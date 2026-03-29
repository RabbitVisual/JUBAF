<?php

namespace Modules\Treasury\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Treasury\App\Models\TreasuryPermission;
use Symfony\Component\HttpFoundation\Response;

class CheckTreasuryPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth()->user();

        if (! $user) {
            abort(403, 'Acesso negado. Faça login para continuar.');
        }

        $treasuryPermission = TreasuryPermission::forUserOrAdmin($user);

        // Verifica permissão específica
        if ($permission === 'is_admin') {
            if (! $treasuryPermission->isAdmin()) {
                abort(403, 'Apenas administradores podem realizar esta ação.');
            }
        } else {
            $method = 'can'.str_replace(' ', '', ucwords(str_replace('_', ' ', $permission)));

            if (method_exists($treasuryPermission, $method) && ! $treasuryPermission->$method()) {
                abort(403, 'Você não tem permissão para realizar esta ação.');
            }
        }

        return $next($request);
    }
}
