<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutorController;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\LoteController;


// Rota para exibir o formulário de cadastro
Route::get('/cadastro', [CadastroController::class, 'showForm'])->name('cadastro.show');

// Rota para processar o formulário de cadastro (POST)
Route::post('/cadastro', [CadastroController::class, 'processForm'])->name('cadastro.process');


Route::prefix('produtores')->group(function() {
    Route::get('/', [ProdutorController::class, 'index'])->name('produtores.index');
    Route::get('/{produtor}/edit', [ProdutorController::class, 'edit'])->name('produtores.edit');
    Route::post('/', [ProdutorController::class, 'store'])->name('produtores.store');
    Route::put('/{id}', [ProdutorController::class, 'update'])->name('produtores.update');
    Route::delete('/{id}', [ProdutorController::class, 'destroy'])->name('produtores.destroy');
});

Route::prefix('eventos')->group(function() {
    Route::get('/', [EventoController::class, 'index'])->name('eventos.index');
    Route::get('/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::post('/', [EventoController::class, 'store'])->name('eventos.store');
    Route::put('/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');
});

Route::prefix('setores')->group(function() {
    Route::get('/', [SetorController::class, 'index'])->name('setores.index');
    Route::post('/', [SetorController::class, 'store'])->name('setores.store');
    Route::get('/{setor}/edit', [SetorController::class, 'edit'])->name('setores.edit');
    Route::put('/{setor}', [SetorController::class, 'update'])->name('setores.update');
    Route::delete('/{setor}', [SetorController::class, 'destroy'])->name('setores.destroy');
});

Route::prefix('lotes')->group(function() {
    Route::get('/', [LoteController::class, 'index'])->name('lotes.index');
    Route::post('/', [LoteController::class, 'store'])->name('lotes.store');
    Route::get('/{lote}/edit', [LoteController::class, 'edit'])->name('lotes.edit');
    Route::put('/{lote}', [LoteController::class, 'update'])->name('lotes.update');
    Route::delete('/{lote}', [LoteController::class, 'destroy'])->name('lotes.destroy');
});