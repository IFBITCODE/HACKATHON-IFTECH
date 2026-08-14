<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccurrenceController;
use App\Http\Controllers\EmpreendedorController;

// ROTAS USUÁRIO / TURISTA

// Rota raiz (redireciona para tela principal do usuário)
Route::get('/', function () {
    return view('usuario.homeUsuario');
});

Route::get('/rotaguiada', function () {
    return view('usuario.homeUsuario');
});


// ROTAS PREFEITURA

// 1Rota da tela de Login da Prefeitura (Definida como login oficial)
Route::get('/login-prefeitura', function () {
    return view('prefeitura.login');
})->name('login');

// 2. Rota do Painel da Prefeitura
Route::get('/prefeitura', function () {
    return view('prefeitura.homePrefeitura');
});


// ROTAS DO EMPREENDEDOR

// rota para pagina inicial de login do empreendedor
Route::get('/login-empreendedor', function () {
    return view('empreendedor.homeEmpreendedor');
});

// Painel do Empreendedor
Route::get('/empreendedor/controle', [EmpreendedorController::class, 'painel']);

// EMPREENDEDOR LOGADO
Route::get('/logado-empreendedor', function () {
    $empreendedor = (object) [
        'nome_fantasia' => 'Restaurante Sabor Paraibano',
        'email'         => 'contato@saborparaibano.com',
        'status'        => 'aprovado', // <- ADICIONADO AQUI ('aprovado' ou 'pendente')
    ];

    return view('empreendedor.controleEmpreendedor', compact('empreendedor'));
});


// CHATBOT E DIAGNÓSTICO

Route::get('/chat', [ChatbotController::class, 'index']);
Route::post('/chat', [ChatbotController::class, 'responder']);
Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);


// ADMIN / OCORRÊNCIAS

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::resource('/admin/occurrences', OccurrenceController::class)->names('admin.occurrences');