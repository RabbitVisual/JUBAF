<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'gateway/webhooks/*',
        ]);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'lider.panel' => \Modules\PainelLider\App\Http\Middleware\EnsureUserIsLider::class,
            'jovens.panel' => \Modules\PainelJovens\App\Http\Middleware\EnsureUserIsJovens::class,
            'module.enabled' => \App\Http\Middleware\EnsureModuleEnabled::class,
            'diretoria.panel' => \Modules\PainelDiretoria\App\Http\Middleware\EnsureUserIsDiretoria::class,
            'diretoria.executive' => \Modules\PainelDiretoria\App\Http\Middleware\EnsureUserIsDiretoriaExecutive::class,
            'co-admin-or-admin' => \Modules\PainelDiretoria\App\Http\Middleware\EnsureUserIsDiretoria::class,
            'diretoria-panel' => \Modules\PainelDiretoria\App\Http\Middleware\EnsureUserIsDiretoria::class,
            'secure-impersonation' => \App\Http\Middleware\SecureImpersonation::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tratar erro de APP_KEY ausente ou Autenticação para rotas API de notificações
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            // Se for uma requisição API de notificações e for erro de chave ou autenticação
            if ($request->is('api/notificacoes/*') || $request->is('*/api/notificacoes/*')) {
                if ($e instanceof \Illuminate\Encryption\MissingAppKeyException ||
                    $e instanceof \Illuminate\Auth\AuthenticationException) {

                    return response()->json([
                        'success' => true,
                        'count' => 0,
                        'data' => [],
                        'message' => 'Silently ignored (Guest or missing key)',
                    ], 200);
                }
            }

            // Para outras rotas, deixar o Laravel tratar normalmente
            return null;
        });
    })->create();
