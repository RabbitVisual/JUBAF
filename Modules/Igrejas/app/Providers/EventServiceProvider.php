<?php

namespace Modules\Igrejas\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Igrejas\App\Events\ChurchSectorAssigned;
use Modules\Igrejas\App\Events\LeaderAssignedToChurch;
use Modules\Igrejas\App\Listeners\LogChurchSectorAssignment;
use Modules\Igrejas\App\Listeners\LogLeaderAssignedToChurch;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        ChurchSectorAssigned::class => [
            LogChurchSectorAssignment::class,
        ],
        LeaderAssignedToChurch::class => [
            LogLeaderAssignedToChurch::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
