<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Services\Admin\ModuleService;

class DiretoriaModuleController extends AdminModuleController
{
    public function __construct(ModuleService $moduleService)
    {
        parent::__construct($moduleService);
    }

    protected function routePrefix(): string
    {
        return 'diretoria.modules';
    }

    protected function viewPrefix(): string
    {
        return 'paineldiretoria::modules';
    }

    protected function panelLayout(): string
    {
        return 'layouts.app';
    }
}
