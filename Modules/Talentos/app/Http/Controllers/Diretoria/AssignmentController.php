<?php

namespace Modules\Talentos\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Talentos\App\Http\Requests\StoreTalentAssignmentRequest;
use Modules\Talentos\App\Http\Requests\UpdateTalentAssignmentRequest;
use Modules\Talentos\App\Models\TalentAssignment;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', TalentAssignment::class);

        $q = TalentAssignment::query()
            ->with(['user', 'calendarEvent', 'creator']);

        if ($request->filled('status')) {
            $q->where('status', $request->input('status'));
        }

        $assignments = $q->orderByDesc('id')->paginate(25)->withQueryString();

        return view('talentos::paineldiretoria.assignments.index', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'assignments' => $assignments,
            'filters' => $request->only(['status']),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', TalentAssignment::class);

        $events = module_enabled('Calendario')
            ? CalendarEvent::query()->where('starts_at', '>=', now()->subDay())->orderBy('starts_at')->limit(100)->get()
            : collect();

        $preUserId = $request->integer('user_id') ?: null;

        return view('talentos::paineldiretoria.assignments.create', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'assignment' => new TalentAssignment([
                'status' => TalentAssignment::STATUS_INVITED,
                'user_id' => $preUserId,
            ]),
            'users' => User::query()->orderBy('name')->limit(500)->get(),
            'events' => $events,
        ]);
    }

    public function store(StoreTalentAssignmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        TalentAssignment::create($data);

        return redirect()
            ->route('diretoria.talentos.assignments.index')
            ->with('success', 'Atribuição criada.');
    }

    public function edit(TalentAssignment $assignment): View
    {
        $this->authorize('update', $assignment);

        $events = module_enabled('Calendario')
            ? CalendarEvent::query()->where('starts_at', '>=', now()->subMonths(3))->orderBy('starts_at')->limit(150)->get()
            : collect();

        return view('talentos::paineldiretoria.assignments.edit', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'assignment' => $assignment,
            'users' => User::query()->orderBy('name')->limit(500)->get(),
            'events' => $events,
        ]);
    }

    public function update(UpdateTalentAssignmentRequest $request, TalentAssignment $assignment): RedirectResponse
    {
        $this->authorize('update', $assignment);

        $assignment->update($request->validated());

        return redirect()
            ->route('diretoria.talentos.assignments.index')
            ->with('success', 'Atribuição atualizada.');
    }

    public function destroy(TalentAssignment $assignment): RedirectResponse
    {
        $this->authorize('delete', $assignment);
        $assignment->delete();

        return redirect()
            ->route('diretoria.talentos.assignments.index')
            ->with('success', 'Atribuição removida.');
    }
}
