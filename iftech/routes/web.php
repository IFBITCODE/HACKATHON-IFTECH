<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccurrenceController;
use App\Http\Controllers\EmpreendedorController;
use App\Http\Controllers\PrefeituraDashboardController;

/*
| ÁREA PÚBLICA / USUÁRIO
*/
// Página principal do usuário/turista
Route::get('/', function () {
    return view('usuario.HomeUsuario');
})->name('home');


/*
| ÁREA DA PREFEITURA

*/
// Página de login da prefeitura
Route::get('/login-prefeitura', function () {
    return view('prefeitura.login');
});

// Página principal da prefeitura
Route::get('/logado-prefeitura', [PrefeituraDashboardController::class, 'index'])
    ->name('prefeitura.home');

/*
| ÁREA DO EMPREENDEDOR

*/
// Tela de Login/Cadastro
Route::get('/login-empreendedor', function () {
    return view('empreendedor.homeEmpreendedor');
});

// Painel do empreendedor (Atalho / Rota Legada)
Route::get('/empreendedor/controle', [EmpreendedorController::class, 'painel']);

// Página de controle do empreendedor oficial
Route::get('/logado-empreendedor', [EmpreendedorController::class, 'painel'])
    ->name('empreendedor.painel');


/*
| CHATBOT / GUIA TURÍSTICO
*/
// Abrir o chat
Route::get('/chat', [ChatbotController::class, 'index']);

// Enviar pergunta via AJAX/Fetch
Route::post('/chat', [ChatbotController::class, 'responder']);

// Diagnóstico do chatbot
Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);


/*
| ADMIN

*/
Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

Route::resource('/admin/occurrences', OccurrenceController::class)
    ->names('admin.occurrences');