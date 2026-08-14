<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccurrenceController;
use App\Http\Controllers\EmpreendedorController;


//rota da pagina principal do usuario/turista 
Route::get('/', function () {
    return view('HomeUsuario');
});

// Rota para abrir a página de login da prefeitura
Route::get('/login-prefeitura', function(){
    return view('prefeitura.login');

// PÁGINA PRINCIPAL DA PREFEITURA

Route::get('/prefeitura', function () {
    return view('prefeitura.homePrefeitura');
});

// ==========================================
// ÁREA DO EMPREENDEDOR
// ==========================================

// 1. A Rota Pública (Tela de Login/Cadastro)
Route::get('/empreendedor', function () {

// PÁGINA PRINCIPAL DO EMPREENDEDOR

Route::get('/empreendedor', function () {
    return view('empreendedor.homeEmpreendedor');
})->name('login');

// Rota do Painel VIP (Sem middleware para o Hackathon)
Route::get('/empreendedor/controle', [EmpreendedorController::class, 'painel']);

// Abrir o chat, caso você queira acessar /chat diretamente
Route::get('/chat', [ChatbotController::class, 'index']);
Route::post('/chat', [ChatbotController::class, 'responder']);

Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);

Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);


// EMPREENDEDOR LOGADO

Route::get('/logadoempreendedor', function () {
    return view('empreendedor.controleEmpreendedor');
});


// ADMIN
Route::get(
    '/admin/dashboard',
    [DashboardController::class, 'index']
)->name('admin.dashboard');


Route::resource(
    '/admin/occurrences',
    OccurrenceController::class
)->names('admin.occurrences');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::resource('/admin/occurrences', OccurrenceController::class)->names('admin.occurrences');