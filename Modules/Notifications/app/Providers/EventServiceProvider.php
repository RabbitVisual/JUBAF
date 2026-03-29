<?php

namespace Modules\Notifications\App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Events\App\Events\RegistrationConfirmed;
use Modules\Notifications\App\Listeners\SendEventRegistrationNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        RegistrationConfirmed::class => [
            SendEventRegistrationNotification::class,
        ],
        'payment.completed' => [
            \Modules\Notifications\App\Listeners\SendPaymentCompletedNotification::class,
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
