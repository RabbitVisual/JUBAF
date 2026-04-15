<?php

namespace Modules\PainelDiretoria\App\Http\Middleware;

use App\Support\JubafRoleRegistry;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsDiretoriaExecutive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! method_exists($user, 'hasAnyRole')) {
            abort(403);
        }

        if (! $user->hasAnyRole(JubafRoleRegistry::directorateExecutiveRoleNames())) {
            abort(403, 'Apenas Presidente e Vice-Presidentes podem aceder a esta área.');
        }

        return $next($request);
    }
}
