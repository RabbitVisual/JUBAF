<?php

namespace App\Http\Middleware;

use App\Services\MemberPanelAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMemberPanelModule
{
    public function handle(Request $request, Closure $next, string $moduleKey = ''): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(403, 'Acesso não autorizado.');
        }

        if (! MemberPanelAccess::canUseModule($user, $moduleKey)) {
            abort(403, 'Este módulo não está disponível para o seu perfil.');
        }

        return $next($request);
    }
}
