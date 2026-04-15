<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\SystemApiController;

/*
|--------------------------------------------------------------------------
| API Routes (JUBAF — núcleo)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::get('/docs', function () {
        return redirect()->route('api.documentation');
    })->name('api.docs');

    Route::get('/info', function () {
        return response()->json([
            'name' => 'JUBAF API',
            'version' => '1.0.0',
            'status' => 'development',
            'documentation' => route('api.documentation'),
        ]);
    });

    Route::get('/health', [SystemApiController::class, 'health'])->name('api.health');
    Route::post('/log-error', [SystemApiController::class, 'logError'])->name('api.log-error');

    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthApiController::class, 'login'])
            ->middleware('throttle:login')
            ->name('api.auth.login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthApiController::class, 'logout'])->name('api.auth.logout');
            Route::get('/me', [AuthApiController::class, 'me'])->name('api.auth.me');
            Route::post('/revoke-all', [AuthApiController::class, 'revokeAllTokens'])->name('api.auth.revoke-all');
        });
    });

    if (module_enabled('Chat')) {
        $chatApiRoutesPath = module_path('Chat', '/routes/api.php');
        if ($chatApiRoutesPath && file_exists($chatApiRoutesPath)) {
            require $chatApiRoutesPath;
        }
    }

    if (module_enabled('Igrejas')) {
        require module_path('Igrejas', 'routes/api.php');
    }

    if (module_enabled('Secretaria')) {
        require module_path('Secretaria', 'routes/api.php');
    }
});
