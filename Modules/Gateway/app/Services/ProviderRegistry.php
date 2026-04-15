<?php

namespace Modules\Gateway\App\Services;

use InvalidArgumentException;
use Modules\Gateway\App\Models\GatewayProviderAccount;
use Modules\Gateway\App\Services\Contracts\PaymentProviderContract;
use Modules\Gateway\App\Services\Providers\CoraMtlsDriver;
use Modules\Gateway\App\Services\Providers\CoraParceriaDriver;
use Modules\Gateway\App\Services\Providers\MercadoPagoDriver;
use Modules\Gateway\App\Services\Providers\PagarMeDriver;
use Modules\Gateway\App\Services\Providers\StripeDriver;

class ProviderRegistry
{
    /** @var array<string, PaymentProviderContract> */
    private array $drivers = [];

    public function __construct(
        CoraParceriaDriver $coraParceria,
        CoraMtlsDriver $coraMtls,
        MercadoPagoDriver $mercadoPago,
        StripeDriver $stripe,
        PagarMeDriver $pagarme,
    ) {
        foreach ([$coraParceria, $coraMtls, $mercadoPago, $stripe, $pagarme] as $driver) {
            $this->drivers[$driver->driverKey()] = $driver;
        }
    }

    public function get(string $driverKey): PaymentProviderContract
    {
        if (! isset($this->drivers[$driverKey])) {
            throw new InvalidArgumentException("Gateway driver [{$driverKey}] não registado.");
        }

        return $this->drivers[$driverKey];
    }

    public function forAccount(GatewayProviderAccount $account): PaymentProviderContract
    {
        return $this->get($account->driver);
    }
}
