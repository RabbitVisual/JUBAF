<?php

namespace Modules\Calendario\App\Services;

use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Models\CalendarRegistration;

class EventService
{
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
