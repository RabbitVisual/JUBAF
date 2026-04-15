<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Funcionarios\App\Models\Funcionario;

class SecureImpersonation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // 1. Check for Super Admin role
        if (!$user || !$user->hasRole('super-admin')) {
            Log::warning('[Security] Unauthorized impersonation attempt.', [
                'user_id' => $user->id ?? 'guest',
                'target_id' => $request->route('id')
            ]);
            abort(403, 'Apenas Super Administradores podem realizar personificação.');
        }

        // 2. Prevent Privilege Escalation (Impersonating another Admin or higher)
        $targetId = $request->route('id');
        $targetFuncionario = Funcionario::find($targetId);
        $targetUser = $targetFuncionario ? $targetFuncionario->user() : null;

        if ($targetUser && (\user_can_access_admin_panel($targetUser) || \user_can_access_diretoria_panel($targetUser))) {
            Log::emergency('[Security] Privilege escalation attempt detected.', [
                'user_id' => $user->id,
                'target_id' => $targetUser->id
            ]);
            abort(403, 'Proibida personificação de outros administradores ou superiores.');
        }

        return $next($request);
    }
}
