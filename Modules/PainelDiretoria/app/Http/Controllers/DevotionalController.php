<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use App\Http\Controllers\Admin\DevotionalController as AdminDevotionalController;
use App\Models\Devotional;
use Illuminate\View\View;

class DevotionalController extends AdminDevotionalController
{
    protected function routePrefix(): string
    {
        return 'diretoria.devotionals';
    }

    protected function viewPrefix(): string
    {
        return 'paineldiretoria::devotionals';
    }

    protected function panelLayout(): string
    {
        return 'paineldiretoria::components.layouts.app';
    }

    public function index(): View
    {
        $this->authorize('viewAny', Devotional::class);

        $rows = Devotional::query()
            ->with(['user', 'boardMember'])
            ->orderByDesc('devotional_date')
            ->orderByDesc('updated_at')
            ->paginate(20);

        $stats = [
            'total' => (int) Devotional::query()->count(),
            'published' => (int) Devotional::query()->where('status', Devotional::STATUS_PUBLISHED)->count(),
            'draft' => (int) Devotional::query()->where('status', Devotional::STATUS_DRAFT)->count(),
        ];

        $routePrefix = $this->routePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.index', compact('rows', 'routePrefix', 'layout', 'stats'));
    }
}
