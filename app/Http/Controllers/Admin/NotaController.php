<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nota;
use App\Models\Vale;
use App\Models\CompanyUser; 
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('aprovar_notas', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $query = Nota::where('admin_id', $adminId)->with(['funcionario', 'vale']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vale_id')) {
            $query->where('vale_id', $request->vale_id);
        }

        if ($request->filled('data')) {
            $query->whereDate('created_at', $request->data);
        }

        $notas = $query->orderByRaw("FIELD(status, 'pendente', 'aprovado', 'reprovado')")
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

        $vales = Vale::where('admin_id', $adminId)->orWhereNull('admin_id')->get();

        return view('admin.notas.index', compact('notas', 'vales'));
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('aprovar_notas', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $nota = Nota::where('admin_id', $adminId)
            ->with(['funcionario', 'vale', 'produtos'])
            ->findOrFail($id);

        return view('admin.notas.show', compact('nota'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('aprovar_notas', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
            $authorId = $user->id;
        } else {
            $adminId = $user->id;
            $authorId = null;
        }

        $nota = Nota::where('admin_id', $adminId)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:aprovado,reprovado,pendente',
            'motivo' => 'nullable|string|max:1000',
        ]);

        $nota->update([
            'status' => $request->status,
            'motivo' => $request->motivo,
        ]);

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Atualizou o status da nota #{$nota->id} para {$request->status}."
        ]);

        return redirect()->back()->with('success', 'Status da nota atualizado com sucesso.');
    }
}