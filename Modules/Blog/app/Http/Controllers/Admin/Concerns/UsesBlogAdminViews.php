<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Concerns;

trait UsesBlogAdminViews
{
    protected function blogView(string $name): string
    {
        $root = request()->routeIs('diretoria.*') ? 'blog::paineldiretoria' : 'blog::admin';

        return $root.'.'.$name;
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    protected function blogRoute(string $suffix, array $parameters = []): string
    {
        $prefix = request()->routeIs('diretoria.*') ? 'diretoria.blog.' : 'admin.blog.';

        return route($prefix.$suffix, $parameters);
    }
}
