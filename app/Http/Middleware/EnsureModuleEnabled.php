<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $moduleName): Response
    {
        try {
            $module = Module::find($moduleName);
            
            if (!$module || !$module->isEnabled()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => "O módulo {$moduleName} está desabilitado.",
                        'module' => $moduleName,
                    ], 503);
                }

                abort(503, "O módulo {$moduleName} está desabilitado.");
            }
        } catch (\Exception $e) {
            \Log::warning("Erro ao verificar status do módulo {$moduleName}: " . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => "Erro ao verificar status do módulo {$moduleName}.",
                    'module' => $moduleName,
                ], 503);
            }

            abort(503, "Erro ao verificar status do módulo {$moduleName}.");
        }

        return $next($request);
    }
}

