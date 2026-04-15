<?php

namespace Modules\Gateway\App\Services\Providers;

use Modules\Gateway\App\Models\GatewayProviderAccount;

class CoraParceriaDriver extends AbstractCoraDriver
{
    public function driverKey(): string
    {
        return GatewayProviderAccount::DRIVER_CORA_PARCERIA;
    }

    protected function defaultBaseUrl(): string
    {
        return 'https://api.stage.cora.com.br';
    }

    protected function httpOptions(GatewayProviderAccount $account): array
    {
        return [
            'timeout' => 60,
        ];
    }
}
