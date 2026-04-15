<?php

namespace Modules\Gateway\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GatewayWebhookEvent extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSED = 'processed';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'gateway_provider_account_id',
        'driver',
        'payload',
        'headers',
        'signature_valid',
        'processing_status',
        'error_message',
        'gateway_payment_id',
    ];

    protected function casts(): array
    {
        return [
            'headers' => 'array',
            'signature_valid' => 'boolean',
        ];
    }

    public function providerAccount(): BelongsTo
    {
        return $this->belongsTo(GatewayProviderAccount::class, 'gateway_provider_account_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(GatewayPayment::class, 'gateway_payment_id');
    }
}
