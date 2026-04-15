<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\App\Http\Controllers\ChatPublicController;

/*
 * Rotas públicas do Chat
 */
Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/status', [ChatPublicController::class, 'status'])->name('status');
    Route::post('/start', [ChatPublicController::class, 'start'])->name('start');
    Route::get('/session/{sessionId}', [ChatPublicController::class, 'getSession'])->name('session.get');
    Route::post('/session/{sessionId}/message', [ChatPublicController::class, 'sendMessage'])->name('session.message');
    Route::post('/session/{sessionId}/typing', [ChatPublicController::class, 'sendTypingIndicator'])->name('session.typing');
    Route::put('/session/{sessionId}/visitor-info', [ChatPublicController::class, 'updateVisitorInfo'])->name('session.visitor-info');
});
