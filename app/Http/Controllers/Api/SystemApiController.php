<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class SystemApiController extends BaseApiController
{
    /**
     * Endpoint de verificação de saúde do sistema
     * Retorna o status das conexões vitais
     */
    public function health(): JsonResponse
    {
        $services = [
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
        ];

        // Opcional: Verificar Redis se estiver em uso
        if (config('database.redis.client') !== null && config('cache.default') === 'redis') {
            $services['redis'] = $this->checkRedis();
        }

        $allOk = !in_array('error', array_column($services, 'status'));

        return response()->json([
            'status' => $allOk ? 'ok' : 'partial_failure',
            'timestamp' => now()->toIso8601String(),
            'environment' => app()->environment(),
            'services' => $services
        ], $allOk ? 200 : 503);
    }

    /**
     * Endpoint para recebimento de logs de erro do frontend (PWA/Mobile)
     */
    public function logError(Request $request): JsonResponse
    {
        $this->validateRequest($request, [
            'message' => 'required|string',
            'stack' => 'nullable|string',
            'context' => 'nullable|array',
            'url' => 'nullable|string',
            'user_agent' => 'nullable|string'
        ]);

        Log::error('[PWA-CLIENT-ERROR] ' . $request->message, [
            'stack' => $request->stack,
            'url' => $request->url,
            'user' => auth('sanctum')->id() ?? 'guest',
            'user_agent' => $request->user_agent ?? $request->header('User-Agent'),
            'context' => $request->context,
            'client_ip' => $request->ip()
        ]);

        return $this->success(null, 'Log registrado com sucesso');
    }

    /**
     * Verifica conexão com Banco de Dados
     */
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'ok'];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Falha na conexão com o banco de dados'
            ];
        }
    }

    /**
     * Verifica acesso ao Storage
     */
    private function checkStorage(): array
    {
        try {
            $filename = 'health-check.txt';
            Storage::disk('public')->put($filename, 'health check ' . now());
            Storage::disk('public')->delete($filename);
            return ['status' => 'ok'];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Falha de escrita/leitura no storage'
            ];
        }
    }

    /**
     * Verifica conexão com Redis
     */
    private function checkRedis(): array
    {
        try {
            Redis::connection()->ping();
            return ['status' => 'ok'];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Falha na conexão com o Redis'
            ];
        }
    }
}
