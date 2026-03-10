<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use App\Models\FuncionarioVale;
use App\Models\Vale;
use App\Models\CompanyUser;
use App\Models\Auditoria;
use App\Models\ContaFuncionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class FuncionarioController extends Controller
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

        $funcionarios = Funcionario::where('admin_id', $adminId)->get();
        return view('admin.funcionarios.index', compact('funcionarios'));
    }

    public function create()
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

        $cargos = Funcionario::where('admin_id', $adminId)
            ->select('cargo')
            ->distinct()
            ->pluck('cargo');
        
        $vales = Vale::all();

        return view('admin.funcionarios.create', compact('cargos', 'vales'));
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
            'nome' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'cpf' => 'required|unique:funcionarios,cpf',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'data_admissao' => 'nullable|date',
            'vales' => 'nullable|array',
            'vales.*.vale_id' => 'required_with:vales|exists:vales,id',
            'vales.*.valor' => 'required_with:vales|numeric',
            'vales.*.periodicidade' => 'required_with:vales|in:diario,semanal,mensal',
        ]);

        $funcionario = DB::transaction(function () use ($request, $adminId, $authorId) {
            $funcionario = Funcionario::create([
                'admin_id' => $adminId,
                'nome' => $request->nome,
                'cargo' => $request->cargo,
                'data_nascimento' => $request->data_nascimento,
                'cpf' => $request->cpf,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'data_admissao' => $request->data_admissao,
                'ativo' => true,
            ]);

            if ($request->has('vales')) {
                foreach ($request->vales as $valeData) {
                    if(isset($valeData['vale_id'])) {
                        FuncionarioVale::create([
                            'admin_id' => $adminId,
                            'funcionario_id' => $funcionario->id,
                            'vale_id' => $valeData['vale_id'],
                            'valor' => $valeData['valor'],
                            'periodicidade' => $valeData['periodicidade'],
                        ]);
                    }
                }
            }

            Auditoria::create([
                'admin_id' => $adminId,
                'user_id' => $authorId,
                'log' => "Cadastrou o funcionário: {$funcionario->nome} (CPF: {$funcionario->cpf})."
            ]);

            return $funcionario;
        });

        return redirect()->route('admin.funcionarios.edit', $funcionario->id);
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

        $funcionario = Funcionario::where('admin_id', $adminId)
            ->with(['vales.tipo', 'contas'])
            ->findOrFail($id);
        
        $cargos = Funcionario::where('admin_id', $adminId)
            ->select('cargo')
            ->distinct()
            ->pluck('cargo');

        $vales = Vale::all();

        return view('admin.funcionarios.edit', compact('funcionario', 'cargos', 'vales'));
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

        $funcionario = Funcionario::where('admin_id', $adminId)->findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'cpf' => ['required', Rule::unique('funcionarios')->ignore($funcionario->id)],
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'data_admissao' => 'nullable|date',
            'ativo' => 'boolean'
        ]);

        $funcionario->update($request->all());

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Atualizou dados do funcionário: {$funcionario->nome}."
        ]);

        return redirect()->back()->with('success', 'Dados atualizados com sucesso.');
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

        $funcionario = Funcionario::where('admin_id', $adminId)->findOrFail($id);
        $nome = $funcionario->nome;
        $funcionario->delete();

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Excluiu o funcionário: {$nome}."
        ]);

        return redirect()->route('admin.funcionarios.index');
    }

    public function storeVale(Request $request, $funcionario_id)
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

        $funcionario = Funcionario::where('admin_id', $adminId)->findOrFail($funcionario_id);

        $request->validate([
            'vale_id' => 'required|exists:vales,id',
            'valor' => 'required|numeric',
            'periodicidade' => 'required|in:diario,semanal,mensal'
        ]);

        FuncionarioVale::create([
            'admin_id' => $adminId,
            'funcionario_id' => $funcionario->id,
            'vale_id' => $request->vale_id,
            'valor' => $request->valor,
            'periodicidade' => $request->periodicidade
        ]);

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Adicionou benefício (Vale ID: {$request->vale_id}) para: {$funcionario->nome}."
        ]);

        return redirect()->back()->with('success', 'Vale adicionado.');
    }

    public function updateVale(Request $request, $id)
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

        $vale = FuncionarioVale::where('admin_id', $adminId)->findOrFail($id);

        $request->validate([
            'valor' => 'required|numeric',
            'periodicidade' => 'required|in:diario,semanal,mensal'
        ]);

        $vale->update([
            'valor' => $request->valor,
            'periodicidade' => $request->periodicidade
        ]);

        $funcionario = Funcionario::find($vale->funcionario_id);

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Atualizou benefício de {$funcionario->nome}: R$ {$request->valor} / {$request->periodicidade}."
        ]);

        return redirect()->back()->with('success', 'Vale atualizado.');
    }

    public function destroyVale($id)
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

        $vale = FuncionarioVale::where('admin_id', $adminId)->findOrFail($id);
        $funcId = $vale->funcionario_id;
        $vale->delete();

        $funcionario = Funcionario::find($funcId);

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Removeu um benefício do funcionário: {$funcionario->nome}."
        ]);

        return redirect()->back();
    }

    public function storeConta(Request $request, $funcionario_id)
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

        $funcionario = Funcionario::where('admin_id', $adminId)->findOrFail($funcionario_id);

        $request->validate([
            'tipo_pagamento' => 'required|in:pix,ted,doc,transferencia',
            'frequencia_pagamento' => 'required|in:mensal,semanal,diario',
            'chave_pix' => 'required_if:tipo_pagamento,pix',
            'banco' => 'required_unless:tipo_pagamento,pix',
            'agencia' => 'required_unless:tipo_pagamento,pix',
            'conta' => 'required_unless:tipo_pagamento,pix',
        ]);

        $temPrincipal = ContaFuncionario::where('funcionario_id', $funcionario->id)->exists();

        ContaFuncionario::create([
            'admin_id' => $adminId,
            'funcionario_id' => $funcionario->id,
            'tipo_pagamento' => $request->tipo_pagamento,
            'chave_pix' => $request->chave_pix,
            'tipo_chave_pix' => $request->tipo_chave_pix,
            'banco' => $request->banco,
            'agencia' => $request->agencia,
            'conta' => $request->conta,
            'tipo_conta' => $request->tipo_conta,
            'frequencia_pagamento' => $request->frequencia_pagamento,
            'dia_pagamento' => $request->dia_pagamento,
            'dia_semana' => $request->dia_semana,
            'principal' => !$temPrincipal
        ]);

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Adicionou conta bancária para: {$funcionario->nome}."
        ]);

        return redirect()->back()->with('success', 'Conta bancária adicionada.');
    }

    public function destroyConta($id)
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

        $conta = ContaFuncionario::where('admin_id', $adminId)->findOrFail($id);
        $conta->delete();

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Removeu conta bancária."
        ]);

        return redirect()->back();
    }

    public function setContaPrincipal($id)
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $conta = ContaFuncionario::where('admin_id', $adminId)->findOrFail($id);
        
        ContaFuncionario::where('funcionario_id', $conta->funcionario_id)->update(['principal' => false]);
        $conta->update(['principal' => true]);

        return redirect()->back();
    }
}