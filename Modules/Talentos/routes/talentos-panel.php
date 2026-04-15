<?php

use Illuminate\Support\Facades\Route;
use Modules\Talentos\App\Http\Controllers\TalentAssignmentRespondController;
use Modules\Talentos\App\Http\Controllers\TalentProfilePanelController;

Route::get('/perfil', [TalentProfilePanelController::class, 'edit'])->name('profile.edit');
Route::put('/perfil', [TalentProfilePanelController::class, 'update'])->name('profile.update');

Route::post('/atribuicoes/{assignment}/responder', TalentAssignmentRespondController::class)
    ->name('assignments.respond');
