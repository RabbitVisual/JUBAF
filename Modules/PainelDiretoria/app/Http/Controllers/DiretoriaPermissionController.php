<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use Modules\Permisao\App\Http\Controllers\PermissionManagementController;

class DiretoriaPermissionController extends PermissionManagementController
{
    protected function routePrefix(): string
    {
        return 'diretoria.permissions';
    }

    protected function rolesRoutePrefix(): string
    {
        return 'diretoria.roles';
    }

    protected function viewPrefix(): string
    {
        return 'permisao::paineldiretoria.permissions';
    }

    protected function panelLayout(): string
    {
        return 'paineldiretoria::components.layouts.app';
    }
}
