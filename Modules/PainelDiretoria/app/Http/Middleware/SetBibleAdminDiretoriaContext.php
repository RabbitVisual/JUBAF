<?php

namespace Modules\PainelDiretoria\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Define o prefixo de rotas e pasta de views da Bíblia (admin) para o painel /diretoria.
 */
class SetBibleAdminDiretoriaContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('bible_admin_route_prefix', 'diretoria.bible');
        $request->attributes->set('bible_admin_view_root', 'paineldiretoria');

        return $next($request);
    }
}
