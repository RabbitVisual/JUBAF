<?php

namespace Modules\Talentos\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Talentos\App\Http\Requests\StoreTalentAreaRequest;
use Modules\Talentos\App\Http\Requests\UpdateTalentAreaRequest;
use Modules\Talentos\App\Models\TalentArea;

class TalentAreaController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', TalentArea::class);

        $areas = TalentArea::query()->orderBy('name')->get();

        return view('talentos::paineldiretoria.taxonomy.areas-index', [
            'layout' => 'layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'areas' => $areas,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', TalentArea::class);

        return view('talentos::paineldiretoria.taxonomy.area-form', [
            'layout' => 'layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'area' => new TalentArea,
            'mode' => 'create',
        ]);
    }

    public function store(StoreTalentAreaRequest $request): RedirectResponse
    {
        TalentArea::create($request->validated());

        return redirect()
            ->route('diretoria.talentos.areas-servico.index')
            ->with('success', 'Área criada.');
    }

    public function edit(TalentArea $area): View
    {
        $this->authorize('update', $area);

        return view('talentos::paineldiretoria.taxonomy.area-form', [
            'layout' => 'layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'area' => $area,
            'mode' => 'edit',
        ]);
    }

    public function update(UpdateTalentAreaRequest $request, TalentArea $area): RedirectResponse
    {
        $this->authorize('update', $area);

        $area->update($request->validated());

        return redirect()
            ->route('diretoria.talentos.areas-servico.index')
            ->with('success', 'Área atualizada.');
    }

    public function destroy(TalentArea $area): RedirectResponse
    {
        $this->authorize('delete', $area);

        if ($area->profiles()->exists()) {
            return redirect()
                ->route('diretoria.talentos.areas-servico.index')
                ->with('error', 'Não é possível remover: existem perfis nesta área.');
        }

        $area->delete();

        return redirect()
            ->route('diretoria.talentos.areas-servico.index')
            ->with('success', 'Área removida.');
    }
}
