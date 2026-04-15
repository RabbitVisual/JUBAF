<?php

namespace Modules\Financeiro\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Financeiro\App\Events\FinancialObligationGenerated;
use Modules\Financeiro\App\Events\FinancialObligationPaid;
use Modules\Financeiro\App\Listeners\LogFinancialObligationGenerated;
use Modules\Financeiro\App\Listeners\LogFinancialObligationPaid;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        FinancialObligationGenerated::class => [
            LogFinancialObligationGenerated::class,
        ],
        FinancialObligationPaid::class => [
            LogFinancialObligationPaid::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = false;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
