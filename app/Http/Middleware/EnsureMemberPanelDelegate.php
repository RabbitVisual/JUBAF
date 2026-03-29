<?php

namespace App\Http\Middleware;

use App\Services\MemberPanelAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMemberPanelDelegate
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! MemberPanelAccess::canDelegateGrants($user)) {
            abort(403, 'Apenas a diretoria autorizada pode gerir estes acessos.');
        }

        return $next($request);
    }
}
