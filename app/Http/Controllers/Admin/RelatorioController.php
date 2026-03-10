<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use App\Models\Nota;
use App\Models\Vale;
use App\Models\CompanyUser;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $adminId = null;

        if ($user instanceof CompanyUser) {
            if (!in_array('visualizar_relatorios', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $inicio = $request->input('inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fim = $request->input('fim', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $vales = Vale::where('admin_id', $adminId)->orWhereNull('admin_id')->get();
        $funcionarios = Funcionario::where('admin_id', $adminId)->where('ativo', true)->get();

        $notas = Nota::where('admin_id', $adminId)
            ->where('status', 'aprovado')
            ->whereBetween('created_at', [$inicio . ' 00:00:00', $fim . ' 23:59:59'])
            ->get();

        $dados = [];
        $totaisPorVale = [];
        $totalGeral = 0;

        foreach ($vales as $vale) {
            $totaisPorVale[$vale->id] = 0;
        }

        foreach ($funcionarios as $func) {
            $linha = [
                'funcionario' => $func,
                'valores_por_vale' => [],
                'total_funcionario' => 0
            ];

            foreach ($vales as $vale) {
                $soma = $notas->where('funcionario_id', $func->id)
                              ->where('vale_id', $vale->id)
                              ->sum('valor_total_nota');

                $linha['valores_por_vale'][$vale->id] = $soma;
                $linha['total_funcionario'] += $soma;
                
                $totaisPorVale[$vale->id] += $soma;
            }

            $totalGeral += $linha['total_funcionario'];
            $dados[] = $linha;
        }

        return view('admin.relatorios.index', compact('dados', 'vales', 'totaisPorVale', 'totalGeral', 'inicio', 'fim'));
    }

    public function exportarPdf(Request $request)
    {
        $user = Auth::user();
        $adminId = null;
        $authorId = null;

        if ($user instanceof CompanyUser) {
            if (!in_array('visualizar_relatorios', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
            $authorId = $user->id;
        } else {
            $adminId = $user->id;
            $authorId = null;
        }

        $inicio = $request->input('inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fim = $request->input('fim', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $vales = Vale::where('admin_id', $adminId)->orWhereNull('admin_id')->get();
        $funcionarios = Funcionario::where('admin_id', $adminId)->where('ativo', true)->get();

        $notas = Nota::where('admin_id', $adminId)
            ->where('status', 'aprovado')
            ->whereBetween('created_at', [$inicio . ' 00:00:00', $fim . ' 23:59:59'])
            ->get();

        $dados = [];
        $totaisPorVale = [];
        $totalGeral = 0;

        foreach ($vales as $vale) {
            $totaisPorVale[$vale->id] = 0;
        }

        foreach ($funcionarios as $func) {
            $linha = [
                'funcionario' => $func,
                'valores_por_vale' => [],
                'total_funcionario' => 0
            ];

            foreach ($vales as $vale) {
                $soma = $notas->where('funcionario_id', $func->id)
                              ->where('vale_id', $vale->id)
                              ->sum('valor_total_nota');

                $linha['valores_por_vale'][$vale->id] = $soma;
                $linha['total_funcionario'] += $soma;
                
                $totaisPorVale[$vale->id] += $soma;
            }

            $totalGeral += $linha['total_funcionario'];
            $dados[] = $linha;
        }

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Exportou relatório geral de reembolsos PDF."
        ]);

        $pdf = Pdf::loadView('admin.relatorios.pdf', compact('dados', 'vales', 'totaisPorVale', 'totalGeral', 'inicio', 'fim'));
        return $pdf->download('relatorio_geral_' . date('Ymd_His') . '.pdf');
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $adminId = null;

        if ($user instanceof CompanyUser) {
            if (!in_array('visualizar_relatorios', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
        } else {
            $adminId = $user->id;
        }

        $inicio = $request->input('inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fim = $request->input('fim', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $funcionario = Funcionario::where('admin_id', $adminId)
            ->with(['vales.tipo'])
            ->findOrFail($id);

        $relatorioVales = [];
        $totalGastoGeral = 0;

        foreach ($funcionario->vales as $configVale) {
            $limiteCalculado = $configVale->valor;
            if ($configVale->periodicidade == 'diario') $limiteCalculado *= 22; 
            if ($configVale->periodicidade == 'semanal') $limiteCalculado *= 4; 

            $notasDoVale = Nota::where('admin_id', $adminId)
                ->where('funcionario_id', $funcionario->id)
                ->where('vale_id', $configVale->vale_id)
                ->where('status', 'aprovado')
                ->whereBetween('created_at', [$inicio . ' 00:00:00', $fim . ' 23:59:59'])
                ->orderBy('created_at', 'desc')
                ->get();

            $totalGasto = $notasDoVale->sum('valor_total_nota');
            $saldoDisponivel = $limiteCalculado - $totalGasto;

            $relatorioVales[] = [
                'config' => $configVale,
                'nome_vale' => $configVale->tipo ? $configVale->tipo->nome : 'Desconhecido',
                'limite' => $limiteCalculado,
                'gasto' => $totalGasto,
                'saldo' => $saldoDisponivel,
                'notas' => $notasDoVale
            ];

            $totalGastoGeral += $totalGasto;
        }

        return view('admin.relatorios.show', compact('funcionario', 'relatorioVales', 'totalGastoGeral', 'inicio', 'fim'));
    }

    public function exportarPdfFuncionario(Request $request, $id)
    {
        $user = Auth::user();
        $adminId = null;
        $authorId = null;

        if ($user instanceof CompanyUser) {
            if (!in_array('visualizar_relatorios', $user->permissions ?? [])) {
                return redirect()->route('acessorestrito');
            }
            $adminId = $user->admin_id;
            $authorId = $user->id;
        } else {
            $adminId = $user->id;
            $authorId = null;
        }

        $inicio = $request->input('inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fim = $request->input('fim', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $funcionario = Funcionario::where('admin_id', $adminId)
            ->with(['vales.tipo'])
            ->findOrFail($id);

        $relatorioVales = [];
        $totalGastoGeral = 0;

        foreach ($funcionario->vales as $configVale) {
            $limiteCalculado = $configVale->valor;
            if ($configVale->periodicidade == 'diario') $limiteCalculado *= 22;
            if ($configVale->periodicidade == 'semanal') $limiteCalculado *= 4;

            $notasDoVale = Nota::where('admin_id', $adminId)
                ->where('funcionario_id', $funcionario->id)
                ->where('vale_id', $configVale->vale_id)
                ->where('status', 'aprovado')
                ->whereBetween('created_at', [$inicio . ' 00:00:00', $fim . ' 23:59:59'])
                ->orderBy('created_at', 'desc')
                ->get();

            $totalGasto = $notasDoVale->sum('valor_total_nota');
            $saldoDisponivel = $limiteCalculado - $totalGasto;

            $relatorioVales[] = [
                'config' => $configVale,
                'nome_vale' => $configVale->tipo ? $configVale->tipo->nome : 'Desconhecido',
                'limite' => $limiteCalculado,
                'gasto' => $totalGasto,
                'saldo' => $saldoDisponivel,
                'notas' => $notasDoVale
            ];

            $totalGastoGeral += $totalGasto;
        }

        Auditoria::create([
            'admin_id' => $adminId,
            'user_id' => $authorId,
            'log' => "Exportou relatório individual PDF de {$funcionario->nome}."
        ]);

        $pdf = Pdf::loadView('admin.relatorios.pdf_funcionario', compact('funcionario', 'relatorioVales', 'totalGastoGeral', 'inicio', 'fim'));
        return $pdf->download('relatorio_' . str_replace(' ', '_', strtolower($funcionario->nome)) . '.pdf');
    }
}