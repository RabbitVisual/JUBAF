<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use App\Http\Controllers\Admin\CarouselController as AdminCarouselController;

class DiretoriaCarouselController extends AdminCarouselController
{
    protected function routePrefix(): string
    {
        return 'diretoria.carousel';
    }

    protected function viewPrefix(): string
    {
        return 'paineldiretoria::carousel';
    }

    protected function panelLayout(): string
    {
        return 'layouts.app';
    }
}
