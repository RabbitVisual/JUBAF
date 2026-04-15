<?php

use Illuminate\Support\Facades\Route;
use Modules\Notificacoes\App\Http\Controllers\NotificacoesController;
use Modules\Notificacoes\App\Http\Controllers\Api\NotificacoesApiController;

// Rotas que exigem autenticação
Route::middleware(['web', 'auth', 'module.enabled:Notificacoes'])->group(function () {
    // Rotas do usuário (página completa)
    Route::prefix('notificacoes')->name('notificacoes.')->group(function () {
        Route::get('/', [NotificacoesController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificacoesController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificacoesController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificacoesController::class, 'destroy'])->name('destroy');
    });

    // Demonstração local: cria notificações de exemplo (desativado em produção)
    Route::get('/notificacoes/demo/create', function () {
        if (! app()->environment(['local', 'testing'])) {
            abort(404);
        }
        $service = app(\Modules\Notificacoes\App\Services\NotificacaoService::class);
        $user = auth()->user();

        // Criar várias notificações de exemplo
        $notifications = [];

        // Notificação de sucesso
        $notifications[] = $service->sendToUser(
            $user->id,
            'success',
            '✅ Notificação de Sucesso',
            'Esta é uma notificação de sucesso! O sistema está funcionando perfeitamente.',
            route('notificacoes.index'),
            ['demo' => true, 'type' => 'success'],
            'Notificacoes',
            'Demo',
            1
        );

        // Notificação de informação
        $notifications[] = $service->sendToUser(
            $user->id,
            'info',
            'ℹ️ Notificação Informativa',
            'Esta é uma notificação informativa. Use este tipo para compartilhar informações importantes.',
            (function () {
                $name = get_dashboard_route();

                return \Illuminate\Support\Facades\Route::has($name) ? route($name) : route('login');
            })(),
            ['demo' => true, 'type' => 'info'],
            'Notificacoes',
            'Demo',
            2
        );

        // Notificação de aviso
        $notifications[] = $service->sendToUser(
            $user->id,
            'warning',
            '⚠️ Notificação de Aviso',
            'Esta é uma notificação de aviso. Use para alertar sobre situações que precisam de atenção.',
            (function () {
                $name = get_profile_route();

                return \Illuminate\Support\Facades\Route::has($name) ? route($name) : route('login');
            })(),
            ['demo' => true, 'type' => 'warning'],
            'Notificacoes',
            'Demo',
            3
        );

        // Notificação de erro
        $notifications[] = $service->sendToUser(
            $user->id,
            'error',
            '❌ Notificação de Erro',
            'Esta é uma notificação de erro. Use para informar sobre problemas que ocorreram.',
            null,
            ['demo' => true, 'type' => 'error'],
            'Notificacoes',
            'Demo',
            4
        );

        // Notificação de sistema
        $notifications[] = $service->sendToUser(
            $user->id,
            'system',
            '⚙️ Notificação do Sistema',
            'Esta é uma notificação do sistema. Use para comunicar atualizações e manutenções.',
            route('admin.dashboard'),
            ['demo' => true, 'type' => 'system'],
            'Notificacoes',
            'Demo',
            5
        );

        // Notificação de alerta
        $notifications[] = $service->sendToUser(
            $user->id,
            'alert',
            '🔔 Notificação de Alerta',
            'Esta é uma notificação de alerta. Use para chamar atenção para eventos importantes.',
            route('notificacoes.index'),
            ['demo' => true, 'type' => 'alert'],
            'Notificacoes',
            'Demo',
            6
        );

        return redirect()->route('notificacoes.index')
            ->with('success', count($notifications) . ' notificações de demonstração criadas com sucesso!');
    })->name('notificacoes.demo.create');
});

// Rotas API acessíveis (com tratamento interno para guests no controlador)
Route::middleware(['web', 'module.enabled:Notificacoes'])->group(function () {
    Route::prefix('api/notificacoes')->name('api.notificacoes.')->group(function () {
        Route::get('/unread', [NotificacoesApiController::class, 'unread'])->name('unread');
        Route::get('/count', [NotificacoesApiController::class, 'count'])->name('count');
        Route::get('/', [NotificacoesApiController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificacoesApiController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificacoesApiController::class, 'markAllAsRead'])->name('read-all');
    });
});
