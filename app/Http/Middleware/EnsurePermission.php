<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    /**
     * Uma ou mais permissões (slug), separadas por vírgula — basta uma corresponder (OR).
     */
    public function handle(Request $request, Closure $next, string $permissions = ''): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(403, 'Acesso não autorizado.');
        }

        if (! $user->hasAdminAccess()) {
            abort(403, 'Acesso restrito.');
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        $slugs = array_filter(array_map('trim', explode(',', $permissions)));
        if ($slugs === []) {
            return $next($request);
        }

        foreach ($slugs as $slug) {
            if ($user->canAccess($slug)) {
                return $next($request);
            }
        }

        abort(403, 'Sem permissão para esta área.');
    }
}
