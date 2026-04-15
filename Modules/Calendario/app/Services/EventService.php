<?php

namespace Modules\Calendario\App\Services;

use Illuminate\Support\Collection;
use Modules\Calendario\App\Http\Controllers\PublicCalendarController;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Models\CalendarRegistration;

class EventService
{
    /**
     * Próximos eventos públicos (mesmos critérios que {@see PublicCalendarController::index}).
     * Destaques (`is_featured`) primeiro.
     *
     * @return Collection<int, CalendarEvent>
     */
    public function upcomingPublicFeatured(int $limit = 6): Collection
    {
        return CalendarEvent::query()
            ->published()
            ->where('visibility', CalendarEvent::VIS_PUBLIC)
            ->where('start_date', '>=', now()->startOfDay())
            ->orderByDesc('is_featured')
            ->orderBy('start_date')
            ->limit($limit)
            ->get();
    }

    public function confirmedCount(CalendarEvent $event): int
    {
        return CalendarRegistration::query()
            ->where('evento_id', $event->id)
            ->where('status', CalendarRegistration::STATUS_CONFIRMED)
            ->count();
    }

    public function resolveRegistrationStatus(CalendarEvent $event): string
    {
        $capacity = $event->capacity;
        if ($capacity === null) {
            return CalendarRegistration::STATUS_CONFIRMED;
        }

        $confirmed = $this->confirmedCount($event);

        return $confirmed >= $capacity
            ? CalendarRegistration::STATUS_WAITLIST
            : CalendarRegistration::STATUS_CONFIRMED;
    }
}
