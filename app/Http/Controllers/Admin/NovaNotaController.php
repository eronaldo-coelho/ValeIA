<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChaveApi;
use App\Models\Funcionario;
use App\Models\FuncionarioVale;
use App\Models\Nota;
use App\Models\NotaProduto;
use App\Models\Vale;
use App\Models\Auditoria;
use App\Models\CompanyUser;
use App\Models\Configuracao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DateTime;
use Carbon\Carbon;

class NovaNotaController extends Controller
{
    public function index()
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

        $funcionarios = Funcionario::where('admin_id', $adminId)->where('ativo', 1)->get();
        $vales = Vale::all();

        return view('admin.criar_notas.index', compact('funcionarios', 'vales'));
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
            'funcionario_id' => 'required|exists:funcionarios,id',
            'modo' => 'nullable|string'
        ]);

        $base64 = $request->imagem;
        if (strpos($base64, 'base64,') !== false) {
            $base64 = substr($base64, strpos($base64, 'base64,') + 7);
        }

        $keyModel = $this->getActiveKey();
        if (!$keyModel) {
            return response()->json(['erro' => 'Nenhuma chave de API configurada.'], 500);
        }
        $apiKey = $keyModel->api_key;
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

        // MODO VALIDAÇÃO FINAL
        if ($request->modo === 'validar_final') {
            $dadosFormulario = json_encode([
                'valor_total' => $request->valor_total,
                'data_emissao' => $request->data_emissao,
                'estabelecimento' => $request->estabelecimento,
                'cnpj' => $request->cnpj
            ]);

            $prompt = <<<PROMPT
Você é um perito forense digital. Analise a imagem da nota fiscal e compare com os dados informados pelo usuário.

DADOS INFORMADOS: $dadosFormulario

TAREFA:
1. Verifique se o valor total na imagem é EXATAMENTE igual ao informado (aceite variação de 0.05 centavos).
2. Verifique se a data de emissão na imagem bate com a informada.
3. Verifique se o CNPJ ou Nome do Estabelecimento batem (aproximadamente).

Se houver divergência significativa (principalmente valor e data), considere FRAUDE.

RETORNE APENAS ESTE JSON:
{
  "valido": true/false,
  "motivo_recusa": "Se falso, explique curto o motivo. Ex: Data na imagem (10/05) difere da informada (12/05)."
}
PROMPT;
        } else {
            // MODO EXTRAÇÃO INICIAL
            $funcionario = Funcionario::find($request->funcionario_id);
            $valesFuncionario = FuncionarioVale::with('tipo')->where('funcionario_id', $funcionario->id)->get();
            $listaVales = $valesFuncionario->map(fn($v) => ['id' => $v->vale_id, 'nome' => $v->tipo ? $v->tipo->nome : 'Vale'])->toArray();
            $jsonVales = json_encode($listaVales);

            $config = Configuracao::where('admin_id', $funcionario->admin_id)->first();
            $permiteAlcool = $config && is_array($config->permitido) && in_array('bebidas_alcoolicas', $config->permitido);
            $instrucaoAlcool = $permiteAlcool ? "PERMITE álcool." : "NÃO PERMITE álcool.";

            $prompt = <<<PROMPT
Auditor fiscal. Extraia dados.
Vales: $jsonVales
Config: $instrucaoAlcool

JSON RETORNO:
{
  "vale_id": (int|null),
  "data_compra": "AAAA-MM-DD",
  "estabelecimento": "Nome",
  "tipo": "Tipo Doc",
  "cnpj": "00000000000000",
  "endereco": "Endereço",
  "numero_documento": "Num",
  "valor_total": (float),
  "itens": [{"nome": "Desc", "categoria": "alimento/bebida_alcoolica/outros", "quantidade": 1, "valor_unitario": 0, "valor_total": 0}],
  "contem_bebida_alcoolica": (bool),
  "itens_alcoolicos": ["item"]
}
PROMPT;
        }

        $payload = ["contents" => [["parts" => [["inline_data" => ["mime_type" => "image/jpeg", "data" => $base64]], ["text" => $prompt]]]]];

        try {
            $response = Http::withHeaders(["x-goog-api-key" => $apiKey])->post($url, $payload);
            
            if ($response->failed()) return response()->json(['erro' => 'Falha na IA'], 500);

            $json = $response->json();
            $text = $json["candidates"][0]["content"]["parts"][0]["text"] ?? '';
            $clean = preg_replace('/```json|```/', '', $text);
            return response()->json(json_decode(trim($clean), true));

        } catch (\Exception $e) {
            return response()->json(['erro' => 'Erro interno IA'], 500);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $adminId = $user instanceof CompanyUser ? $user->admin_id : $user->id;
        $authorId = $user instanceof CompanyUser ? $user->id : null;

        $request->validate([
            'funcionario_id' => 'required|exists:funcionarios,id',
            'valor_total' => 'required|numeric',
            'data_emissao' => 'required|date',
            'itens' => 'nullable|array',
            'tipo' => 'nullable|string|max:255',
            'vale_id' => 'nullable|exists:vales,id',
            'imagem_base64' => 'required'
        ]);

        $dataNota = Carbon::parse($request->data_emissao);
        $inicioMes = Carbon::now()->startOfMonth();
        
        if ($dataNota->lt($inicioMes)) {
            return back()->withInput()->with('error', "Nota Inválida: Data ({$dataNota->format('d/m/Y')}) anterior ao mês atual.");
        }

        // Validação de Duplicidade (Golpe de Nota Repetida)
        $duplicada = Nota::where('admin_id', $adminId)
            ->where('valor_total_nota', $request->valor_total)
            ->where('data_emissao', $request->data_emissao)
            ->where(function($q) use ($request) {
                if ($request->numero_documento) {
                    $q->where('numero_documento', $request->numero_documento);
                }
            })
            ->exists();

        if ($duplicada) {
            return back()->withInput()->with('error', 'Golpe Detectado: Esta nota fiscal já foi lançada anteriormente no sistema.');
        }

        // Validação de Saldo do Vale (Diário/Semanal/Mensal)
        if ($request->vale_id) {
            $funcVale = FuncionarioVale::where('funcionario_id', $request->funcionario_id)
                ->where('vale_id', $request->vale_id)
                ->first();

            if ($funcVale) {
                $limite = $funcVale->valor;
                $gastoPeriodo = 0;

                $query = Nota::where('funcionario_id', $request->funcionario_id)
                    ->where('vale_id', $request->vale_id)
                    ->where('status', 'aprovado');

                if ($funcVale->periodicidade == 'diario') {
                    $query->whereDate('data_emissao', $dataNota);
                    $periodoMsg = "hoje";
                } elseif ($funcVale->periodicidade == 'semanal') {
                    $startWeek = $dataNota->copy()->startOfWeek();
                    $endWeek = $dataNota->copy()->endOfWeek();
                    $query->whereBetween('data_emissao', [$startWeek, $endWeek]);
                    $periodoMsg = "nesta semana";
                } else { // Mensal
                    $query->whereMonth('data_emissao', $dataNota->month)
                          ->whereYear('data_emissao', $dataNota->year);
                    $periodoMsg = "neste mês";
                }

                $gastoPeriodo = $query->sum('valor_total_nota');
                
                if (($gastoPeriodo + $request->valor_total) > $limite) {
                    $disponivel = max(0, $limite - $gastoPeriodo);
                    return back()->withInput()->with('error', "Saldo Insuficiente: O limite {$funcVale->periodicidade} é R$ {$limite}. Já gastou R$ {$gastoPeriodo}. Disponível: R$ {$disponivel}.");
                }
            } else {
                return back()->withInput()->with('error', 'O funcionário não possui este tipo de vale habilitado.');
            }
        }

        DB::transaction(function () use ($request, $adminId, $authorId) {
            $status = 'aprovado';
            $motivo = null;
            
            $config = Configuracao::where('admin_id', $adminId)->first();
            $permiteAlcool = false;
            if ($config && is_array($config->permitido) && in_array('bebidas_alcoolicas', $config->permitido)) {
                $permiteAlcool = true;
            }

            $temAlcool = false;
            $itensAlcoolicos = [];
            
            if ($request->has('itens')) {
                foreach ($request->itens as $item) {
                    if (isset($item['categoria']) && $item['categoria'] == 'bebida_alcoolica') {
                        $temAlcool = true;
                        $itensAlcoolicos[] = $item['nome'];
                    }
                }
            }

            if ($request->filled('itens_alcoolicos_detectados')) {
                 $detected = json_decode($request->itens_alcoolicos_detectados, true);
                 if (!empty($detected)) {
                     $temAlcool = true;
                     $itensAlcoolicos = array_merge($itensAlcoolicos, $detected);
                 }
            }

            if ($temAlcool && !$permiteAlcool) {
                $status = 'reprovado';
                $motivo = 'Contém bebida alcoólica e a empresa não permite.';
            }

            $nota = Nota::create([
                'admin_id' => $adminId,
                'funcionario_id' => $request->funcionario_id,
                'vale_id' => $request->vale_id,
                'tipo' => $request->tipo,
                'status' => $status,
                'motivo' => $motivo,
                'imagem' => $request->imagem_base64,
                'numero_documento' => $request->numero_documento,
                'emitente' => $request->estabelecimento,
                'cnpj_cpf_emitente' => $request->cnpj,
                'data_emissao' => $request->data_emissao,
                'valor_total_nota' => $request->valor_total,
                'estabelecimento' => $request->estabelecimento,
                'endereco' => $request->endereco,
                'probabilidade_nota_autentica' => 100,
                'contem_bebida_alcoolica' => $temAlcool,
                'itens_alcoolicos' => $itensAlcoolicos
            ]);

            if ($request->has('itens')) {
                foreach ($request->itens as $item) {
                    NotaProduto::create([
                        'nota_id' => $nota->id,
                        'produto' => $item['nome'],
                        'categoria' => $item['categoria'] ?? 'geral',
                        'valor' => $item['valor_total'],
                        'quantidade' => $item['quantidade']
                    ]);
                }
            }

            Auditoria::create([
                'admin_id' => $adminId,
                'user_id' => $authorId,
                'log' => "Criou nota fiscal ID {$nota->id} para o funcionário ID {$request->funcionario_id}."
            ]);
        });

        return redirect()->route('admin.notas.index')->with('success', 'Nota lançada com sucesso!');
    }
}