<?php

namespace Modules\Igrejas\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Igrejas\App\Models\Church;

class IgrejaAtualizada
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @param  array<string, mixed>|null  $originalCrmStatus
     */
    public function __construct(
        public Church $church,
        public ?string $originalCrmStatus = null
    ) {}
}
