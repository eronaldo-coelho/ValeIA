<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Whatsapp;
use App\Models\Funcionario;
use App\Models\Nota;
use App\Models\PagamentoFuncionario;
use Carbon\Carbon;

class BotWhatsapp extends Command
{
    protected $signature = 'bot:whatsapp';
    protected $description = 'Bot WhatsApp ValeIA';

    private string $apiUrl = 'http://127.0.0.1:3000';
    private string $localApiUrl = 'https://valeia.com.br/api/analisar-nota';

    public function handle()
    {
        $this->info("=== BOT INICIADO ===");
        $this->line("Conectado a API Node: " . $this->apiUrl);

        while (true) {
            try {
                $response = Http::timeout(10)->get($this->apiUrl . '/messages');

                if ($response->successful()) {
                    $messages = $response->json();
                    
                    if (is_array($messages) && count($messages) > 0) {
                        foreach ($messages as $msg) {
                            $this->processMessage($msg);
                        }
                    } 
                }
            } catch (\Exception $e) {
                $this->error("ERRO CONEXÃO: " . $e->getMessage());
            }

            sleep(2);
        }
    }

    private function processMessage($msg)
    {
        $number = $msg['number'] ?? null;
        $chatId = $msg['chatId'] ?? null;
        $text = trim($msg['message'] ?? '');
        $mediaFile = $msg['mediaFileName'] ?? null;
        $hasMedia = $msg['hasMedia'] ?? false; // Novo campo
        $type = $msg['type'] ?? 'chat'; // Novo campo
        $timestamp = isset($msg['timestamp']) ? (int)$msg['timestamp'] : time(); 

        if (!$number || !$chatId) {
            return;
        }

        $user = Whatsapp::firstOrCreate(
            ['chat_id' => $chatId],
            ['phone_number' => $number]
        );

        if ($timestamp <= $user->last_message_timestamp) {
            return;
        }

        $this->info("-> Msg de {$number}: " . ($hasMedia ? "[MÍDIA]" : $text));

        $user->update(['last_message_timestamp' => $timestamp]);

        if ($user->funcionario_id && $user->pin) {
            $this->handleLoggedUser($user, $chatId, $text, $mediaFile, $hasMedia, $type);
        } else {
            $this->handleRegistration($user, $chatId, $text);
        }
    }

    private function handleRegistration(Whatsapp $user, $chatId, $message)
    {
        $this->line("   [Cadastro] Etapa: {$user->step}");
        $tempData = $user->temp_data ?? [];

        switch ($user->step) {
            case 'START':
                $this->sendMessage($chatId, "Olá! Sou o assistente virtual da ValeIA. \n\nPara configurar seu acesso, preciso validar seu cadastro (Nome, CPF e Data de Nascimento).\n\nVocê autoriza a consulta? Responda *SIM*.");
                $user->update(['step' => 'WAITING_CONSENT']);
                break;

            case 'WAITING_CONSENT':
                if (strtoupper(trim($message)) === 'SIM') {
                    $this->sendMessage($chatId, "Ótimo! Por favor, digite seu *Nome Completo*:");
                    $user->update(['step' => 'WAITING_NAME']);
                } else {
                    $this->sendMessage($chatId, "Sem sua autorização não podemos prosseguir. Digite SIM quando estiver pronto.");
                }
                break;

            case 'WAITING_NAME':
                $tempData['nome'] = $message;
                $user->update(['temp_data' => $tempData, 'step' => 'WAITING_CPF']);
                $this->sendMessage($chatId, "Obrigado. Agora digite seu *CPF* (apenas números):");
                break;

            case 'WAITING_CPF':
                $cpf = preg_replace('/[^0-9]/', '', $message);
                $tempData['cpf'] = $cpf;
                $user->update(['temp_data' => $tempData, 'step' => 'WAITING_BIRTH']);
                $this->sendMessage($chatId, "Certo. Digite sua *Data de Nascimento* (Ex: 26/05/2008):");
                break;

            case 'WAITING_BIRTH':
                try {
                    $dataNasc = null;
                    if (strpos($message, '/') !== false) {
                        $dataNasc = Carbon::createFromFormat('d/m/Y', $message)->format('Y-m-d');
                    } elseif (strpos($message, '-') !== false) {
                        $dataNasc = Carbon::createFromFormat('Y-m-d', $message)->format('Y-m-d');
                    } else {
                        throw new \Exception("Formato inválido");
                    }
                    
                    $cpfLimpo = $tempData['cpf'];
                    $cpfFormatado = substr($cpfLimpo, 0, 3) . '.' . substr($cpfLimpo, 3, 3) . '.' . substr($cpfLimpo, 6, 3) . '-' . substr($cpfLimpo, 9, 2);

                    $this->line("   Buscando CPF: {$cpfFormatado} Data: {$dataNasc}");

                    $funcionario = Funcionario::where(function($query) use ($cpfLimpo, $cpfFormatado) {
                            $query->where('cpf', $cpfLimpo)
                                  ->orWhere('cpf', $cpfFormatado);
                        })
                        ->where('data_nascimento', $dataNasc)
                        ->where('ativo', true)
                        ->first();

                    if ($funcionario) {
                        $this->info("   Funcionário: {$funcionario->nome}");
                        $user->update([
                            'admin_id' => $funcionario->admin_id,
                            'funcionario_id' => $funcionario->id,
                            'step' => 'CREATE_PIN',
                            'temp_data' => null
                        ]);
                        $this->sendMessage($chatId, "✅ Cadastro localizado: " . $funcionario->nome . "\n\nPara finalizar, crie um *PIN de 4 números* que será sua assinatura digital:");
                    } else {
                        $this->warn("   Funcionário não encontrado.");
                        $this->sendMessage($chatId, "❌ Dados não conferem no sistema.\nCPF buscado: $cpfFormatado\nData: " . date('d/m/Y', strtotime($dataNasc)) . "\n\nDigite 'Oi' para tentar novamente.");
                        $user->update(['step' => 'START', 'temp_data' => null]);
                    }
                } catch (\Exception $e) {
                    $this->sendMessage($chatId, "Data inválida. Use o formato DD/MM/AAAA (Ex: 26/05/2008):");
                }
                break;

            case 'CREATE_PIN':
                if (preg_match('/^\d{4}$/', $message)) {
                    $user->update(['pin' => $message, 'step' => 'LOGGED_IN']);
                    $this->sendMessage($chatId, "🔒 PIN cadastrado! Acesso liberado.");
                    $this->sendMenu($chatId);
                } else {
                    $this->sendMessage($chatId, "O PIN deve conter exatamente 4 números. Tente novamente:");
                }
                break;
        }
    }

    private function handleLoggedUser(Whatsapp $user, $chatId, $message, $mediaFile, $hasMedia, $type)
    {
        $this->line("   [Logado] Etapa: {$user->step}");

        // 1. Verifica fluxo de PIN para envio de Nota
        if ($user->step === 'VERIFY_PIN_NOTA') {
            if ($message === $user->pin) {
                $this->processNotaApi($user, $chatId);
            } else {
                $this->sendMessage($chatId, "🚫 PIN Incorreto. Envio cancelado.");
                $user->update(['step' => 'LOGGED_IN', 'temp_data' => null]);
                $this->sendMenu($chatId);
            }
            return;
        }

        // 2. Verifica fluxo de visualização de comprovante
        if ($user->step === 'WAITING_RECEIPT_ID') {
            if (is_numeric($message)) {
                $this->sendComprovanteImage($user, $chatId, $message);
            } elseif (strtolower($message) == 'sair') {
                $user->update(['step' => 'LOGGED_IN']);
                $this->sendMenu($chatId);
            } else {
                $this->sendMessage($chatId, "⚠️ Digite o número do ID do comprovante ou 'sair' para voltar.");
            }
            return;
        }

        // 3. Detecção de Imagem
        // AQUI ESTAVA O BUG: Se mediaFile for null (erro download), mas hasMedia for true
        if ($hasMedia || $type === 'image') {
            if (!$mediaFile) {
                $this->warn("   Mídia detectada mas arquivo não veio (timeout node).");
                $this->sendMessage($chatId, "⚠️ Recebi uma imagem, mas houve falha ao baixar. Por favor, envie novamente a foto.");
                return;
            }

            $this->info("   Processando Mídia: $mediaFile");
            $this->sendMessage($chatId, "📥 Baixando imagem...");
            $base64Image = $this->downloadMedia($mediaFile);
            
            if ($base64Image) {
                $user->update([
                    'step' => 'VERIFY_PIN_NOTA',
                    'temp_data' => ['pending_image' => $base64Image]
                ]);
                $this->sendMessage($chatId, "📸 Imagem recebida!\n\nDigite seu *PIN de 4 dígitos* para confirmar o envio:");
            } else {
                $this->sendMessage($chatId, "⚠️ Erro ao processar arquivo. Tente enviar novamente.");
            }
            return;
        }

        // 4. Menu Principal (Texto)
        switch (trim($message)) {
            case '1':
                $this->showSaldo($user, $chatId);
                break;
            case '2':
                $this->sendMessage($chatId, "📝 *Nova Nota*\n\nEnvie uma *FOTO* nítida do cupom fiscal agora.");
                break;
            case '3':
                $this->showNotasStatus($user, $chatId);
                break;
            case '4':
                $this->showPagamentos($user, $chatId);
                break;
            default:
                $this->sendMenu($chatId);
                break;
        }
    }

    private function sendMenu($chatId)
    {
        $this->sendMessage($chatId, "*MENU VALEIA*\n\n1️⃣ Gastos e Saldo de Vales\n2️⃣ Registrar Nova Nota\n3️⃣ Status das Notas Enviadas\n4️⃣ Comprovantes de Pagamento\n\nDigite o número da opção:");
    }

    private function showSaldo($user, $chatId)
    {
        $funcionario = Funcionario::with(['vales.tipo', 'contas'])->find($user->funcionario_id);
        
        $msg = "*💰 CONTROLE DE GASTOS E VALES:*\n";
        
        if ($funcionario->vales->isEmpty()) {
            $msg .= "Nenhum vale configurado.\n";
        } else {
            foreach ($funcionario->vales as $vale) {
                $nome = $vale->tipo ? $vale->tipo->nome : 'Geral';
                $limite = $vale->valor;
                $periodicidade = $vale->periodicidade;

                $start = null;
                // Ajuste dos períodos
                if ($periodicidade == 'diario') {
                    $start = Carbon::now()->startOfDay();
                } elseif ($periodicidade == 'semanal') {
                    $start = Carbon::now()->startOfWeek();
                } elseif ($periodicidade == 'mensal') {
                    $start = Carbon::now()->startOfMonth();
                }

                $gasto = 0;
                if ($start) {
                    $gasto = Nota::where('funcionario_id', $user->funcionario_id)
                        ->where('vale_id', $vale->vale_id)
                        ->where('created_at', '>=', $start)
                        ->whereIn('status', ['aprovado', 'pendente'])
                        ->sum('valor_total_nota');
                }

                $disponivel = $limite - $gasto;
                if($disponivel < 0) $disponivel = 0;
                
                $msg .= "\n🏷️ *{$nome}* ({$periodicidade})";
                $msg .= "\n   Limite: R$ " . number_format($limite, 2, ',', '.');
                $msg .= "\n   Gasto Atual: R$ " . number_format($gasto, 2, ',', '.');
                $msg .= "\n   Disponível: R$ " . number_format($disponivel, 2, ',', '.') . "\n";
            }
        }

        $this->sendMessage($chatId, $msg);
    }

    private function showNotasStatus($user, $chatId)
    {
        $notas = Nota::where('funcionario_id', $user->funcionario_id)
            ->latest()
            ->take(5)
            ->get();

        if ($notas->isEmpty()) {
            $this->sendMessage($chatId, "Nenhuma nota registrada.");
            return;
        }

        $msg = "*📄 ÚLTIMAS 5 NOTAS ENVIADAS:*\n";
        foreach ($notas as $nota) {
            $status = strtoupper($nota->status);
            
            if ($status == 'APROVADO') $icon = '✅';
            elseif ($status == 'REPROVADO') $icon = '❌';
            else $icon = '🕒';

            $data = $nota->created_at->format('d/m H:i');
            $valor = number_format($nota->valor_total_nota, 2, ',', '.');
            
            $msg .= "{$icon} {$data} - R$ {$valor}\n   Status: {$status}\n";
            
            if($status == 'REPROVADO' && $nota->motivo) {
                 $msg .= "   Obs: {$nota->motivo}\n";
            }
            $msg .= "----------------\n";
        }
        $this->sendMessage($chatId, $msg);
    }

    private function showPagamentos($user, $chatId)
    {
        $pagamentos = PagamentoFuncionario::where('funcionario_id', $user->funcionario_id)
            ->latest()
            ->take(5)
            ->get();

        if ($pagamentos->isEmpty()) {
            $this->sendMessage($chatId, "Nenhum pagamento registrado.");
            return;
        }

        $msg = "*💸 ÚLTIMOS PAGAMENTOS RECEBIDOS:*\n\n";
        foreach ($pagamentos as $pag) {
            $data = $pag->created_at->format('d/m/Y');
            $valor = number_format($pag->valor, 2, ',', '.');
            $msg .= "ID: *{$pag->id}* | Data: {$data}\nValor: R$ {$valor}\nForma: {$pag->forma_pagamento}\n----------------\n";
        }

        $msg .= "\nPara ver o comprovante, digite o *ID* do pagamento (ex: 15). Ou digite 'sair' para voltar.";
        
        $this->sendMessage($chatId, $msg);
        $user->update(['step' => 'WAITING_RECEIPT_ID']);
    }

    private function sendComprovanteImage($user, $chatId, $pagamentoId)
    {
        $pagamento = PagamentoFuncionario::where('id', $pagamentoId)
            ->where('funcionario_id', $user->funcionario_id)
            ->first();

        if (!$pagamento) {
            $this->sendMessage($chatId, "❌ Comprovante não encontrado. Tente outro ID.");
            return;
        }

        if (!$pagamento->imagem) {
            $this->sendMessage($chatId, "⚠️ Este pagamento não possui imagem de comprovante anexada.");
            return;
        }

        $this->sendMessage($chatId, "📤 Enviando comprovante...");

        try {
            // Remove prefixo base64 se existir no banco
            $imgClean = $pagamento->imagem;
            if (strpos($imgClean, 'base64,') !== false) {
                $imgClean = explode('base64,', $imgClean)[1];
            }

            Http::post($this->apiUrl . '/send', [
                'chatId' => $chatId,
                'media' => $imgClean
            ]);
            
            $user->update(['step' => 'LOGGED_IN']);
            $this->sendMenu($chatId);
            
        } catch (\Exception $e) {
            $this->sendMessage($chatId, "❌ Erro ao enviar a imagem do comprovante.");
        }
    }

    private function processNotaApi($user, $chatId)
    {
        $this->sendMessage($chatId, "⏳ Enviando para análise com Inteligência Artificial...");

        $funcionario = Funcionario::find($user->funcionario_id);
        $tempData = $user->temp_data;
        $imagemBase64 = $tempData['pending_image'] ?? null;

        if (!$imagemBase64) {
            $this->sendMessage($chatId, "❌ Erro: Imagem perdida. Envie a foto novamente.");
            $user->update(['step' => 'LOGGED_IN', 'temp_data' => null]);
            return;
        }

        try {
            $this->info("   Chamando API Local...");
            $response = Http::post($this->localApiUrl, [
                'imagem' => $imagemBase64,
                'email' => $funcionario->email
            ]);

            $result = $response->json();

            if ($response->successful()) {
                $status = strtoupper($result['status_definido'] ?? 'PENDENTE');
                $valor = number_format($result['nota']['valor_total_nota'] ?? 0, 2, ',', '.');
                $motivo = $result['motivo'] ?? '';
                
                $msg = "✅ *Processamento Concluído!*\n\n";
                $msg .= "💰 Valor Identificado: R$ {$valor}\n";
                $msg .= "📊 Status Inicial: {$status}\n";
                
                if ($status == 'REPROVADO') {
                    $msg .= "🚫 Motivo: {$motivo}\n";
                } elseif ($status == 'APROVADO') {
                    $msg .= "✨ Nota aprovada automaticamente!\n";
                } else {
                    $msg .= "🕒 Aguardando análise final do gestor.\n";
                }

                $this->sendMessage($chatId, $msg);

            } elseif ($response->status() == 422 || $response->status() == 400) {
                $erro = $result['erro'] ?? 'Imagem inválida.';
                $this->sendMessage($chatId, "⚠️ Nota Recusada pelo Sistema.\n\nMotivo: {$erro}\n\nTente enviar uma foto mais clara.");
            } else {
                $this->sendMessage($chatId, "❌ Erro ao processar a nota (Status " . $response->status() . ")");
            }

        } catch (\Exception $e) {
            $this->error("   Erro API Local: " . $e->getMessage());
            $this->sendMessage($chatId, "❌ Falha crítica de comunicação com o servidor.");
        }

        $user->update(['step' => 'LOGGED_IN', 'temp_data' => null]);
    }

    private function downloadMedia($fileName)
    {
        try {
            $url = "{$this->apiUrl}/download/{$fileName}";
            $this->info("   Baixando mídia: $url");
            
            $response = Http::get($url);

            if ($response->successful()) {
                return base64_encode($response->body());
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }

    private function sendMessage($chatId, $content)
    {
        $this->info("   -> Enviando para $chatId...");
        try {
            $response = Http::post("{$this->apiUrl}/send", [
                'chatId' => $chatId,
                'message' => $content
            ]);
            
            if(!$response->successful()) {
                $this->error("   ERRO ENVIO: " . $response->body());
            }
        } catch (\Exception $e) {
            $this->error("   EXCEPTION ENVIO: " . $e->getMessage());
        }
    }
}