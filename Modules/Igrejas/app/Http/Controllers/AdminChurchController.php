<?php

namespace Modules\Igrejas\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Igrejas\App\Http\Controllers\Concerns\ManagesChurches;
use Modules\Igrejas\App\Models\Church;

class AdminChurchController extends Controller
{
    use ManagesChurches;

    protected function routePrefix(): string
    {
        return 'admin.igrejas';
    }

    protected function viewPrefix(): string
    {
        return 'igrejas::admin.churches';
    }

    protected function panelLayout(): string
    {
        return 'admin::layouts.admin';
    }

    /**
     * @param  \Illuminate\Contracts\Pagination\LengthAwarePaginator<int, Church>  $churches
     * @return array<string, mixed>
     */
    protected function indexExtraData(Request $request, $churches, array $filters): array
    {
        return [
            'stats' => [
                'churches_total' => Church::query()->count(),
                'churches_active' => Church::query()->where('is_active', true)->count(),
                'users_with_church' => User::query()->whereNotNull('church_id')->count(),
                'jovens_linked' => User::query()->role('jovens')->whereNotNull('church_id')->count(),
                'lideres_linked' => User::query()->role('lider')->whereNotNull('church_id')->count(),
            ],
        ];
    }
}
