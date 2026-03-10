<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\PagamentoPlano;
use App\Models\Plano;
use App\Models\UserPlano;
use App\Models\User;
use Carbon\Carbon;

class PagamentoPlanoController extends Controller
{
    public function gerarPagamento(Request $request)
    {
        $request->validate([
            'plano_id' => 'required|exists:planos,id',
            'metodo' => 'required|in:pix,bolbradesco'
        ]);

        try {
            $user = Auth::user();
            $plano = Plano::findOrFail($request->plano_id);
            
            $metodoDb = $request->metodo == 'bolbradesco' ? 'boleto' : 'pix';
            
            $pagamentoExistente = PagamentoPlano::where('admin_id', $user->id)
                ->where('plano_id', $plano->id)
                ->where('metodo_pagamento', $metodoDb)
                ->where('status', 'pending')
                ->where('data_expiracao', '>', Carbon::now())
                ->latest()
                ->first();

            if ($pagamentoExistente) {
                return redirect()->route('admin.planos.pagamento', $pagamentoExistente->id);
            }
            
            $valor = $plano->valor;
            if($plano->desconto > 0) {
                $valor = $plano->valor - ($plano->valor * ($plano->desconto / 100));
            }

            $diasExpiracao = ($request->metodo == 'bolbradesco') ? 25 : 29;
            $dataExpiracao = Carbon::now()->addDays($diasExpiracao);

            $docNumber = preg_replace('/[^0-9]/', '', $user->document);
            $idempotencyKey = (string) Str::uuid();

            $payload = [
                'transaction_amount' => (float) number_format($valor, 2, '.', ''),
                'description' => 'Plano ' . $plano->nome,
                'payment_method_id' => $request->metodo,
                'date_of_expiration' => $dataExpiracao->format('Y-m-d\TH:i:s.vP'),
                'payer' => [
                    'email' => $user->email,
                    'first_name' => explode(' ', $user->name)[0],
                    'last_name' => explode(' ', $user->name)[1] ?? 'User',
                    'identification' => [
                        'type' => strlen($docNumber) > 11 ? 'CNPJ' : 'CPF',
                        'number' => $docNumber
                    ],
                    'address' => [
                        'zip_code' => '01310-930',
                        'street_name' => 'Av. Paulista',
                        'street_number' => '1578',
                        'neighborhood' => 'Bela Vista',
                        'city' => 'São Paulo',
                        'federal_unit' => 'SP'
                    ]
                ]
            ];

            Log::info('MercadoPago Payload:', $payload);

            $response = Http::withToken(env('MERCADO_PAGO_ACESS_TOKEN'))
                ->withHeaders(['X-Idempotency-Key' => $idempotencyKey])
                ->post('https://api.mercadopago.com/v1/payments', $payload);

            if ($response->failed()) {
                $errorMsg = $response->json()['message'] ?? 'Erro desconhecido.';
                Log::error('Falha MP: ' . $errorMsg);
                return back()->with('error', 'Erro no Mercado Pago: ' . $errorMsg);
            }

            $data = $response->json();
            
            if (!isset($data['id'])) {
                return back()->with('error', 'Erro ao obter ID do pagamento.');
            }

            $pagamento = PagamentoPlano::create([
                'admin_id' => $user->id,
                'plano_id' => $plano->id,
                'external_reference' => $data['id'],
                'metodo_pagamento' => $metodoDb,
                'status' => $data['status'],
                'efetivado' => false,
                'valor' => $valor,
                'data_expiracao' => $dataExpiracao,
                'qr_code' => $data['point_of_interaction']['transaction_data']['qr_code'] ?? null,
                'qr_code_base64' => $data['point_of_interaction']['transaction_data']['qr_code_base64'] ?? null,
                'boleto_url' => $data['transaction_details']['external_resource_url'] ?? null,
                'boleto_linha_digitavel' => $data['barcode']['content'] ?? null,
            ]);

            return redirect()->route('admin.planos.pagamento', $pagamento->id);

        } catch (\Exception $e) {
            Log::critical('Exceção Pagamento: ' . $e->getMessage());
            return back()->with('error', 'Erro interno.');
        }
    }

    public function show($id)
    {
        $pagamento = PagamentoPlano::where('admin_id', Auth::id())->findOrFail($id);
        
        if ($pagamento->status === 'approved' && $pagamento->efetivado) {
            return redirect()->route('admin.planos.index')->with('success', 'Pagamento aprovado e plano renovado!');
        }

        return view('admin.planos.pagamento', compact('pagamento'));
    }

    public function checkStatus($id)
    {
        try {
            $pagamento = PagamentoPlano::where('admin_id', Auth::id())->findOrFail($id);

            if ($pagamento->status === 'approved' && $pagamento->efetivado) {
                return response()->json(['status' => 'approved']);
            }

            $response = Http::withToken(env('MERCADO_PAGO_ACESS_TOKEN'))
                ->get("https://api.mercadopago.com/v1/payments/{$pagamento->external_reference}");

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['status'];

                if ($pagamento->status !== $status) {
                    $pagamento->update(['status' => $status]);
                }

                if ($status === 'approved' && !$pagamento->efetivado) {
                    $this->renovarPlanoUsuario($pagamento);
                }

                return response()->json(['status' => $status]);
            }

            return response()->json(['status' => $pagamento->status]);

        } catch (\Exception $e) {
            Log::error("Erro checkStatus: " . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function renovarPlanoUsuario($pagamento)
    {
        $userPlano = UserPlano::where('admin_id', $pagamento->admin_id)->first();
        $user = User::find($pagamento->admin_id);
                        
        if ($userPlano && $user) {
            $fimDoTesteGratis = Carbon::parse($user->created_at)->addDays(15);
            $agora = Carbon::now();

            if ($userPlano->data_vencimento) {
                // Caso 1: Já tem vencimento (renovação). Se for futuro, usa ele. Se já passou, usa agora.
                $vencimentoAtual = Carbon::parse($userPlano->data_vencimento);
                $baseDate = $vencimentoAtual->isFuture() ? $vencimentoAtual : $agora;
            } else {
                // Caso 2: Primeiro pagamento.
                // Se hoje é ANTES do fim do teste, a base é o fim do teste (soma os dias restantes).
                // Se o teste já acabou, a base é agora.
                if ($agora->lessThan($fimDoTesteGratis)) {
                    $baseDate = $fimDoTesteGratis;
                } else {
                    $baseDate = $agora;
                }
            }

            $novoVencimento = $baseDate->copy()->addDays(30);

            $userPlano->update([
                'plano_id' => $pagamento->plano_id,
                'data_vencimento' => $novoVencimento
            ]);
            
            $pagamento->update(['efetivado' => true]);

            Log::info("Renovação OK. Admin: {$pagamento->admin_id}. Base: {$baseDate}. Novo Vencimento: {$novoVencimento}");
        }
    }
}