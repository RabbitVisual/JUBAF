<?php

use Illuminate\Support\Facades\Route;
use Modules\CoordinationCouncil\Http\Controllers\CoordinationCouncilController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('coordinationcouncils', CoordinationCouncilController::class)->names('coordinationcouncil');
});
