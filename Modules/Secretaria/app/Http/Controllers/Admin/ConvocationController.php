<?php

namespace Modules\Secretaria\App\Http\Controllers\Admin;

use Modules\Secretaria\App\Http\Controllers\Diretoria\ConvocationController as Base;

class ConvocationController extends Base
{
    protected function routePrefix(): string
    {
        return 'admin.secretaria.convocatorias';
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
