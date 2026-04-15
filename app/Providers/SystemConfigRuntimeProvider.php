<?php

namespace App\Providers;

use App\Models\SystemConfig;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Events\RouteMatched;

class SystemConfigRuntimeProvider extends ServiceProvider
{
    /**
     * Map DB system_configs keys to Laravel config() keys.
     */
    private array $mailKeys = [
        'mail.mailer' => 'mail.default',
        'mail.host' => 'mail.mailers.smtp.host',
        'mail.port' => 'mail.mailers.smtp.port',
        'mail.username' => 'mail.mailers.smtp.username',
        'mail.password' => 'mail.mailers.smtp.password',
        'mail.encryption' => 'mail.mailers.smtp.encryption',
        'mail.from_address' => 'mail.from.address',
        'mail.from_name' => 'mail.from.name',
    ];

    /**
     * Broadcasting (Pusher) keys to Laravel config() keys.
     */
    private array $broadcastKeys = [
        'broadcast.driver' => 'broadcasting.default',
        'pusher.app_id' => 'broadcasting.connections.pusher.app_id',
        'pusher.app_key' => 'broadcasting.connections.pusher.key',
        'pusher.app_secret' => 'broadcasting.connections.pusher.secret',
        'pusher.cluster' => 'broadcasting.connections.pusher.options.cluster',
        'pusher.port' => 'broadcasting.connections.pusher.options.port',
        'pusher.scheme' => 'broadcasting.connections.pusher.options.scheme',
        'pusher.host' => 'broadcasting.connections.pusher.options.host',
    ];

    public function boot(): void
    {
        // Apply overrides on every request before controller execution.
        // This is what makes admin changes work "immediately" in production.
        $this->app['events']->listen(RouteMatched::class, function () {
            $this->applyFromDatabase();
        });
    }

    private function applyFromDatabase(): void
    {
        $keys = array_merge(
            array_keys($this->mailKeys),
            array_keys($this->broadcastKeys),
        );

        $configs = SystemConfig::query()
            ->whereIn('key', $keys)
            ->get()
            ->keyBy('key');

        if ($configs->isEmpty()) {
            return;
        }

        foreach ($this->mailKeys as $dbKey => $configKey) {
            if (! $configs->has($dbKey)) {
                continue;
            }

            $value = $this->castSystemConfigValue($configs->get($dbKey));
            config()->set($configKey, $value);
        }

        foreach ($this->broadcastKeys as $dbKey => $configKey) {
            if (! $configs->has($dbKey)) {
                continue;
            }

            $value = $this->castSystemConfigValue($configs->get($dbKey));

            // Avoid breaking defaults with empty host/scheme/port.
            if (in_array($dbKey, ['pusher.host', 'pusher.scheme'], true) && $value === '') {
                continue;
            }
            if ($dbKey === 'pusher.port' && ($value === '' || (int) $value <= 0)) {
                continue;
            }

            config()->set($configKey, $value);
        }

    }

    private function castSystemConfigValue(SystemConfig $config)
    {
        return match ($config->type) {
            'integer' => (int) $config->value,
            'boolean' => filter_var((string) $config->value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode((string) $config->value, true),
            default => (string) $config->value,
        };
    }
}

