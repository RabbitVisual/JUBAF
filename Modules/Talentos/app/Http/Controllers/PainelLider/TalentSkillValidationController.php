<?php

namespace Modules\Talentos\App\Http\Controllers\PainelLider;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Talentos\App\Events\TalentSkillValidated;
use Modules\Talentos\App\Models\TalentProfile;
use Modules\Talentos\App\Models\TalentSkill;

class TalentSkillValidationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user?->can('paineljovens.talentos.validate'), 403);

        $churchIds = $user->affiliatedChurchIds();
        if ($churchIds === []) {
            $profiles = collect();

            return view('talentos::painellider.talent-validation.index', [
                'profiles' => $profiles,
                'routePrefix' => 'lideres.talentos',
            ]);
        }

        $profiles = TalentProfile::query()
            ->with(['user.church', 'skills'])
            ->whereHas('user', function (Builder $q) use ($churchIds): void {
                $q->whereIn('church_id', $churchIds);
            })
            ->whereExists(function ($sub): void {
                $sub->selectRaw('1')
                    ->from('talent_profile_skill')
                    ->whereColumn('talent_profile_skill.talent_profile_id', 'talent_profiles.id')
                    ->whereNull('talent_profile_skill.validated_at');
            })
            ->orderByDesc('talent_profiles.updated_at')
            ->limit(80)
            ->get();

        return view('talentos::painellider.talent-validation.index', [
            'profiles' => $profiles,
            'routePrefix' => 'lideres.talentos',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'talent_profile_id' => ['required', 'integer', 'exists:talent_profiles,id'],
            'talent_skill_id' => ['required', 'integer', 'exists:talent_skills,id'],
        ]);

        $profile = TalentProfile::query()->with('user')->findOrFail($request->integer('talent_profile_id'));
        $this->authorize('validateYouthSkills', $profile);

        $skillId = $request->integer('talent_skill_id');
        if (! $profile->skills()->whereKey($skillId)->exists()) {
            return back()->withErrors(['talent_skill_id' => 'Esta competência não está associada a este perfil.']);
        }

        $profile->skills()->updateExistingPivot($skillId, [
            'validated_at' => now(),
            'validated_by' => $request->user()->id,
        ]);

        $skill = TalentSkill::query()->findOrFail($skillId);
        event(new TalentSkillValidated($profile->user, $skill, $request->user()));

        return back()->with('success', 'Competência validada com sucesso.');
    }
}
