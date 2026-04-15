<?php

namespace Modules\Talentos\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Igrejas\App\Models\Church;
use Modules\Talentos\App\Models\TalentArea;
use Modules\Talentos\App\Models\TalentProfile;
use Modules\Talentos\App\Models\TalentSkill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', TalentProfile::class);

        $q = $this->baseDirectoryQuery();
        $this->applyDirectoryFilters($q, $request);

        $profiles = $q->paginate(24)->withQueryString();

        return view('talentos::paineldiretoria.directory.index', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'profiles' => $profiles,
            'skills' => TalentSkill::query()->orderBy('name')->get(),
            'areas' => TalentArea::query()->orderBy('name')->get(),
            'churches' => module_enabled('Igrejas') ? Church::query()->orderBy('name')->get() : collect(),
            'filters' => $request->only(['church_id', 'skill_id', 'area_id', 'searchable_only', 'q']),
        ]);
    }

    public function show(User $user): View
    {
        $profile = $user->talentProfile;
        abort_if(! $profile, 404);

        $this->authorize('view', $profile);

        $profile->load(['skills', 'areas', 'user.church', 'user.talentAssignments' => function ($rel) {
            $rel->with('calendarEvent')->orderByDesc('id')->limit(20);
        }]);

        return view('talentos::paineldiretoria.directory.show', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'member' => $user,
            'profile' => $profile,
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        abort_unless($request->user()?->can('talentos.directory.export'), 403);

        $filename = 'talentos-diretorio-'.now()->format('Y-m-d').'.csv';

        $q = $this->baseDirectoryQuery();
        $this->applyDirectoryFilters($q, $request);

        return response()->streamDownload(function () use ($q) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['nome', 'email', 'igreja', 'bio', 'disponibilidade', 'pesquisavel', 'competencias', 'areas']);

            $q->chunk(200, function ($chunk) use ($out) {
                foreach ($chunk as $profile) {
                    $u = $profile->user;
                    fputcsv($out, [
                        $u?->name,
                        $u?->email,
                        $u?->church?->name,
                        $profile->bio,
                        $profile->availability_text,
                        $profile->is_searchable ? 'sim' : 'nao',
                        $profile->skills->pluck('name')->implode('; '),
                        $profile->areas->pluck('name')->implode('; '),
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @return Builder<TalentProfile>
     */
    protected function baseDirectoryQuery(): Builder
    {
        return TalentProfile::query()
            ->with(['user.church', 'skills', 'areas'])
            ->join('users', 'users.id', '=', 'talent_profiles.user_id')
            ->orderBy('users.name')
            ->select('talent_profiles.*');
    }

    /**
     * @param  Builder<TalentProfile>  $q
     */
    protected function applyDirectoryFilters(Builder $q, Request $request): void
    {
        if ($request->filled('church_id') && module_enabled('Igrejas')) {
            $q->where('users.church_id', $request->integer('church_id'));
        }

        if ($request->filled('skill_id')) {
            $skillId = $request->integer('skill_id');
            $q->whereExists(function ($sub) use ($skillId) {
                $sub->selectRaw('1')
                    ->from('talent_profile_skill')
                    ->whereColumn('talent_profile_skill.talent_profile_id', 'talent_profiles.id')
                    ->where('talent_profile_skill.talent_skill_id', $skillId);
            });
        }

        if ($request->filled('area_id')) {
            $areaId = $request->integer('area_id');
            $q->whereExists(function ($sub) use ($areaId) {
                $sub->selectRaw('1')
                    ->from('talent_profile_area')
                    ->whereColumn('talent_profile_area.talent_profile_id', 'talent_profiles.id')
                    ->where('talent_profile_area.talent_area_id', $areaId);
            });
        }

        if ($request->boolean('searchable_only')) {
            $q->where('talent_profiles.is_searchable', true);
        }

        $term = trim((string) $request->input('q', ''));
        if ($term !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $q->where(function ($w) use ($like) {
                $w->where('users.name', 'like', $like)
                    ->orWhere('users.email', 'like', $like);
            });
        }
    }
}
