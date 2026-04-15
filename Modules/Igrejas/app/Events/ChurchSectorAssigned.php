<?php

namespace Modules\Igrejas\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Igrejas\App\Models\Church;

/**
 * Disparado quando uma igreja é criada com setor ou quando o setor é alterado.
 */
class ChurchSectorAssigned
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Church $church,
        public ?int $previousJubafSectorId
    ) {}
}
