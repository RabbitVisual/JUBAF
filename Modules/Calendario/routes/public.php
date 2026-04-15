<?php

use Illuminate\Support\Facades\Route;
use Modules\Calendario\App\Http\Controllers\PublicCalendarController;

Route::get('/eventos', [PublicCalendarController::class, 'index'])->name('eventos.index');
Route::get('/eventos/{slug}', [PublicCalendarController::class, 'show'])->name('eventos.show');
