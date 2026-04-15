<?php

namespace Modules\Igrejas\App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaderAssignedToChurch
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public int $churchId,
    ) {}
}
