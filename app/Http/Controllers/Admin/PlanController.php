<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPlano;
use App\Models\Plano;
use App\Models\PagamentoPlano;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PlanController extends Controller
{
    public function index()
    {
        $userPlano = UserPlano::where('admin_id', Auth::id())->firstOrFail();
        $currentPlan = Plano::find($userPlano->plano_id);
        $allPlans = Plano::all();

        // Se data_vencimento for nulo, fallback para lógica antiga (15 dias do inicio)
        if (!$userPlano->data_vencimento) {
            $renovationDate = $userPlano->data_inicio->addDays(15);
        } else {
            $renovationDate = $userPlano->data_vencimento;
        }

        $daysToRenovation = Carbon::now()->diffInDays($renovationDate, false);
        
        $hasPaid = PagamentoPlano::where('admin_id', Auth::id())
            ->where('status', 'approved')
            ->exists();

        $isTrial = !$hasPaid;

        if ($daysToRenovation < 0) $daysToRenovation = 0;

        // Pode renovar (pagar) se faltam 15 dias ou menos
        $canRenewEarly = $daysToRenovation <= 15;

        // SÓ PODE TROCAR DE PLANO se faltarem 2 dias ou menos (ou se já expirou)
        $canChangePlan = $daysToRenovation <= 2;

        return view('admin.planos.index', compact(
            'currentPlan', 
            'allPlans', 
            'isTrial', 
            'renovationDate', 
            'daysToRenovation',
            'canRenewEarly',
            'canChangePlan' // Nova variável
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'plano_id' => 'required|exists:planos,id'
        ]);

        $userPlano = UserPlano::where('admin_id', Auth::id())->first();

        // Recalcula dias para validação de segurança no backend
        $renovationDate = $userPlano->data_vencimento ?? $userPlano->data_inicio->addDays(15);
        $daysToRenovation = Carbon::now()->diffInDays($renovationDate, false);

        // Se faltar mais de 2 dias, bloqueia a troca
        if ($daysToRenovation > 2) {
            return redirect()->back()->with('error', 'A troca de plano só é permitida faltando 2 dias para o vencimento.');
        }

        $userPlano->update([
            'plano_id' => $request->plano_id
        ]);

        return redirect()->back()->with('success', 'Plano de interesse atualizado! Realize o pagamento para efetivar a troca.');
    }
}