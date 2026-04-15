<?php

use Illuminate\Support\Facades\Route;
use Modules\Igrejas\App\Http\Controllers\AdminChurchController;

Route::get('/igrejas/export/csv', [AdminChurchController::class, 'exportCsv'])->name('igrejas.export.csv');
Route::get('/igrejas/{church}/membros/export/csv', [AdminChurchController::class, 'exportMembersCsv'])->name('igrejas.members.export.csv');
Route::resource('igrejas', AdminChurchController::class)
    ->parameters(['igrejas' => 'church']);
