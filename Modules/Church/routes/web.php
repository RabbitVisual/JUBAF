<?php

use Illuminate\Support\Facades\Route;
use Modules\Church\Http\Controllers\ChurchController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('churches', ChurchController::class)->names('church');
});
