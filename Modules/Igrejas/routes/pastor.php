<?php

use Illuminate\Support\Facades\Route;
use Modules\Igrejas\App\Http\Controllers\Pastor\ChurchDirectoryController;

Route::prefix('igrejas')->name('igrejas.')->group(function () {
    Route::get('/', [ChurchDirectoryController::class, 'index'])->name('index');
    Route::get('/{church}', [ChurchDirectoryController::class, 'show'])->name('show');
});
