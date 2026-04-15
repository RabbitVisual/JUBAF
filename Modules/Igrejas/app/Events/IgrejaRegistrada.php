<?php

namespace Modules\Igrejas\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Igrejas\App\Models\Church;

class IgrejaRegistrada
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Church $church
    ) {}
}
