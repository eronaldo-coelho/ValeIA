<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plano;
use App\Models\UserPlano;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PlanSelectionController extends Controller
{
    public function index()
    {
        $hasPlan = UserPlano::where('admin_id', Auth::id())->exists();
        if ($hasPlan) {
            return redirect()->route('dashboard');
        }

        $planos = Plano::all();
        return view('plan-selection', compact('planos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plano_id' => 'required|exists:planos,id'
        ]);

        UserPlano::create([
            'admin_id' => Auth::id(),
            'plano_id' => $request->plano_id,
            'data_inicio' => Carbon::now(),
        ]);

        return redirect()->route('dashboard');
    }
}