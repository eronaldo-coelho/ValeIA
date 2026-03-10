<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotaController;

Route::post('/analisar-nota', [NotaController::class, 'analisar']);
