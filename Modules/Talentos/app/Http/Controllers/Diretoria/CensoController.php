<?php

namespace Modules\Talentos\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\PainelJovens\App\Services\CensoService;

class CensoController extends Controller
{
    public function __construct(
        protected CensoService $censoService
    ) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()?->can('paineljovens.census.view'), 403);

        return view('talentos::paineldiretoria.censo.index', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'summary' => $this->censoService->youthSummaryBySector(),
            'topSkills' => $this->censoService->topValidatedSkills(16),
        ]);
    }
}
