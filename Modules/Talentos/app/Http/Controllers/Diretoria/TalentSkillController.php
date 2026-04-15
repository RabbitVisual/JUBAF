<?php

namespace Modules\Talentos\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Talentos\App\Http\Requests\StoreTalentSkillRequest;
use Modules\Talentos\App\Http\Requests\UpdateTalentSkillRequest;
use Modules\Talentos\App\Models\TalentSkill;

class TalentSkillController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', TalentSkill::class);

        $skills = TalentSkill::query()->orderBy('name')->get();

        return view('talentos::paineldiretoria.taxonomy.skills-index', [
            'layout' => 'layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'skills' => $skills,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', TalentSkill::class);

        return view('talentos::paineldiretoria.taxonomy.skill-form', [
            'layout' => 'layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'skill' => new TalentSkill,
            'mode' => 'create',
        ]);
    }

    public function store(StoreTalentSkillRequest $request): RedirectResponse
    {
        TalentSkill::create($request->validated());

        return redirect()
            ->route('diretoria.talentos.competencias.index')
            ->with('success', 'Competência criada.');
    }

    public function edit(TalentSkill $skill): View
    {
        $this->authorize('update', $skill);

        return view('talentos::paineldiretoria.taxonomy.skill-form', [
            'layout' => 'layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'skill' => $skill,
            'mode' => 'edit',
        ]);
    }

    public function update(UpdateTalentSkillRequest $request, TalentSkill $skill): RedirectResponse
    {
        $this->authorize('update', $skill);

        $skill->update($request->validated());

        return redirect()
            ->route('diretoria.talentos.competencias.index')
            ->with('success', 'Competência atualizada.');
    }

    public function destroy(TalentSkill $skill): RedirectResponse
    {
        $this->authorize('delete', $skill);

        if ($skill->profiles()->exists()) {
            return redirect()
                ->route('diretoria.talentos.competencias.index')
                ->with('error', 'Não é possível remover: existem perfis com esta competência.');
        }

        $skill->delete();

        return redirect()
            ->route('diretoria.talentos.competencias.index')
            ->with('success', 'Competência removida.');
    }
}
