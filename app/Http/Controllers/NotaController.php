<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\NotaProduto;
use App\Models\ChaveApi;
use App\Models\Funcionario;
use App\Models\FuncionarioVale;
use App\Models\Configuracao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DateTime;

class NotaController extends Controller
{
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

    private function rotateKey()
    {
        $current = ChaveApi::where('ativo', 1)->first();
        if (!$current) {
            return $this->getActiveKey();
        }
        $current->update(['ativo' => 0]);
        $next = ChaveApi::where('id', '>', $current->id)->first();
        if (!$next) {
            $next = ChaveApi::first();
        }
        $next->update(['ativo' => 1]);
        return $next;
    }

    public function analisar(Request $request)
    {
        $request->validate([
            'imagem' => 'required',
            'email' => 'required|email'
        ]);

        $func = Funcionario::where('email', $request->email)->first();
        if (!$func) {
            return response()->json(['erro' => 'Funcionário não encontrado pelo email.'], 404);
        }

        $funcionario_id = $func->id;
        $admin_id = $func->admin_id;

        $config = Configuracao::where('admin_id', $admin_id)->first();
        $permiteAlcool = false;
        
        if ($config && is_array($config->permitido) && in_array('bebidas_alcoolicas', $config->permitido)) {
            $permiteAlcool = true;
        }

        $instrucaoAlcool = $permiteAlcool 
            ? "A empresa PERMITE bebidas alcoólicas. NÃO reprove a nota apenas por conter álcool. Se houver álcool, apenas categorize como 'bebida'."
            : "A empresa NÃO PERMITE bebidas alcoólicas. Se houver qualquer item alcoólico (cerveja, vinho, etc), defina o status como REPROVADO e o motivo 'Contém bebida alcoólica'.";

        $valesFuncionario = FuncionarioVale::with('tipo')
            ->where('funcionario_id', $funcionario_id)
            ->get();

        $listaVales = $valesFuncionario->map(fn($v) => [
            'id' => $v->vale_id,
            'nome' => $v->tipo ? $v->tipo->nome : 'Vale Desconhecido'
        ])->toArray();

        $jsonVales = json_encode($listaVales);

        $base64 = $request->imagem;

        $keyModel = $this->getActiveKey();
        $apiKey = $keyModel->api_key;

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

        $prompt = <<<PROMPT
Você é um auditor fiscal experiente e especialista em OCR.
Sua missão é extrair dados com precisão absoluta e JULGAR a validade do documento.

Lista de vales do funcionário:
$jsonVales

--- CONFIGURAÇÃO DA EMPRESA ---
$instrucaoAlcool

--- REGRAS DE EXTRAÇÃO ---
1. **QUANTIDADE:** O formato brasileiro usa ponto para milhar e vírgula para decimal. Mas, muitas vezes impressoras fiscais imprimem "1.000" querendo dizer "1 unidade".
   - **Regra:** Se for 1.000, considere 1.0. Se for 0.500, considere 0.5. Retorne sempre FLOAT puro.
2. **VALORES:** Todo item deve ter valor total. Se faltar, calcule: (Quantidade * Valor Unitário).
3. **DADOS EXTRAS:** Procure atentamente pelo **Endereço** e pelo **Número do Documento** (pode aparecer como: COO, CCF, Extrato, Nº Nota, Danfe).

--- REGRAS DE STATUS (JUIZ) ---
Defina o campo "status" e "motivo":

1. **REPROVADO**
   - Se violar a regra de bebidas alcoólicas acima.
   - Se o documento parecer falso ou ilegível.
   - Se não é uma nota e sim qualquer outro tipo de imagem (ex: foto de produto, tela de celular, etc).
   - Motivo obrigatório.
2. **PENDENTE**
   - Se NÃO for nota fiscal válida (ex: Recibo, Pedido, Cupom Não Fiscal).
   - Se tiver dúvida nos valores.
   - Motivo obrigatório.
3. **APROVADO**
   - Apenas se for NFC-e, SAT, NF-e ou Cupom Fiscal válido.
   - Dados claros e respeitando as regras da empresa.

RETORNE APENAS ESTE JSON:
{
  "vale_id": (id do vale correspondente da lista fornecida ou null se não combinar),
  "status": "aprovado | pendente | reprovado",
  "motivo": "Explicação da decisão (obrigatório se não aprovado e se tiver bebida alcoolica e nao for permitido e tambem tiver a data antiga diga o motivo principalmente da bebida alcolina)",
  "tipo_documento": "nfc-e, sat, cupom_nao_fiscal, recibo, etc",
  "numero_documento": "O número da nota/COO/Extrato (string ou null se não encontrar)",
  "estabelecimento": "Nome da Loja",
  "cnpj": "CNPJ limpo ou null",
  "endereco": "Endereço completo da loja (string ou null se não encontrar)",
  "data_compra": "DD/MM/AAAA",
  "valor_total": (float ex: 100.50),
  "probabilidade_nota_autentica": (0-100),
  "contém_bebida_alcoolica": (true/false),
  "itens_alcoolicos": ["item1"],
  "itens": [
    {
      "nome": "Descrição",
      "categoria": "alimento, bebida, outros",
      "quantidade": (FLOAT PURO. Ex: 1.0),
      "valor_unitario": (FLOAT),
      "valor_total": (FLOAT)
    }
  ]
}
PROMPT;

        $payload = [
            "contents" => [
                [
                    "parts" => [
                        [
                            "inline_data" => [
                                "mime_type" => "image/jpeg",
                                "data" => $base64
                            ]
                        ],
                        ["text" => $prompt]
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "x-goog-api-key" => $apiKey
        ])->post($url, $payload);

        if ($response->status() == 429) {
            $novaChave = $this->rotateKey();
            return response()->json([
                "erro" => "Limite da chave excedido (429). Chave trocada.",
                "chave_antiga" => $apiKey,
                "nova_chave" => $novaChave->api_key
            ], 429);
        }

        if ($response->failed()) {
            Log::error('FALHA_CHAMADA_GEMINI', ['status' => $response->status(), 'body' => $response->json()]);
            return response()->json([
                "erro" => "Erro ao chamar Gemini.",
                "status" => $response->status(),
                "detalhes" => $response->json()
            ], $response->status());
        }

        $json = $response->json();
        
        try {
            $text = $json["candidates"][0]["content"]["parts"][0]["text"] ?? '';
            $clean = preg_replace('/```json|```/', '', $text);
            $clean = trim($clean);
            $notaIA = json_decode($clean, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("JSON inválido: " . json_last_error_msg());
            }

            Log::info('JSON_IA_PROCESSADO', $notaIA);

        } catch (\Exception $e) {
            Log::error('ERRO_PARSE', ['msg' => $e->getMessage(), 'raw' => $json]);
            return response()->json(["erro" => "Falha ao processar resposta da IA"], 500);
        }

        if (!empty($notaIA['data_compra'])) {
            try {
                $dataCompra = DateTime::createFromFormat('d/m/Y', $notaIA['data_compra']);
                if ($dataCompra) {
                    $inicioMesAtual = new DateTime('first day of this month');
                    $inicioMesAtual->setTime(0, 0, 0);

                    if ($dataCompra < $inicioMesAtual) {
                        return response()->json([
                            'erro' => 'A nota é antiga e inválida. Data da compra: ' . $notaIA['data_compra'],
                            'data_detectada' => $notaIA['data_compra']
                        ], 400);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Erro ao validar data', ['data' => $notaIA['data_compra']]);
            }
        }

        try {
            DB::beginTransaction();

            $dataFormatada = null;
            if (!empty($notaIA['data_compra'])) {
                try {
                    $d = DateTime::createFromFormat('d/m/Y', $notaIA['data_compra']);
                    $dataFormatada = $d ? $d->format('Y-m-d') : date('Y-m-d');
                } catch (\Exception $e) {
                    $dataFormatada = date('Y-m-d');
                }
            }

            $statusFinal = isset($notaIA['status']) ? strtolower($notaIA['status']) : 'pendente';
            if (!in_array($statusFinal, ['aprovado', 'pendente', 'reprovado'])) {
                $statusFinal = 'pendente';
            }

            $nota = Nota::create([
                'admin_id' => $admin_id,
                'funcionario_id' => $funcionario_id,
                'vale_id' => $notaIA['vale_id'] ?? null,
                'tipo' => $notaIA['tipo_documento'] ?? 'desconhecido',
                'status' => $statusFinal,
                'motivo' => $notaIA['motivo'] ?? null,
                'imagem' => $base64,
                'numero_documento' => $notaIA['numero_documento'] ?? null,
                'endereco' => $notaIA['endereco'] ?? null,
                'emitente' => $notaIA['estabelecimento'] ?? null,
                'cnpj_cpf_emitente' => $notaIA['cnpj'] ?? null,
                'data_emissao' => $dataFormatada,
                'valor_total_nota' => floatval($notaIA['valor_total'] ?? 0),
                'estabelecimento' => $notaIA['estabelecimento'] ?? null,
                'probabilidade_nota_autentica' => $notaIA['probabilidade_nota_autentica'] ?? 0,
                'contem_bebida_alcoolica' => $notaIA['contém_bebida_alcoolica'] ?? false,
                'itens_alcoolicos' => $notaIA['itens_alcoolicos'] ?? []
            ]);

            if (!empty($notaIA['itens'])) {
                foreach ($notaIA['itens'] as $item) {
                    $qtd = isset($item['quantidade']) ? floatval($item['quantidade']) : 1.0;
                    $val = isset($item['valor_total']) ? floatval($item['valor_total']) : 0.0;
                    
                    if ($val <= 0 && isset($item['valor_unitario'])) {
                        $val = $qtd * floatval($item['valor_unitario']);
                    }
                    if ($qtd <= 0) $qtd = 1.0;

                    NotaProduto::create([
                        'nota_id' => $nota->id,
                        'produto' => $item['nome'] ?? 'Item',
                        'categoria' => $item['categoria'] ?? 'geral',
                        'valor' => $val,
                        'quantidade' => $qtd
                    ]);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ERRO_DB', ['msg' => $e->getMessage()]);
            return response()->json(['erro' => 'Erro ao salvar no banco.'], 500);
        }

        return response()->json([
            'mensagem' => 'Processado com sucesso',
            'status_definido' => $nota->status,
            'motivo' => $nota->motivo,
            'nota' => $nota->fresh(['produtos'])
        ]);
    }
}