<?php

use Illuminate\Support\Facades\Route;
use Modules\Gateway\App\Http\Controllers\Public\CheckoutController;
use Modules\Gateway\App\Http\Controllers\Webhooks\GatewayWebhookController;

Route::middleware('web')->group(function () {
    Route::prefix('pagamento')->name('gateway.public.')->group(function () {
        Route::get('{uuid}', [CheckoutController::class, 'show'])->name('checkout');
        Route::get('{uuid}/retorno', [CheckoutController::class, 'returnPage'])->name('return');
    });

    Route::post('gateway/webhooks/{driver}/{account?}', [GatewayWebhookController::class, 'handle'])
        ->middleware('throttle:120,1')
        ->name('gateway.webhooks.handle');
});
