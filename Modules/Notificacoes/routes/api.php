<?php

use Illuminate\Support\Facades\Route;
use Modules\Notificacoes\App\Http\Controllers\Api\NotificacoesApiController;

Route::prefix('notificacoes')->name('notificacoes.')->group(function () {
    Route::get('/unread', [NotificacoesApiController::class, 'unread'])->name('unread');
    Route::get('/count', [NotificacoesApiController::class, 'count'])->name('count');
    Route::get('/', [NotificacoesApiController::class, 'index'])->name('index');
    Route::post('/{id}/read', [NotificacoesApiController::class, 'markAsRead'])->name('read');
    Route::post('/read-all', [NotificacoesApiController::class, 'markAllAsRead'])->name('read-all');
});
