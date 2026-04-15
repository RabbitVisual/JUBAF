<?php

use Illuminate\Support\Facades\Route;
use Modules\PainelJovens\App\Http\Controllers\PainelJovensController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('paineljovens', PainelJovensController::class)->names('paineljovens');
});
