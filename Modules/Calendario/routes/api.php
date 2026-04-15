<?php

use Illuminate\Support\Facades\Route;
use Modules\Calendario\App\Http\Controllers\Api\CalendarFeedController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('calendar/feed', CalendarFeedController::class)->name('calendar.feed');
});
