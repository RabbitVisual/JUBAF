<?php

namespace Modules\PainelJovens\App\Http\Controllers\PainelLider;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class YouthMetricsController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->can('paineljovens.dashboard.metrics'), 403);

        $churchIds = $request->user()->affiliatedChurchIds();
        $youthCount = $churchIds === []
            ? 0
            : User::query()
                ->role('jovens')
                ->whereIn('church_id', $churchIds)
                ->count();

        return view('paineljovens::painellider.metrics.index', [
            'youthCount' => $youthCount,
            'churchIds' => $churchIds,
        ]);
    }
}
