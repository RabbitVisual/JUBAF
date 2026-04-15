<?php

namespace Modules\Financeiro\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Financeiro\App\Console\Commands\GenerateFinancialObligationsCommand;
use Modules\Financeiro\App\Console\Commands\GenerateMonthlyQuotaInvoicesCommand;
use Modules\Financeiro\App\Console\Commands\MarkFinTransactionsOverdueCommand;
use Modules\Financeiro\App\Jobs\GerarCotasAssociativasJob;
use Modules\Financeiro\App\Services\FinanceiroService;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinExpenseRequest;
use Modules\Financeiro\App\Models\FinObligation;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Financeiro\App\Policies\FinCategoryPolicy;
use Modules\Financeiro\App\Policies\FinExpenseRequestPolicy;
use Modules\Financeiro\App\Policies\FinObligationPolicy;
use Modules\Financeiro\App\Policies\FinTransactionPolicy;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FinanceiroServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Financeiro';

    protected string $nameLower = 'financeiro';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));

        Gate::policy(FinTransaction::class, FinTransactionPolicy::class);
        Gate::policy(FinExpenseRequest::class, FinExpenseRequestPolicy::class);
        Gate::policy(FinCategory::class, FinCategoryPolicy::class);
        Gate::policy(FinObligation::class, FinObligationPolicy::class);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->singleton(FinanceiroService::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        $this->commands([
            GenerateFinancialObligationsCommand::class,
            GenerateMonthlyQuotaInvoicesCommand::class,
            MarkFinTransactionsOverdueCommand::class,
        ]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('financeiro:generate-obligations')->yearlyOn(3, 1, '6:00');
            $schedule->job(new GerarCotasAssociativasJob)->monthlyOn(1, '7:00');
            $schedule->command('financeiro:mark-overdue')->dailyAt('1:00');
        });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->name, config('modules.paths.generator.config.path'));

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments = explode('.', $this->nameLower.'.'.$config_key);

                    // Remove duplicated adjacent segments
                    $normalized = [];
                    foreach ($segments as $segment) {
                        if (end($normalized) !== $segment) {
                            $normalized[] = $segment;
                        }
                    }

                    $key = ($config === 'config.php') ? $this->nameLower : implode('.', $normalized);

                    $this->publishes([$file->getPathname() => config_path($config)], 'config');
                    $this->merge_config_from($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    protected function merge_config_from(string $path, string $key): void
    {
        $existing = config($key, []);
        $module_config = require $path;

        config([$key => array_replace_recursive($existing, $module_config)]);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        Blade::componentNamespace(config('modules.namespace').'\\' . $this->name . '\\View\\Components', $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }
}
