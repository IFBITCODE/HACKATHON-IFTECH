<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccurrenceController;
<<<<<<< HEAD
use App\Http\Controllers\EmpreendedorController;
=======
use App\Http\Controllers\PrefeituraDashboardController;
>>>>>>> acc9de7 (agrupando dashboard a tela de gestão da prefeitura)

// rota da pagina principal do usuario/turista
Route::get('/', function () {
    return view('HomeUsuario');
});

// Rota para abrir a página de login da prefeitura
Route::get('/login-prefeitura', function(){
    return view('prefeitura.login');
<<<<<<< HEAD

// PÁGINA PRINCIPAL DA PREFEITURA

=======

});

// Rota para home prefeitura
>>>>>>> acc9de7 (agrupando dashboard a tela de gestão da prefeitura)
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

<<<<<<< HEAD
// Rota do Painel VIP (Sem middleware para o Hackathon)
Route::get('/empreendedor/controle', [EmpreendedorController::class, 'painel']);

// Abrir o chat, caso você queira acessar /chat diretamente
Route::get('/chat', [ChatbotController::class, 'index']);
=======
// rota da pagina principal da prefeitura (agora com dados do dashboard embutidos)
Route::get(
    '/prefeitura',
    [PrefeituraDashboardController::class, 'index']
)->name('prefeitura.home');

// rota da pagina principal do empreendedor
Route::get('/empreededor', function () {
    return view('empreendedor.homeEmpreendedor');
});

Route::get('/chat', [ChatbotController::class, 'index']);

>>>>>>> acc9de7 (agrupando dashboard a tela de gestão da prefeitura)
Route::post('/chat', [ChatbotController::class, 'responder']);

Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);

<<<<<<< HEAD
Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);


// EMPREENDEDOR LOGADO

=======
>>>>>>> acc9de7 (agrupando dashboard a tela de gestão da prefeitura)
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
<<<<<<< HEAD
)->names('admin.occurrences');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::resource('/admin/occurrences', OccurrenceController::class)->names('admin.occurrences');
=======
)->names('admin.occurrences');
>>>>>>> acc9de7 (agrupando dashboard a tela de gestão da prefeitura)
