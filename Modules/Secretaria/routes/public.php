<?php

use Illuminate\Support\Facades\Route;
use Modules\Secretaria\App\Http\Controllers\PublicSecretariaController;

Route::get('/secretaria/publicacoes', [PublicSecretariaController::class, 'index'])->name('secretaria.public.index');
