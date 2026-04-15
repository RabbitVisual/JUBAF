<?php

namespace Modules\Secretaria\App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Secretaria\App\Models\Minute;

/**
 * Ata publicada e bloqueada — integrações via listener (notificações, avisos).
 */
class MinutePublished
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Minute $minute,
        public User $publisher
    ) {}
}
