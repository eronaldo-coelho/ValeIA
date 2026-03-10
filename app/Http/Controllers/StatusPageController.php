<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Nota;

class StatusPageController extends Controller
{
    public function index()
    {
        // --- DADOS SIMULADOS (FIXOS) ---
        $fakeAdmins = 102;
        $fakeNotasIA = 698;
        $fakeNotasManual = 437;
        $fakeItensAlcool = 27; // Soma dos seus exemplos (63 + 29)

        // 1. Total de Admins (Real + Simulado)
        $totalAdmins = User::count() + $fakeAdmins;

        // 2. Notas Processadas (Real + Simulado)
        $realNotasIA = Nota::whereNotNull('imagem')->count();
        $notasIA = $realNotasIA + $fakeNotasIA;

        $realNotasManual = Nota::whereNull('imagem')->count();
        $notasManual = $realNotasManual + $fakeNotasManual;

        $totalNotas = $notasIA + $notasManual;

        // 3. Itens Alcoólicos (Real + Simulado)
        // Primeiro calculamos apenas o real
        $notasComAlcool = Nota::where('contem_bebida_alcoolica', true)->get();
        
        $realItensAlcool = $notasComAlcool->sum(function ($nota) {
            // Verifica se é array e conta, senão retorna 0
            return is_array($nota->itens_alcoolicos) ? count($nota->itens_alcoolicos) : 0;
        });

        // Agora somamos o simulado ao total final
        $totalItensAlcool = $realItensAlcool + $fakeItensAlcool;

        return view('status_page', compact(
            'totalAdmins', 
            'notasIA', 
            'notasManual', 
            'totalNotas', 
            'totalItensAlcool'
        ));
    }
}