<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vale;
use App\Models\FuncionarioVale;
use App\Models\CompanyUser;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_saldo', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $vales = Vale::where('admin_id', $adminId)
            ->orWhereNull('admin_id')
            ->get();

        $vales = $vales->map(function ($vale) use ($adminId) {
            $usos = FuncionarioVale::where('vale_id', $vale->id)
                ->where('admin_id', $adminId)
                ->get();

            $vale->total_funcionarios = $usos->count();
            
            $vale->custo_mensal = $usos->sum(function ($uso) {
                switch ($uso->periodicidade) {
                    case 'diario':
                        return $uso->valor * 22;
                    case 'semanal':
                        return $uso->valor * 4;
                    default:
                        return $uso->valor;
                }
            });

            return $vale;
        });

        return view('admin.vales.index', compact('vales'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_saldo', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
        }

        return view('admin.vales.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_saldo', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
            $authorId = $user->id;
        } else {
            $adminId = $user->id;
            $authorId = null;
        }

        $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        $vale = Vale::create([
            'admin_id' => $adminId,
            'nome' => $request->nome
        ]);

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Criou um novo tipo de vale: {$vale->nome}."
        ]);

        return redirect()->route('admin.vales.index');
    }

    public function edit($id)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_saldo', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $vale = Vale::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        return view('admin.vales.edit', compact('vale'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_saldo', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
            $authorId = $user->id;
        } else {
            $adminId = $user->id;
            $authorId = null;
        }

        $vale = Vale::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        $vale->update([
            'nome' => $request->nome
        ]);

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Atualizou o nome do vale ID #{$vale->id} para {$request->nome}."
        ]);

        return redirect()->route('admin.vales.index');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            if (!in_array('gerenciar_saldo', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
            $authorId = $user->id;
        } else {
            $adminId = $user->id;
            $authorId = null;
        }

        $vale = Vale::where('id', $id)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        $emUso = FuncionarioVale::where('vale_id', $vale->id)->exists();

        if ($emUso) {
            return redirect()->back()->withErrors(['erro' => 'Não é possível excluir um vale que está em uso por funcionários.']);
        }

        $nome = $vale->nome;
        $vale->delete();

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Excluiu o tipo de vale: {$nome}."
        ]);

        return redirect()->route('admin.vales.index');
    }
}