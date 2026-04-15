<?php

use Illuminate\Support\Facades\Route;
use Modules\Talentos\App\Http\Controllers\TalentosController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('talentos', TalentosController::class)->names('talentos');
});
