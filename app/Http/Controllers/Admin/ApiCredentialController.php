<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ApiCredential;
use App\Models\UserPlano;
use App\Models\Plano;
use App\Models\CompanyUser;

class ApiCredentialController extends Controller
{
    private $permissoesDisponiveis = [
        'enviar_notas' => 'Enviar Notas Fiscais (Upload)',
        'listar_funcionarios' => 'Obter Lista de Funcionários',
        'criar_funcionario' => 'Cadastrar Novo Funcionário',
        'listar_vales' => 'Listar Tipos de Vales',
        'gerenciar_saldo' => 'Adicionar/Remover Saldo',
        'consultar_extrato' => 'Consultar Extrato de Uso',
    ];

    public function index()
    {
        $user = Auth::user();

        if ($user instanceof CompanyUser) {
            return redirect()->route('acessorestrito');
        }

        $userPlano = UserPlano::where('admin_id', $user->id)->first();
        
        if (!$userPlano) {
            return redirect()->route('plan.selection');
        }

        $plano = Plano::find($userPlano->plano_id);
        
        $temAcessoApi = false;
        if ($plano && is_array($plano->descricao)) {
            foreach ($plano->descricao as $item) {
                if (str_contains($item, 'API Dedicada')) {
                    $temAcessoApi = true;
                    break;
                }
            }
        }

        if (!$temAcessoApi) {
            return redirect()->route('admin.planos.index')->with('error', 'Seu plano atual não permite acesso à API. Faça o upgrade para o plano Corporativo.');
        }

        $credential = ApiCredential::where('admin_id', $user->id)->first();

        return view('admin.api.index', [
            'credential' => $credential,
            'permissoesDisponiveis' => $this->permissoesDisponiveis
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user instanceof CompanyUser) {
            abort(403);
        }

        $request->validate([
            'permissoes' => 'required|array',
            'permissoes.*' => 'in:' . implode(',', array_keys($this->permissoesDisponiveis)),
        ]);

        ApiCredential::where('admin_id', $user->id)->delete();

        ApiCredential::create([
            'admin_id' => $user->id,
            'token' => 'vt_' . Str::random(60),
            'name' => 'Token de Acesso ' . date('d/m/Y'),
            'permissoes' => $request->permissoes,
        ]);

        return redirect()->route('admin.api.index')->with('success', 'Nova chave de API gerada com sucesso.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        ApiCredential::where('admin_id', $user->id)->where('id', $id)->delete();

        return redirect()->route('admin.api.index')->with('success', 'Chave de API revogada.');
    }
}