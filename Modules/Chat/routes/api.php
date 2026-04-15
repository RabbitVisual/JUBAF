<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\App\Http\Controllers\Api\ChatApiController;

/*
 * Rotas API do Chat (autenticadas)
 * Middleware auth:sanctum aceita tanto tokens quanto sessão web stateful
 * IMPORTANTE: Usar nome 'api.chat.' para evitar conflito com rotas públicas 'chat.'
 */
Route::prefix('chat')->name('api.chat.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/sessions', [ChatApiController::class, 'getActiveSessions'])->name('sessions.active');
    Route::get('/session/{sessionId}/messages', [ChatApiController::class, 'getMessages'])->name('session.messages');
    Route::post('/session/{sessionId}/message', [ChatApiController::class, 'sendMessage'])->name('session.message');
    Route::post('/session/{sessionId}/typing', [ChatApiController::class, 'sendTypingIndicator'])->name('session.typing');
    Route::post('/session/{sessionId}/read', [ChatApiController::class, 'markAsRead'])->name('session.read');
    Route::put('/session/{sessionId}/status', [ChatApiController::class, 'updateStatus'])->name('session.status');
});
