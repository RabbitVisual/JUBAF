<?php

namespace Modules\Secretaria\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use App\Support\ErpChurchScope;
use Illuminate\Http\Request;
use Modules\Secretaria\App\Http\Controllers\Concerns\RendersSecretariaPanelViews;
use Modules\Secretaria\App\Models\Convocation;
use Modules\Secretaria\App\Models\Meeting;
use Modules\Secretaria\App\Models\Minute;

class SecretariaDashboardController extends Controller
{
    use RendersSecretariaPanelViews;

    protected function routePrefix(): string
    {
        return 'diretoria.secretaria';
    }

    protected function panelLayout(): string
    {
        return 'paineldiretoria::components.layouts.app';
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Meeting::class);

        $user = $request->user();

        $pendingMinutesQuery = Minute::query()->where('status', 'pending_approval');
        if ($user) {
            ErpChurchScope::applyToSecretariaMinuteQuery($pendingMinutesQuery, $user);
        }
        $pendingMinutes = (clone $pendingMinutesQuery)->count();

        $pendingConvocations = Convocation::query()->where('status', 'pending_approval')->count();
        $pendingMinutesList = Minute::query()
            ->when($user, fn ($q) => ErpChurchScope::applyToSecretariaMinuteQuery($q, $user))
            ->where('status', 'pending_approval')
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();
        $pendingConvocationsList = Convocation::query()
            ->where('status', 'pending_approval')
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();
        $upcomingMeetings = Meeting::query()
            ->where('starts_at', '>=', now()->startOfDay())
            ->where('status', '!=', 'cancelled')
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        $meetingsThisWeek = Meeting::query()
            ->where('starts_at', '>=', now()->startOfWeek())
            ->where('starts_at', '<=', now()->endOfWeek())
            ->where('status', '!=', 'cancelled')
            ->orderBy('starts_at')
            ->limit(12)
            ->get();

        $extraordinarySoon = Convocation::query()
            ->where('status', 'published')
            ->where('assembly_at', '>=', now())
            ->orderBy('assembly_at')
            ->limit(3)
            ->get();

        $isSecretary = $user && $user->hasAnyRole(['secretario-1', 'secretario-2']);

        return $this->secretariaView('dashboard', [
            'secretariaBase' => $this->secretariaBaseRoute(),
            'pendingMinutes' => $pendingMinutes,
            'pendingConvocations' => $pendingConvocations,
            'pendingMinutesList' => $pendingMinutesList,
            'pendingConvocationsList' => $pendingConvocationsList,
            'upcomingMeetings' => $upcomingMeetings,
            'meetingsThisWeek' => $meetingsThisWeek,
            'extraordinarySoon' => $extraordinarySoon,
            'isSecretary' => $isSecretary,
        ]);
    }
}
