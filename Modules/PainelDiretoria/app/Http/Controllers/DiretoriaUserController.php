<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use Modules\Permisao\App\Http\Controllers\UserManagementController;

class DiretoriaUserController extends UserManagementController
{
    protected function routePrefix(): string
    {
        return 'diretoria.users';
    }

    protected function viewPrefix(): string
    {
        return 'permisao::paineldiretoria.users';
    }

    protected function panelLayout(): string
    {
        return 'layouts.app';
    }
}
