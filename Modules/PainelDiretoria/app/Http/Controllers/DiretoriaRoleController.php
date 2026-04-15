<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use Modules\Permisao\App\Http\Controllers\RoleManagementController;

class DiretoriaRoleController extends RoleManagementController
{
    protected function routePrefix(): string
    {
        return 'diretoria.roles';
    }

    protected function permissionsRoutePrefix(): string
    {
        return 'diretoria.permissions';
    }

    protected function viewPrefix(): string
    {
        return 'permisao::paineldiretoria.roles';
    }

    protected function panelLayout(): string
    {
        return 'layouts.app';
    }
}
