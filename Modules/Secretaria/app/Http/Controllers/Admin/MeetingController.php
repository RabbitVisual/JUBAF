<?php

namespace Modules\Secretaria\App\Http\Controllers\Admin;

use Modules\Secretaria\App\Http\Controllers\Diretoria\MeetingController as Base;

class MeetingController extends Base
{
    protected function routePrefix(): string
    {
        return 'admin.secretaria.reunioes';
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
