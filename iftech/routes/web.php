<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccurrenceController;
use App\Http\Controllers\EmpreendedorController;

// Rota da pagina principal do usuario/turista 
Route::get('/', function () {
    return view('usuario.HomeUsuario');
});

// Rota da pagina principal da prefeitura
Route::get('/prefeitura', function () {
    return view('prefeitura.homePrefeitura');
});

// ==========================================
// ÁREA DO EMPREENDEDOR
// ==========================================

// 1. A Rota Pública (Tela de Login/Cadastro)
Route::get('/empreendedor', function () {
    return view('empreendedor.homeEmpreendedor');
})->name('login');

// Rota do Painel VIP (Sem middleware para o Hackathon)
Route::get('/empreendedor/controle', [EmpreendedorController::class, 'painel']);

// ==========================================
// CHATBOT E ADMIN
// ==========================================

Route::get('/chat', [ChatbotController::class, 'index']);
Route::post('/chat', [ChatbotController::class, 'responder']);
Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::resource('/admin/occurrences', OccurrenceController::class)->names('admin.occurrences');