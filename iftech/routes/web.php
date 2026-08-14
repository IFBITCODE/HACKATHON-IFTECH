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
    return view('prefeitura.loginPrefeitura.login');
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
})->name('login');


// Logout da sessão web
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Painel do empreendedor (Atalho / Rota Legada) — agora exige login
Route::get('/empreendedor/controle', [EmpreendedorController::class, 'painel'])
    ->middleware('auth');

// Página de controle do empreendedor oficial — agora exige login
Route::get('/logado-empreendedor', [EmpreendedorController::class, 'painel'])
    ->middleware('auth')
    ->name('empreendedor.painel');

// Códigos de troca do empreendedor
Route::middleware('auth')->group(function () {
    Route::get('/empreendedor/codigos', [EmpreendedorController::class, 'listarCodigos']);
    Route::post('/empreendedor/codigos/gerar', [EmpreendedorController::class, 'gerarCodigo']);
    Route::post('/usuario/codigos/usar', [EmpreendedorController::class, 'usarCodigo']);
});

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