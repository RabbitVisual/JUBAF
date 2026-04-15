<?php

namespace Modules\Secretaria\App\Http\Controllers\Admin;

use Modules\Secretaria\App\Http\Controllers\Diretoria\MinuteController as Base;

class MinuteController extends Base
{
    protected function routePrefix(): string
    {
        return 'admin.secretaria.atas';
    }

    protected function panelLayout(): string
    {
        return 'layouts.app';
    }

    protected function secretariaViewsDirectory(): string
    {
        return 'admin';
    }
}
