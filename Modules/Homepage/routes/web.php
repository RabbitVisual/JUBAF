<?php

use Illuminate\Support\Facades\Route;
use Modules\Homepage\App\Http\Controllers\HomepageController;
// Homepage principal
Route::get('/', [HomepageController::class, 'index'])->name('homepage');

// Páginas Legais
Route::get('/privacidade', [HomepageController::class, 'privacidade'])->name('privacidade');
Route::get('/termos', [HomepageController::class, 'termos'])->name('termos');
Route::get('/sobre', [HomepageController::class, 'sobre'])->name('sobre');
Route::get('/contato', [HomepageController::class, 'contato'])->name('contato');
Route::post('/contato', [HomepageController::class, 'contatoStore'])
    ->middleware('throttle:8,1')
    ->name('contato.store');
Route::post('/newsletter/inscrever', [HomepageController::class, 'newsletterSubscribe'])
    ->middleware('throttle:15,1')
    ->name('homepage.newsletter.subscribe');
Route::get('/desenvolvedor', [HomepageController::class, 'desenvolvedor'])->name('desenvolvedor');
/** Página pública da diretoria (URL distinta do painel autenticado em /diretoria). */
Route::get('/equipe/diretoria', [HomepageController::class, 'diretoria'])->name('homepage.diretoria');
Route::get('/radio', [HomepageController::class, 'radio'])->name('radio');
Route::get('/devocionais', [HomepageController::class, 'devotionalsIndex'])->name('devocionais.index');
Route::get('/devocionais/{devotional:slug}', [HomepageController::class, 'devotionalShow'])->name('devocionais.show');
