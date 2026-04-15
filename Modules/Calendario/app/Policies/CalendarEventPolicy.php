<?php

namespace Modules\Calendario\App\Policies;

use App\Models\User;
use Modules\Calendario\App\Models\CalendarEvent;

class CalendarEventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('calendario.events.view');
    }

    public function view(User $user, CalendarEvent $calendarEvent): bool
    {
        return $user->can('calendario.events.view');
    }

    public function create(User $user): bool
    {
        return $user->can('calendario.events.create');
    }

    public function update(User $user, CalendarEvent $calendarEvent): bool
    {
        return $user->can('calendario.events.edit');
    }

    public function delete(User $user, CalendarEvent $calendarEvent): bool
    {
        return $user->can('calendario.events.delete');
    }

    public function manageRegistrations(User $user, CalendarEvent $calendarEvent): bool
    {
        return $user->can('calendario.registrations.view');
    }
}
