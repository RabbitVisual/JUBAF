<?php

namespace Modules\Igrejas\App\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Igrejas\App\Events\LeaderAssignedToChurch;

class LogLeaderAssignedToChurch
{
    public function handle(LeaderAssignedToChurch $event): void
    {
        Log::info('erp.leader_assigned_to_church', [
            'user_id' => $event->user->id,
            'church_id' => $event->churchId,
        ]);
    }
}
