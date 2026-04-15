<?php

namespace Modules\Talentos\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Talentos\App\Http\Requests\UpdateTalentProfileRequest;
use Modules\Talentos\App\Models\TalentArea;
use Modules\Talentos\App\Models\TalentProfile;
use Modules\Talentos\App\Models\TalentSkill;

class TalentProfilePanelController extends Controller
{
    public function edit(Request $request): View
    {
        abort_unless($request->user()?->can('talentos.profile.edit'), 403);

        $user = $request->user();
        $profile = $user->talentProfile;

        if (! $profile) {
            $profile = new TalentProfile([
                'user_id' => $user->id,
                'is_searchable' => true,
            ]);
        } else {
            $profile->load(['skills', 'areas']);
        }

        $routeName = $request->route()->getName() ?? '';
        $isJovens = str_contains($routeName, 'jovens.');

        $user->load([
            'talentAssignments' => function ($q): void {
                $q->with('calendarEvent')
                    ->orderByDesc('updated_at')
                    ->limit(20);
            },
        ]);

        $enrollmentStarted = $profile->exists;
        $enrollmentComplete = $enrollmentStarted
            && (
                filled($profile->bio)
                || $profile->skills()->exists()
                || $profile->areas()->exists()
            );

        $view = $isJovens ? 'talentos::paineljovens.inscription' : 'talentos::painellider.inscription';

        return view($view, [
            'profile' => $profile,
            'skills' => TalentSkill::query()->orderBy('name')->get(),
            'areas' => TalentArea::query()->orderBy('name')->get(),
            'routePrefix' => $isJovens ? 'jovens.talentos' : 'lideres.talentos',
            'assignments' => $user->talentAssignments,
            'enrollmentStarted' => $enrollmentStarted,
            'enrollmentComplete' => $enrollmentComplete,
        ]);
    }

    public function update(UpdateTalentProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $profile = TalentProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['is_searchable' => true]
        );

        $this->authorize('update', $profile);

        $profile->fill([
            'bio' => $request->input('bio'),
            'availability_text' => $request->input('availability_text'),
            'is_searchable' => $request->boolean('is_searchable'),
        ]);
        $profile->save();

        $skillIds = array_map('intval', array_filter($request->input('skill_ids', [])));
        $rawLevels = $request->input('skill_levels', []);
        $syncSkills = [];
        foreach ($skillIds as $id) {
            $lvl = $rawLevels[$id] ?? $rawLevels[(string) $id] ?? null;
            $lvl = is_string($lvl) && $lvl !== '' ? $lvl : null;
            $syncSkills[$id] = ['level' => $lvl];
        }
        $profile->skills()->sync($syncSkills);

        $areaIds = array_filter($request->input('area_ids', []));
        $profile->areas()->sync($areaIds);

        $prefix = str_contains($request->route()->getName() ?? '', 'jovens.') ? 'jovens.talentos' : 'lideres.talentos';

        return redirect()
            ->route($prefix.'.profile.edit')
            ->with('success', 'A sua inscrição no banco de talentos foi guardada. A diretoria pode contactá-lo(a) para eventos e equipas quando houver oportunidades.');
    }
}
