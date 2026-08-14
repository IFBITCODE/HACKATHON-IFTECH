<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccurrenceController;


// PÁGINA PRINCIPAL DO USUÁRIO / TURISTA

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


// PÁGINA PRINCIPAL DO EMPREENDEDOR

Route::get('/empreendedor', function () {
    return view('empreendedor.homeEmpreendedor');
});


// CHATBOT

// Abrir o chat, caso você queira acessar /chat diretamente
Route::get('/chat', [ChatbotController::class, 'index']);

// Enviar mensagem para o chatbot
Route::post('/chat', [ChatbotController::class, 'responder']);


// DIAGNÓSTICO

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