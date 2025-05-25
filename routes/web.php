<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CadastroController;

Route::get('/', function () {
    return view('welcome');
});

// Rota para exibir o formulário de cadastro
Route::get('/cadastro', [CadastroController::class, 'showForm'])->name('cadastro.show');

// Rota para processar o formulário de cadastro (POST)
Route::post('/cadastro', [CadastroController::class, 'processForm'])->name('cadastro.process');