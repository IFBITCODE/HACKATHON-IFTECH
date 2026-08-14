<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccurrenceController;
use App\Http\Controllers\EmpreendedorController;
use App\Http\Controllers\PrefeituraDashboardController;

// ==========================================
// ÁREA PÚBLICA / USUÁRIO
// ==========================================

// Página principal do usuário/turista
Route::get('/', function () {
    return view('usuario.HomeUsuario');
});

// ==========================================
// ÁREA DA PREFEITURA
// ==========================================

// Página de login da prefeitura
Route::get('/login-prefeitura', function () {
    return view('prefeitura.login');
});

// Página principal da prefeitura
Route::get(
    '/prefeitura',
    [PrefeituraDashboardController::class, 'index']
)->name('prefeitura.home');

// ==========================================
// ÁREA DO EMPREENDEDOR
// ==========================================

// Tela de Login/Cadastro
Route::get('/empreendedor', function () {
    return view('empreendedor.homeEmpreendedor');
})->name('login');

// Painel do empreendedor
Route::get(
    '/empreendedor/controle',
    [EmpreendedorController::class, 'painel']
);

// Página de controle do empreendedor
Route::get('/logadoempreendedor', function () {
    return view('empreendedor.controleEmpreendedor');
});

// ==========================================
// CHATBOT
// ==========================================

// Abrir o chat
Route::get('/chat', [ChatbotController::class, 'index']);

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