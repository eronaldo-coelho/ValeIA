<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PagamentoPlano;
use App\Http\Controllers\Admin\PagamentoPlanoController;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            // Log::info('Webhook Recebido:', $request->all());

            $type = $request->input('type') ?? $request->input('topic');
            $id = $request->input('data.id') ?? $request->input('id');

            // Ignora se não for pagamento
            if ($type !== 'payment' || !$id) {
                return response()->json(['status' => 'ignored'], 200);
            }

            $response = Http::withToken(env('MERCADO_PAGO_ACESS_TOKEN'))
                ->get("https://api.mercadopago.com/v1/payments/{$id}");

            if ($response->failed()) return response()->json(['status' => 'error'], 500);

            $data = $response->json();
            $status = $data['status'];
            $externalReference = $data['id'];

            $pagamento = PagamentoPlano::where('external_reference', $externalReference)->first();

            if ($pagamento) {
                // Atualiza status
                if ($pagamento->status !== $status) {
                    $pagamento->update(['status' => $status]);
                }

                // Processa renovação se aprovado e não efetivado
                if ($status === 'approved' && !$pagamento->efetivado) {
                    $controller = new PagamentoPlanoController();
                    $controller->renovarPlanoUsuario($pagamento);
                }
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}