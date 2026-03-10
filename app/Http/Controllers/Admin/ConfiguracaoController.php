<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Configuracao;
use App\Models\CompanyUser;

class ConfiguracaoController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            return redirect()->route('acessorestrito');
        }

        $configuracao = Configuracao::firstOrCreate(
            ['admin_id' => $user->id],
            ['permitido' => ['bebidas_alcoolicas' => false]]
        );

        return view('admin.configuracoes.index', compact('user', 'configuracao'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            return redirect()->route('acessorestrito');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'document' => 'required|string|max:20|unique:users,document,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'document' => preg_replace('/[^0-9]/', '', $request->document),
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && strpos($user->avatar, 'storage') !== false) {
                $path = str_replace('/storage/', 'public/', parse_url($user->avatar, PHP_URL_PATH));
                Storage::delete($path);
            }

            $file = $request->file('avatar');
            $path = $file->store('public/avatars');
            $url = Storage::url($path);
            $dataToUpdate['avatar'] = asset($url);
        }

        $user->update($dataToUpdate);

        $configuracao = Configuracao::where('admin_id', $user->id)->first();
        
        $permitido = $configuracao->permitido ?? [];
        $permitido['bebidas_alcoolicas'] = $request->has('bebidas_alcoolicas');

        $configuracao->update([
            'permitido' => $permitido
        ]);

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso.');
    }
}