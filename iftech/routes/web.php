<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OccurrenceController;


//rota da pagina principal do usuario/turista 
Route::get('/', function () {
    return view('usuario.HomeUsuario');
});


// rota da pagina principal da prefeitura
Route::get('/prefeitura', function () {
    return view('prefeitura.homePrefeitura');
});

//rota da pagina principal do empreendedor
Route::get('/empreededor', function () {
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

Route::get(
    '/admin/dashboard',
    [DashboardController::class, 'index']
)->name('admin.dashboard');

Route::resource(
    '/admin/occurrences',
    OccurrenceController::class
)->names('admin.occurrences');
