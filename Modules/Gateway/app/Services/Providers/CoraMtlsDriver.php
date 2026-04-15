<?php

namespace Modules\Gateway\App\Services\Providers;

use Modules\Gateway\App\Models\GatewayProviderAccount;

class CoraMtlsDriver extends AbstractCoraDriver
{
    public function driverKey(): string
    {
        return GatewayProviderAccount::DRIVER_CORA_MTLS;
    }

    protected function defaultBaseUrl(): string
    {
        return 'https://matls-clients.api.stage.cora.com.br';
    }

    protected function httpOptions(GatewayProviderAccount $account): array
    {
        $c = $account->credentials;
        $opts = [
            'timeout' => 60,
        ];
        if (! empty($c['mtls_cert_path']) && ! empty($c['mtls_key_path'])) {
            $opts['cert'] = [$c['mtls_cert_path'], $c['mtls_key_path'] ?? null];
        }

        return $opts;
    }
}
