<?php

namespace Modules\Gateway\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GatewayProviderAccount extends Model
{
    public const DRIVER_CORA_PARCERIA = 'cora_parceria';

    public const DRIVER_CORA_MTLS = 'cora_mtls';

    public const DRIVER_MERCADOPAGO = 'mercadopago';

    public const DRIVER_STRIPE = 'stripe';

    public const DRIVER_PAGARME = 'pagarme';

    protected $fillable = [
        'name',
        'driver',
        'is_enabled',
        'is_default',
        'credentials',
        'base_url',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'is_default' => 'boolean',
            'credentials' => 'encrypted:array',
            'metadata' => 'array',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(GatewayPayment::class, 'gateway_provider_account_id');
    }

    public static function drivers(): array
    {
        return [
            self::DRIVER_CORA_PARCERIA => 'Cora (Parceria API)',
            self::DRIVER_CORA_MTLS => 'Cora (Integração direta / mTLS)',
            self::DRIVER_MERCADOPAGO => 'Mercado Pago',
            self::DRIVER_STRIPE => 'Stripe',
            self::DRIVER_PAGARME => 'Pagar.me',
        ];
    }
}
