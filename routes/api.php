<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ProjetoController;
use App\Http\Controllers\Api\LancamentoController;

Route::apiResource('clientes', ClienteController::class);

Route::apiResource('projetos', ProjetoController::class);

Route::apiResource('lancamentos', LancamentoController::class);

Route::get('projetos/{projeto}/dashboard', [ProjetoController::class, 'dashboard']);