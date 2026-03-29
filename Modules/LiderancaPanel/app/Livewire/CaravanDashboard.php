<?php

namespace Modules\LiderancaPanel\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\LiderancaPanel\Services\CaravanFunnelService;

class CaravanDashboard extends Component
{
    use WithPagination;

    /** @var string|null HTML select binds strings */
    public ?string $selectedEventId = null;

    public function mount(CaravanFunnelService $funnel): void
    {
        $id = $funnel->resolveDefaultEventId();
        $this->selectedEventId = $id !== null ? (string) $id : null;
    }

    public function updatedSelectedEventId(): void
    {
        $this->resetPage();
    }

    public function render(CaravanFunnelService $funnel)
    {
        $user = auth()->user();
        $churchId = (int) $user->church_id;

        $events = $funnel->upcomingEventsForSelect();

        $stats = null;
        $members = null;
        $eventId = $this->selectedEventId !== null && $this->selectedEventId !== '' ? (int) $this->selectedEventId : null;

        if ($eventId) {
            $stats = $funnel->funnelStats($churchId, $eventId);
            $members = $funnel->paginateMembersForEvent($churchId, $eventId, 12);
        }

        return view('liderancapanel::livewire.caravan-dashboard', [
            'events' => $events,
            'stats' => $stats,
            'members' => $members,
            'funnel' => $funnel,
            'resolvedEventId' => $eventId,
        ]);
    }
}
