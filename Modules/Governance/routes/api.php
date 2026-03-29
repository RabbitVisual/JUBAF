<?php

use Illuminate\Support\Facades\Route;
use Modules\Governance\Http\Controllers\GovernanceController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('governances', GovernanceController::class)->names('governance');
});
