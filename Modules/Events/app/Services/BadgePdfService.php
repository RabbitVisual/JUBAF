<?php

namespace Modules\Events\App\Services;

use Modules\Events\App\Models\Event;
use Modules\Events\App\Models\EventBadge;
use Modules\Events\App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;

class BadgePdfService
{
    /**
     * Generate PDF for badges
     */
    public function generate(Event $event, $participants)
    {
        $badge = $event->getBadgeTemplate();
        if (!$badge) {
            return null;
        }

        $data = [
            'event' => $event,
            'badge' => $badge,
            'participants' => $participants,
        ];

        return Pdf::loadView('events::admin.events.pdf.badges', $data)
            ->setPaper($badge->paper_size, $badge->orientation);
    }
}
