<?php

namespace Modules\Secretaria\App\Http\Controllers\Admin;

use Modules\Secretaria\App\Http\Controllers\Diretoria\DocumentController as Base;

class DocumentController extends Base
{
    protected function routePrefix(): string
    {
        return 'admin.secretaria.arquivo';
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
