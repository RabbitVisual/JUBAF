<?php

namespace Modules\Secretaria\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Secretaria\App\Http\Controllers\Concerns\RendersSecretariaPanelViews;
use Modules\Secretaria\App\Models\Meeting;
use Modules\Secretaria\App\Services\SecretariaIntegrationBus;

class MeetingController extends Controller
{
    use RendersSecretariaPanelViews;

    protected function routePrefix(): string
    {
        return 'diretoria.secretaria.reunioes';
    }

    protected function panelLayout(): string
    {
        return 'layouts.app';
    }

    public function index()
    {
        $this->authorize('viewAny', Meeting::class);
        $meetings = Meeting::query()->with('creator')->orderByDesc('starts_at')->paginate(20);

        return $this->secretariaView('meetings.index', [
            'meetings' => $meetings,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Meeting::class);

        return $this->secretariaView('meetings.create', [
            'meeting' => new Meeting,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Meeting::class);
        $data = $request->validate([
            'type' => ['required', 'string', 'max:64'],
            'title' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:scheduled,held,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);
        $data['created_by_id'] = $request->user()->id;
        $meeting = Meeting::create($data);
        SecretariaIntegrationBus::syncMeetingCalendar($meeting->fresh(), $request->user());

        return redirect()->route($this->routePrefix().'.show', $meeting)->with('success', 'Reunião registada.');
    }

    public function show(Meeting $meeting)
    {
        $this->authorize('view', $meeting);
        $meeting->load(['creator', 'minutes', 'convocations']);

        return $this->secretariaView('meetings.show', [
            'meeting' => $meeting,
        ]);
    }

    public function edit(Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        return $this->secretariaView('meetings.edit', [
            'meeting' => $meeting,
        ]);
    }

    public function update(Request $request, Meeting $meeting)
    {
        $this->authorize('update', $meeting);
        $data = $request->validate([
            'type' => ['required', 'string', 'max:64'],
            'title' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:scheduled,held,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);
        $meeting->update($data);
        SecretariaIntegrationBus::syncMeetingCalendar($meeting->fresh(), $request->user());

        return redirect()->route($this->routePrefix().'.show', $meeting)->with('success', 'Reunião atualizada.');
    }

    public function destroy(Meeting $meeting)
    {
        $this->authorize('delete', $meeting);
        SecretariaIntegrationBus::deleteMeetingCalendarEvent($meeting);
        $meeting->delete();

        return redirect()->route($this->routePrefix().'.index')->with('success', 'Reunião eliminada.');
    }
}
