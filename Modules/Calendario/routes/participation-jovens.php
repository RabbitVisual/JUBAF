<?php

use Illuminate\Support\Facades\Route;
use Modules\Calendario\App\Http\Controllers\ParticipationController;

/** URLs canónicas /jovens/eventos/... (sem segmento duplicado "eventos") */
Route::get('/', [ParticipationController::class, 'index'])->name('index');
Route::get('/{event}', [ParticipationController::class, 'show'])->name('show');
Route::post('/{event}/inscrever', [ParticipationController::class, 'register'])->name('register');
Route::post('/{event}/cancelar', [ParticipationController::class, 'cancel'])->name('cancel');
