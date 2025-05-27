<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutorController;
use App\Http\Controllers\CadastroController;


// Rota para exibir o formulário de cadastro
Route::get('/cadastro', [CadastroController::class, 'showForm'])->name('cadastro.show');

// Rota para processar o formulário de cadastro (POST)
Route::post('/cadastro', [CadastroController::class, 'processForm'])->name('cadastro.process');


Route::prefix('produtores')->group(function() {
    Route::get('/', [ProdutorController::class, 'index'])->name('produtores.index');
    Route::get('/edit/{id}', [ProdutorController::class, 'edit'])->name('produtores.edit');
    Route::post('/', [ProdutorController::class, 'store'])->name('produtores.store');
    Route::put('/{id}', [ProdutorController::class, 'update'])->name('produtores.update');
    Route::delete('/{id}', [ProdutorController::class, 'destroy'])->name('produtores.destroy');
});