<?php

use Illuminate\Support\Facades\Route;
use Modules\Financeiro\App\Http\Controllers\PainelLider\MinhasContasController;

Route::get('/minhas-contas', [MinhasContasController::class, 'index'])->name('minhas-contas');
