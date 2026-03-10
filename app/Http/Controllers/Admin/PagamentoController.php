<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChaveApi;
use App\Models\Funcionario;
use App\Models\PagamentoFuncionario;
use App\Models\PagamentoVale;
use App\Models\Auditoria;
use App\Models\CompanyUser;
use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PagamentoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $adminId = $user instanceof CompanyUser ? $user->admin_id : $user->id;
        
        if ($user instanceof CompanyUser && !in_array('reembolsar', $user->permissions ?? [])) {
            return redirect()->route('acessorestrito');
        }
        
        $pagamentos = PagamentoFuncionario::where('admin_id', $adminId)
            ->with('funcionario')
            ->latest()
            ->get();

        // Adiciona nomes dos vales pagos virtualmente
        foreach ($pagamentos as $pg) {
            $valesNomes = PagamentoVale::where('pagamento_funcionario_id', $pg->id)
                ->join('vales', 'pagamento_vales.vale_id', '=', 'vales.id')
                ->pluck('vales.nome')
                ->unique()
                ->implode(', ');
            $pg->nomes_vales = $valesNomes ?: 'Avulso';
        }

        return view('admin.pagamentos.index', compact('pagamentos'));
    }

    public function create()
    {
        $user = Auth::user();
        $adminId = $user instanceof CompanyUser ? $user->admin_id : $user->id;

        $funcionarios = Funcionario::where('admin_id', $adminId)
            ->where('ativo', 1)
            ->with(['vales.tipo', 'contas' => function($q) {
                $q->where('principal', true);
            }])
            ->get();

        foreach ($funcionarios as $func) {
            foreach ($func->vales as $funcVale) {
                $totalGasto = Nota::where('admin_id', $adminId)
                    ->where('funcionario_id', $func->id)
                    ->where('vale_id', $funcVale->vale_id)
                    ->where('status', 'aprovado')
                    ->sum('valor_total_nota');
                
                $totalJaPago = PagamentoVale::whereHas('pagamento', function($q) use ($func, $adminId) {
                        $q->where('funcionario_id', $func->id)
                          ->where('admin_id', $adminId);
                    })
                    ->where('vale_id', $funcVale->vale_id)
                    ->sum('valor_pago');

                $funcVale->saldo_devedor = max(0, $totalGasto - $totalJaPago);
            }
        }

        return view('admin.pagamentos.create', compact('funcionarios'));
    }

    private function getActiveKey()
    {
        $active = ChaveApi::where('ativo', 1)->first();
        if (!$active) {
            $first = ChaveApi::first();
            if ($first) {
                $first->update(['ativo' => 1]);
                return $first;
            }
        }
        return $active;
    }

    public function analisar(Request $request)
    {
        $request->validate([
            'imagem' => 'required',
            'modo' => 'required|in:preencher,validar'
        ]);

        $base64 = $request->imagem;
        if (strpos($base64, 'base64,') !== false) {
            $base64 = substr($base64, strpos($base64, 'base64,') + 7);
        }

        $keyModel = $this->getActiveKey();
        if (!$keyModel) {
            return response()->json(['erro' => 'Chave de API não configurada no sistema.'], 500);
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";
        
        if ($request->modo == 'preencher') {
            $prompt = <<<PROMPT
Analise este comprovante de pagamento bancário. Extraia os dados com precisão.
Retorne APENAS um JSON válido neste formato (sem markdown):
{
    "data_pagamento": "AAAA-MM-DD",
    "valor": 0.00,
    "forma_pagamento": "Texto (Ex: Pix, TED)"
}
PROMPT;
        } else {
            // Modo Validação
            $dadosConta = json_encode($request->conta_dados);
            $valorEsperado = $request->valor_esperado;
            
            $prompt = <<<PROMPT
Analise este comprovante e valide se ele corresponde ao pagamento esperado.
Dados esperados da conta destino: {$dadosConta}
Valor esperado: {$valorEsperado}

Verifique:
1. O valor no comprovante é igual ou muito próximo ao esperado?
2. O beneficiário/conta destino no comprovante bate com os dados fornecidos?
3. A data é recente (últimos 60 dias)?

Retorne APENAS um JSON:
{
    "valido": true/false,
    "motivo": "Explicação curta se for falso, ou 'OK' se verdadeiro"
}
PROMPT;
        }

        $payload = [
            "contents" => [[
                "parts" => [
                    ["inline_data" => ["mime_type" => "image/jpeg", "data" => $base64]],
                    ["text" => $prompt]
                ]
            ]]
        ];

        try {
            $response = Http::withHeaders(["x-goog-api-key" => $keyModel->api_key])->post($url, $payload);
            
            if ($response->failed()) {
                return response()->json(['erro' => 'Falha na comunicação com a IA.'], 500);
            }

            $json = $response->json();
            $text = $json["candidates"][0]["content"]["parts"][0]["text"] ?? '';
            $clean = preg_replace('/```json|```/', '', $text);
            $dados = json_decode(trim($clean), true);

            if (!$dados) {
                return response()->json(['erro' => 'IA retornou dados inválidos.'], 500);
            }

            return response()->json($dados);

        } catch (\Exception $e) {
            return response()->json(['erro' => 'Erro interno ao processar imagem.'], 500);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $adminId = $user instanceof CompanyUser ? $user->admin_id : $user->id;
        $authorId = $user instanceof CompanyUser ? $user->id : null;

        $request->validate([
            'funcionario_id' => 'required|exists:funcionarios,id',
            'valor' => 'required|numeric|min:0.01',
            'data_pagamento' => 'required|date',
            'forma_pagamento' => 'required|string',
            'imagem_base64' => 'required'
        ]);

        DB::transaction(function () use ($request, $adminId, $authorId) {
            $pagamento = PagamentoFuncionario::create([
                'admin_id' => $adminId,
                'funcionario_id' => $request->funcionario_id,
                'imagem' => $request->imagem_base64,
                'forma_pagamento' => $request->forma_pagamento,
                'valor' => $request->valor,
                'data_pagamento' => $request->data_pagamento,
            ]);

            $valorRestante = $request->valor;

            if ($request->has('funcionario_vale_id')) {
                foreach ($request->funcionario_vale_id as $valeId) {
                    if ($valorRestante <= 0) break;

                    // Recalcula dívida real
                    $totalGasto = Nota::where('admin_id', $adminId)
                        ->where('funcionario_id', $request->funcionario_id)
                        ->where('vale_id', $valeId)
                        ->where('status', 'aprovado')
                        ->sum('valor_total_nota');

                    $totalJaPago = PagamentoVale::whereHas('pagamento', function($q) use ($request, $adminId) {
                            $q->where('funcionario_id', $request->funcionario_id)
                              ->where('admin_id', $adminId);
                        })
                        ->where('vale_id', $valeId)
                        ->sum('valor_pago');

                    $dividaAtual = max(0, $totalGasto - $totalJaPago);

                    if ($dividaAtual > 0) {
                        $valorAbatido = min($dividaAtual, $valorRestante);
                        
                        PagamentoVale::create([
                            'pagamento_funcionario_id' => $pagamento->id,
                            'vale_id' => $valeId,
                            'valor_pago' => $valorAbatido
                        ]);

                        $valorRestante -= $valorAbatido;
                    }
                }
            }

            Auditoria::create([
                'admin_id' => $adminId,
                'user_id' => $authorId,
                'log' => "Registrou pagamento ID {$pagamento->id} para funcionário ID {$request->funcionario_id} no valor de R$ {$request->valor}."
            ]);
        });

        return redirect()->route('admin.pagamentos.index')->with('success', 'Pagamento registrado com sucesso.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $adminId = $user instanceof CompanyUser ? $user->admin_id : $user->id;

        $funcionario = Funcionario::where('admin_id', $adminId)->findOrFail($id);
        
        $pagamentos = PagamentoFuncionario::where('admin_id', $adminId)
            ->where('funcionario_id', $id)
            ->orderBy('data_pagamento', 'desc')
            ->get();

        foreach ($pagamentos as $pg) {
            $valesNomes = PagamentoVale::where('pagamento_funcionario_id', $pg->id)
                ->join('vales', 'pagamento_vales.vale_id', '=', 'vales.id')
                ->pluck('vales.nome')
                ->unique()
                ->implode(', ');
            $pg->nomes_vales = $valesNomes ?: 'Avulso';
        }

        return view('admin.pagamentos.show', compact('funcionario', 'pagamentos'));
    }
}