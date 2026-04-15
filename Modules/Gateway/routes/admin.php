<?php

use Illuminate\Support\Facades\Route;
use Modules\Gateway\App\Http\Controllers\Admin\GatewayProviderAccountController;

Route::prefix('gateway')->name('gateway.')->group(function () {
    Route::middleware('can:gateway.accounts.manage')->group(function () {
        Route::resource('accounts', GatewayProviderAccountController::class)->except(['show']);
    });
});
