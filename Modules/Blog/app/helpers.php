<?php

use Illuminate\Support\Facades\Request;

if (! function_exists('blog_admin_route')) {
    /**
     * Rota nomeada do backoffice do blog (admin ou painel da diretoria).
     */
    function blog_admin_route(string $name, mixed $parameters = []): string
    {
        $prefix = Request::routeIs('diretoria.*') ? 'diretoria.blog.' : 'admin.blog.';

        return route($prefix.$name, $parameters);
    }
}
