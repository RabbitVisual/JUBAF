<?php

use Illuminate\Support\Facades\Route;
use Modules\LiderancaPanel\Http\Controllers\LiderancaPanelController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('liderancapanels', LiderancaPanelController::class)->names('liderancapanel');
});
