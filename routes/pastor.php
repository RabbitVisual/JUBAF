<?php

use Illuminate\Support\Facades\Route;
use Modules\Avisos\App\Http\Controllers\AvisosPainelController;

/*
|--------------------------------------------------------------------------
| Painel Pastor (JUBAF) — supervisão local; UI mínima até modelo igreja/pastor.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pastor'])->prefix('pastor')->name('pastor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pastor.dashboard');
    })->name('dashboard');

    if (module_enabled('Igrejas')) {
        Route::middleware(['permission:igrejas.view'])->group(function () {
            require module_path('Igrejas', 'routes/pastor.php');
        });
    }

    if (module_enabled('Avisos')) {
        Route::prefix('avisos')->name('avisos.')->group(function () {
            Route::get('/', [AvisosPainelController::class, 'index'])->name('index');
            Route::get('/{aviso}', [AvisosPainelController::class, 'show'])->name('show');
        });
    }
});
