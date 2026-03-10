<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plano;

class HomeController extends Controller
{
    public function index()
    {
        $planos = Plano::all();
        return view('welcome', compact('planos'));
    }
}