<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccurrenceController;

Route::get('/', function () {
    return view('HomeUsuario');
});

// Rota para abrir a página de login da prefeitura
Route::get('/login-prefeitura', function(){
    return view('prefeitura.login');

});

// Rota para home prefeitura
Route::get('/home-prefeitura', function () {
    return view('prefeitura.homePrefeitura');
});

//rota da pagina principal do empreendedor
Route::get('/empreendedor', function () {
    return view('empreendedor.homeEmpreendedor');
});



Route::get('/chat', function () {
    return view('chat');
});

// Rota para abrir a página do chat (Método GET)
Route::get('/chat', [ChatbotController::class, 'index']);

// Rota para quando o usuário clicar em "Enviar" a mensagem (Método POST)
Route::post('/chat', [ChatbotController::class, 'responder']);

Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);

Route::get('/diagnostico', [ChatbotController::class, 'diagnostico']);

Route::get('/logadoempreendedor', function () {
    return view('empreendedor.controleEmpreendedor');
});


Route::get(
    '/admin/dashboard',
    [DashboardController::class, 'index']
)->name('admin.dashboard');

Route::resource(
    '/admin/occurrences',
    OccurrenceController::class
)->names('admin.occurrences');