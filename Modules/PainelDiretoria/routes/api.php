<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PainelDiretoria API — reservado; painel HTTP em routes/diretoria.php
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    //
});
