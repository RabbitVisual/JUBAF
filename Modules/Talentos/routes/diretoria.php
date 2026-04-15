<?php

use Illuminate\Support\Facades\Route;
use Modules\Talentos\App\Http\Controllers\Diretoria\AssignmentController;
use Modules\Talentos\App\Http\Controllers\Diretoria\DirectoryController;
use Modules\Talentos\App\Http\Controllers\Diretoria\TalentAreaController;
use Modules\Talentos\App\Http\Controllers\Diretoria\TalentDashboardController;
use Modules\Talentos\App\Http\Controllers\Diretoria\TalentSkillController;

Route::prefix('talentos')->name('talentos.')->group(function () {
    Route::get('/', [TalentDashboardController::class, 'index'])->name('dashboard');

    Route::middleware('can:talentos.directory.view')->group(function () {
        Route::get('diretorio', [DirectoryController::class, 'index'])->name('directory.index');
        Route::get('diretorio/exportar.csv', [DirectoryController::class, 'exportCsv'])
            ->middleware('can:talentos.directory.export')
            ->name('directory.export.csv');
        Route::get('diretorio/{user}', [DirectoryController::class, 'show'])->name('directory.show');
    });

    Route::resource('assignments', AssignmentController::class)
        ->parameters(['assignments' => 'assignment'])
        ->except(['show']);

    Route::middleware('can:talentos.taxonomy.manage')->group(function () {
        Route::resource('competencias', TalentSkillController::class)
            ->parameters(['competencias' => 'skill'])
            ->except(['show']);
        Route::resource('areas-servico', TalentAreaController::class)
            ->parameters(['areas-servico' => 'area'])
            ->except(['show']);
    });
});
