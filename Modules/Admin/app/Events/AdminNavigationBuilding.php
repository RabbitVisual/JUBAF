<?php

namespace Modules\Admin\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Admin\App\Support\AdminNavigationBag;

/**
 * Dispatched before the admin sidebar menu is filtered; listeners may mutate {@see AdminNavigationBag::$sections}.
 */
final class AdminNavigationBuilding
{
    use Dispatchable;

    public function __construct(
        public AdminNavigationBag $bag
    ) {}
}
