<?php

use Illuminate\Support\Facades\Route;
use Modules\Igrejas\App\Http\Controllers\DiretoriaChurchChangeRequestController;
use Modules\Igrejas\App\Http\Controllers\DiretoriaChurchController;
use Modules\Igrejas\App\Http\Controllers\DiretoriaIgrejasDashboardController;

Route::prefix('igrejas')->name('igrejas.')->group(function () {
    Route::get('/', [DiretoriaIgrejasDashboardController::class, 'index'])->name('dashboard');
    Route::get('/export/csv', [DiretoriaChurchController::class, 'exportCsv'])->name('export.csv');
    Route::get('/pedidos', [DiretoriaChurchChangeRequestController::class, 'index'])->name('requests.index');
    Route::get('/pedidos/{churchChangeRequest}', [DiretoriaChurchChangeRequestController::class, 'show'])->name('requests.show');
    Route::post('/pedidos/{churchChangeRequest}/aprovar', [DiretoriaChurchChangeRequestController::class, 'approve'])->name('requests.approve');
    Route::post('/pedidos/{churchChangeRequest}/recusar', [DiretoriaChurchChangeRequestController::class, 'reject'])->name('requests.reject');
    Route::get('/congregacoes', [DiretoriaChurchController::class, 'index'])->name('index');
    Route::get('/create', [DiretoriaChurchController::class, 'create'])->name('create');
    Route::post('/', [DiretoriaChurchController::class, 'store'])->name('store');
    Route::get('/{church}/membros/export/csv', [DiretoriaChurchController::class, 'exportMembersCsv'])->name('members.export.csv');
    Route::get('/{church}', [DiretoriaChurchController::class, 'show'])->name('show');
    Route::get('/{church}/edit', [DiretoriaChurchController::class, 'edit'])->name('edit');
    Route::match(['put', 'patch'], '/{church}', [DiretoriaChurchController::class, 'update'])->name('update');
    Route::delete('/{church}', [DiretoriaChurchController::class, 'destroy'])->name('destroy');
});
