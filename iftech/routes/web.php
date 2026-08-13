<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;

Route::get('/', function () {
    return view('HomeUsuario');
});

Route::get('/', function () {
    return view('welcome');
});

// Rota para abrir a página do chat (Método GET)
Route::get('/chat', [ChatbotController::class, 'index']);

// Rota para quando o usuário clicar em "Enviar" a mensagem (Método POST)
Route::post('/chat', [ChatbotController::class, 'responder']);

Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);