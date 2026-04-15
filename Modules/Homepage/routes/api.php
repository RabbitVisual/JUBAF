<?php

use Illuminate\Support\Facades\Route;
use Modules\Homepage\App\Http\Controllers\Api\HomepagePublicApiController;

Route::prefix('v1')->group(function () {
    Route::get('/homepage/settings', [HomepagePublicApiController::class, 'settings'])
        ->name('homepage.settings');
});
