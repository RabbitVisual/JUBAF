<?php

namespace Modules\Calendario\App\Policies;

use App\Models\User;
use Modules\Calendario\App\Models\CalendarRegistration;

class CalendarRegistrationPolicy
{
    public function delete(User $user, CalendarRegistration $calendarRegistration): bool
    {
        if ($user->can('calendario.registrations.delete')) {
            return true;
        }

        return (int) $calendarRegistration->user_id === (int) $user->id
            && $user->can('calendario.participate');
    }
}
