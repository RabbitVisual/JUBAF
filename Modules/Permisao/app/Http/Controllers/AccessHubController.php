<?php

namespace Modules\Permisao\App\Http\Controllers;

use App\Http\Controllers\Controller;

class AccessHubController extends Controller
{
    public function superadmin()
    {
        return view('permisao::admin.hub');
    }

    public function diretoria()
    {
        return view('permisao::paineldiretoria.hub');
    }
}
