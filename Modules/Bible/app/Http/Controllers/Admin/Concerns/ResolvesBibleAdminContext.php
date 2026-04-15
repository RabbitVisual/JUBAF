<?php

namespace Modules\Bible\App\Http\Controllers\Admin\Concerns;

trait ResolvesBibleAdminContext
{
    protected function bibleRoute(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        $prefix = request()->attributes->get('bible_admin_route_prefix', 'admin.bible');

        return route($prefix.'.'.$name, $parameters, $absolute);
    }

    protected function bibleView(string $relativePath): string
    {
        $root = request()->attributes->get('bible_admin_view_root', 'admin');

        return 'bible::'.$root.'.'.$relativePath;
    }
}
