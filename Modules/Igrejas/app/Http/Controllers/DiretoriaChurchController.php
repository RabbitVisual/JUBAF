<?php

namespace Modules\Igrejas\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Igrejas\App\Http\Controllers\Concerns\ManagesChurches;

class DiretoriaChurchController extends Controller
{
    use ManagesChurches;

    protected function routePrefix(): string
    {
        return 'diretoria.igrejas';
    }

    protected function viewPrefix(): string
    {
        return 'igrejas::paineldiretoria.churches';
    }

    protected function panelLayout(): string
    {
        return 'layouts.app';
    }
}
