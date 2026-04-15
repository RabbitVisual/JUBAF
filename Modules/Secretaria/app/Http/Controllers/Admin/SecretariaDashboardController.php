<?php

namespace Modules\Secretaria\App\Http\Controllers\Admin;

use Modules\Secretaria\App\Http\Controllers\Diretoria\SecretariaDashboardController as Base;

class SecretariaDashboardController extends Base
{
    protected function routePrefix(): string
    {
        return 'admin.secretaria';
    }

    protected function panelLayout(): string
    {
        return 'admin::layouts.admin';
    }

    protected function secretariaViewsDirectory(): string
    {
        return 'admin';
    }
}
