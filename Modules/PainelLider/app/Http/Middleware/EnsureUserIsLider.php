<?php

namespace Modules\PainelLider\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsLider
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (! $user->hasRole('lider')) {
            abort(403, 'Acesso negado. Este painel é exclusivo para líderes de igrejas locais da JUBAF.');
        }

        return $next($request);
    }
}
