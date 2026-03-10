<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Nota;
use App\Models\Funcionario;
use App\Models\FuncionarioVale;
use App\Models\CompanyUser;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('company')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // CORREÇÃO: Verifica se é Admin (User) antes de cobrar o documento
        if ($user instanceof User && empty($user->document)) {
            return redirect()->route('auth.complete');
        }

        // Define o ID do Administrador responsável
        if ($user instanceof CompanyUser) {
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalGastoMes = Nota::where('admin_id', $adminId)
            ->where('status', 'aprovado')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('valor_total_nota');

        $notasPendentes = Nota::where('admin_id', $adminId)
            ->where('status', 'pendente')
            ->count();

        $funcionariosAtivos = Funcionario::where('admin_id', $adminId)
            ->where('ativo', 1)
            ->count();

        $creditoTotalMensal = FuncionarioVale::where('funcionarios_vales.admin_id', $adminId)
            ->join('funcionarios', 'funcionarios_vales.funcionario_id', '=', 'funcionarios.id')
            ->where('funcionarios.ativo', 1)
            ->get()
            ->sum(function ($vale) {
                if ($vale->periodicidade == 'diario') {
                    return $vale->valor * 22; 
                } elseif ($vale->periodicidade == 'semanal') {
                    return $vale->valor * 4;
                }
                return $vale->valor;
            });

        $saldoRestante = $creditoTotalMensal - $totalGastoMes;

        $stats = [
            'total_gasto' => 'R$ ' . number_format($totalGastoMes, 2, ',', '.'),
            'notas_pendentes' => $notasPendentes,
            'funcionarios_ativos' => $funcionariosAtivos,
            'saldo_restante' => 'R$ ' . number_format($saldoRestante, 2, ',', '.')
        ];

        $recentNotes = Nota::where('admin_id', $adminId)
            ->with(['funcionario', 'vale'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recent_receipts = $recentNotes->map(function ($nota) {
            $statusMap = [
                'aprovado' => 'approved',
                'pendente' => 'pending',
                'reprovado' => 'rejected'
            ];

            return [
                'id' => '#NB-' . $nota->id,
                'user' => $nota->funcionario ? $nota->funcionario->nome : 'Desconhecido',
                'date' => Carbon::parse($nota->created_at)->diffForHumans(),
                'amount' => 'R$ ' . number_format($nota->valor_total_nota, 2, ',', '.'),
                'status' => $statusMap[strtolower($nota->status)] ?? 'pending',
                'type' => $nota->vale ? $nota->vale->nome : ucfirst(str_replace('_', ' ', $nota->tipo ?? 'Geral'))
            ];
        });

        $chartData = [];
        $chartLabels = [];
        
        $weekMap = [
            0 => 'Dom',
            1 => 'Seg',
            2 => 'Ter',
            3 => 'Qua',
            4 => 'Qui',
            5 => 'Sex',
            6 => 'Sáb'
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $weekMap[$date->dayOfWeek]; 
            
            $val = Nota::where('admin_id', $adminId)
                ->where('status', 'aprovado')
                ->whereDate('created_at', $date->toDateString())
                ->sum('valor_total_nota');
            
            $chartData[] = (float) number_format($val, 2, '.', '');
        }

        $valesData = Nota::select('vales.nome as nome_vale', DB::raw('sum(notas.valor_total_nota) as total'))
            ->leftJoin('vales', 'notas.vale_id', '=', 'vales.id')
            ->where('notas.admin_id', $adminId)
            ->where('notas.status', 'aprovado')
            ->groupBy('vales.nome')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        $catLabels = $valesData->map(fn($v) => $v->nome_vale ?? 'Outros')->toArray();
        
        $catSeries = $valesData->map(function($v) {
            return (float) number_format($v->total, 2, '.', '');
        })->toArray();

        if (empty($catLabels)) {
            $catLabels = ['Sem dados'];
            $catSeries = [0];
        }

        return view('admin.dashboard', compact(
            'user', 
            'stats', 
            'recent_receipts', 
            'chartLabels', 
            'chartData', 
            'catLabels', 
            'catSeries'
        ));
    }
}