<?php

use Illuminate\Support\Facades\Route;
use Modules\Calendario\App\Http\Controllers\Diretoria\CalendarDashboardController;
use Modules\Calendario\App\Http\Controllers\Diretoria\CalendarFeedWebController;
use Modules\Calendario\App\Http\Controllers\Diretoria\EventExportController;
use Modules\Calendario\App\Http\Controllers\Diretoria\EventController;
use Modules\Calendario\App\Http\Controllers\Diretoria\RegistrationsController;

Route::prefix('calendario')->name('calendario.')->group(function () {
    Route::get('/', [CalendarDashboardController::class, 'index'])
        ->middleware('can:calendario.events.view')
        ->name('dashboard');

    Route::get('/feed', CalendarFeedWebController::class)
        ->middleware('can:calendario.events.view')
        ->name('feed');

    Route::get('/inscricoes', [RegistrationsController::class, 'index'])
        ->middleware('can:calendario.registrations.view')
        ->name('registrations.index');

    Route::resource('events', EventController::class)
        ->parameters(['events' => 'event'])
        ->except(['show']);

    Route::get('events/{event}/monitor', [EventController::class, 'monitor'])
        ->middleware('can:manageRegistrations,event')
        ->name('events.monitor');

    Route::post('events/{event}/registrations/{registration}/check-in', [EventController::class, 'checkIn'])
        ->name('events.registrations.check-in');

    Route::get('events/{event}/registrations/export.csv', [EventExportController::class, 'csvRegistrations'])
        ->middleware('can:manageRegistrations,event')
        ->name('events.registrations.export');

    Route::get('events/{event}/registrations/badges.pdf', [EventExportController::class, 'pdfBadges'])
        ->middleware('can:manageRegistrations,event')
        ->name('events.registrations.badges');
});
