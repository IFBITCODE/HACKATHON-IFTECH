<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrefeituraController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
use App\Http\Controllers\EmpreendedorController;

Route::get('/empreendedores', [EmpreendedorController::class, 'index']);
Route::get('/empreendedores/{id}', [EmpreendedorController::class, 'show']);
Route::post('/empreendedores', [EmpreendedorController::class, 'store']);
Route::put('/empreendedores/{id}', [EmpreendedorController::class, 'update']);
Route::patch('/empreendedores/{id}/status', [EmpreendedorController::class, 'alterarStatus']);
Route::delete('/empreendedores/{id}', [EmpreendedorController::class, 'destroy']);



// Rota para a prefeitura ver quem está aguardando (GET)
Route::get('/prefeitura/empreendedores/pendentes', [PrefeituraController::class, 'listarPendentes']);

// Rota para a prefeitura APROVAR o empreendedor (PATCH ou PUT)
Route::patch('/prefeitura/empreendedores/{id}/aprovar', [PrefeituraController::class, 'aprovarEmpreendedor']);

// Rota para a prefeitura REJEITAR o empreendedor (PATCH ou PUT)
Route::patch('/prefeitura/empreendedores/{id}/rejeitar', [PrefeituraController::class, 'rejeitarEmpreendedor']);

