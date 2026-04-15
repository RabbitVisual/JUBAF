<?php

namespace Modules\PainelDiretoria\App\Http\Middleware;

use App\Support\JubafRoleRegistry;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsDiretoria
{
    /**
     * Painel da diretoria: roles em jubaf_roles.directorate + legado co-admin.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $allowed = array_merge(
            JubafRoleRegistry::directorateRoleNames(),
            array_filter([JubafRoleRegistry::legacyCoAdminName()])
        );

        if (! $user->hasAnyRole($allowed)) {
            abort(403, 'User does not have the right roles.');
        }

        return $next($request);
    }
}
