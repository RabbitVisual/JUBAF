<?php

use Illuminate\Support\Facades\Route;
use Modules\Igrejas\App\Http\Controllers\PainelLider\ChurchChangeRequestController;

Route::prefix('pedidos')->name('requests.')->group(function () {
    Route::get('/', [ChurchChangeRequestController::class, 'index'])->name('index');
    Route::get('/create', [ChurchChangeRequestController::class, 'create'])->name('create');
    Route::post('/', [ChurchChangeRequestController::class, 'store'])->name('store');
    Route::get('/{churchChangeRequest}', [ChurchChangeRequestController::class, 'show'])->name('show');
    Route::get('/{churchChangeRequest}/edit', [ChurchChangeRequestController::class, 'edit'])->name('edit');
    Route::put('/{churchChangeRequest}', [ChurchChangeRequestController::class, 'update'])->name('update');
    Route::post('/{churchChangeRequest}/submeter', [ChurchChangeRequestController::class, 'submit'])->name('submit');
});
