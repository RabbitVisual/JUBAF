<?php

use Illuminate\Support\Facades\Route;
use Modules\Gateway\App\Http\Controllers\Diretoria\GatewayDashboardController;
use Modules\Gateway\App\Http\Controllers\Diretoria\GatewayPaymentController;

Route::prefix('gateway')->name('gateway.')->group(function () {
    Route::get('/', [GatewayDashboardController::class, 'index'])
        ->middleware('can:gateway.dashboard.view')
        ->name('dashboard');

    Route::middleware('can:gateway.payments.view')->group(function () {
        Route::get('/pagamentos', [GatewayPaymentController::class, 'index'])->name('payments.index');
        Route::get('/pagamentos/export.csv', [GatewayPaymentController::class, 'exportCsv'])->name('payments.export.csv');
        Route::get('/pagamentos/{payment}', [GatewayPaymentController::class, 'show'])->name('payments.show');
    });
});
