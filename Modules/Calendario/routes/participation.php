<?php

use Illuminate\Support\Facades\Route;
use Modules\Calendario\App\Http\Controllers\ParticipationController;

Route::get('/', [ParticipationController::class, 'index'])->name('index');
Route::get('/eventos/{event}', [ParticipationController::class, 'show'])->name('show');
Route::post('/eventos/{event}/inscrever', [ParticipationController::class, 'register'])->name('register');
Route::post('/eventos/{event}/cancelar', [ParticipationController::class, 'cancel'])->name('cancel');
