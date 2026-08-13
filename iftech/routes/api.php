<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
use App\Http\Controllers\EmpreendedorController;

Route::get('/empreendedores', [EmpreendedorController::class, 'index']);
Route::get('/empreendedores/{id}', [EmpreendedorController::class, 'show']);
Route::post('/empreendedores', [EmpreendedorController::class, 'store']);
Route::put('/empreendedores/{id}', [EmpreendedorController::class, 'update']);
Route::patch('/empreendedores/{id}/status', [EmpreendedorController::class, 'alterarStatus']);
Route::delete('/empreendedores/{id}', [EmpreendedorController::class, 'destroy']);