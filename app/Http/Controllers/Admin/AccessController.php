<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyUser;
use App\Models\User;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccessController extends Controller
{
    private function filterPermissions(array $requestedPermissions, $user)
    {
        if ($user instanceof User) {
            return $requestedPermissions; 
        }

        $userPermissions = $user->permissions ?? [];
        return array_intersect($requestedPermissions, $userPermissions);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_equipe', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $users = CompanyUser::where('admin_id', $adminId)->get();
        
        return view('admin.acessos.index', compact('users'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_equipe', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
        }

        return view('admin.acessos.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_equipe', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
            $authorId = $user->id;
        } else {
            $adminId = $user->id;
            $authorId = null; 
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_users,email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:rh,financeiro,gestor,funcionario',
            'permissions' => 'nullable|array'
        ]);

        $permissionsToSave = $this->filterPermissions($request->permissions ?? [], $user);

        $newUser = CompanyUser::create([
            'admin_id' => $adminId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'permissions' => $permissionsToSave,
        ]);

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Criou um novo usuário de equipe: {$newUser->name} ({$newUser->email}) com cargo {$newUser->role}."
        ]);

        return redirect()->route('admin.acessos.index');
    }

    public function edit($id)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_equipe', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $targetUser = CompanyUser::where('admin_id', $adminId)->findOrFail($id);
        return view('admin.acessos.edit', ['user' => $targetUser]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_equipe', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
            $authorId = $user->id;
        } else {
            $adminId = $user->id;
            $authorId = null;
        }

        $targetUser = CompanyUser::where('admin_id', $adminId)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('company_users')->ignore($targetUser->id)],
            'role' => 'required|in:rh,financeiro,gestor,funcionario',
            'permissions' => 'nullable|array'
        ]);

        $permissionsToSave = $this->filterPermissions($request->permissions ?? [], $user);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'permissions' => $permissionsToSave,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $targetUser->update($data);

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Atualizou o usuário de equipe: {$targetUser->name}."
        ]);

        return redirect()->route('admin.acessos.index');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_equipe', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
            $authorId = $user->id;
        } else {
            $adminId = $user->id;
            $authorId = null;
        }

        $targetUser = CompanyUser::where('admin_id', $adminId)->findOrFail($id);
        $name = $targetUser->name;
        $targetUser->delete();

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Excluiu o usuário de equipe: {$name}."
        ]);

        return redirect()->route('admin.acessos.index');
    }
}