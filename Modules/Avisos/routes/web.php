<?php

use Illuminate\Support\Facades\Route;
use Modules\Avisos\App\Http\Controllers\AvisosController;
use Modules\Avisos\App\Http\Controllers\AvisosPublicController;

/*
|--------------------------------------------------------------------------
| Web Routes - Módulo Avisos
|--------------------------------------------------------------------------
*/

// Rotas públicas (API para componentes e atualização leve)
Route::prefix('api/avisos')->name('avisos.api.')->group(function () {
    Route::get('/feed-meta', [AvisosPublicController::class, 'feedMeta'])->name('feed-meta');
    Route::get('/posicao/{posicao}', [AvisosPublicController::class, 'obterPorPosicao'])->name('posicao');
    Route::post('/{id}/visualizar', [AvisosPublicController::class, 'registrarVisualizacao'])->name('visualizar');
    Route::post('/{id}/clique', [AvisosPublicController::class, 'registrarClique'])->name('clique');
});

// Página pública de avisos (CRUD admin em routes/admin.php)
Route::get('/avisos', [AvisosController::class, 'index'])->name('avisos.index');
Route::get('/avisos/{aviso}', [AvisosController::class, 'show'])->name('avisos.show');
