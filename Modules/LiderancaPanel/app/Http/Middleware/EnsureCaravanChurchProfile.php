<?php

namespace Modules\LiderancaPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCaravanChurchProfile
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->church_id) {
            return redirect()
                ->route('memberpanel.profile.edit')
                ->with('warning', 'Associe a sua igreja local no perfil para aceder ao painel de caravana.');
        }

        return $next($request);
    }
}
