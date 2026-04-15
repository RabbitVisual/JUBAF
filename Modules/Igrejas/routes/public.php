<?php

use Illuminate\Support\Facades\Route;
use Modules\Igrejas\App\Http\Controllers\PublicChurchController;

Route::get('/congregacoes', [PublicChurchController::class, 'index'])->name('igrejas.public.index');
