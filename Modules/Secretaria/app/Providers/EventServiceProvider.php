<?php

namespace Modules\Secretaria\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Secretaria\App\Events\AtaPublished;
use Modules\Secretaria\App\Listeners\DispatchMinutePublishedIntegrations;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        AtaPublished::class => [
            DispatchMinutePublishedIntegrations::class,
            \Modules\Notificacoes\App\Listeners\SendAtaSummaryToWhatsApp::class,
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
