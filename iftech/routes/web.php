<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccurrenceController;
use App\Http\Controllers\EmpreendedorController;
use App\Http\Controllers\PrefeituraDashboardController;
use App\Http\Controllers\AuthController;

// Página principal do usuário/turista
Route::get('/', function () {
    return view('usuario.HomeUsuario');
})->name('home');
// ==========================================
// ÁREA DA PREFEITURA
// ==========================================

/*
 LOGIN COM GOOGLE
 */
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])
    ->name('google.login');

Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])
    ->name('google.callback');


/*
| ÁREA DA PREFEITURA

*/

// Página de login da prefeitura
Route::get('/login-prefeitura', function () {
    return view('prefeitura.login');
}) ->name('login-prefeitura');

Route::post('/login-prefeitura', [AuthController::class, 'loginPrefeitura'])
    ->name('login-prefeitura.submit');

Route::post('/logout-prefeitura', [AuthController::class, 'logoutPrefeitura'])
    ->middleware('auth')
    ->name('logout-prefeitura');

Route::get('/logado-prefeitura', [PrefeituraDashboardController::class, 'index'])
    ->middleware(['auth', 'prefeitura'])
    ->name('prefeitura.homePrefeitura');

// Página principal da prefeitura
Route::get(
    '/prefeitura',
    [PrefeituraDashboardController::class, 'index']
)->name('prefeitura.home');

// ==========================================
// ÁREA DO EMPREENDEDOR
// ==========================================

// Tela de Login/Cadastro

Route::get('/login-empreendedor', function () {
    return view('empreendedor.homeEmpreendedor');
})->name('login-empreendedor');


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

// Abrir o chat
Route::get('/chat', [ChatbotController::class, 'index']);

// Envia a mensagem do usuário e retorna a resposta da IA
Route::post('/chat', [ChatbotController::class, 'responder']);

// Diagnóstico do chatbot
Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);

// ==========================================
// ADMIN
// ==========================================

Route::get(
    '/admin/dashboard',
    [DashboardController::class, 'index']
)->name('admin.dashboard');

Route::resource(
    '/admin/occurrences',
    OccurrenceController::class
)->names('admin.occurrences');