<?php

namespace Modules\Gateway\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Modules\Financeiro\App\Models\FinTransaction;

class GatewayPayment extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_FAILED = 'failed';

    public const STATUS_REFUNDED = 'refunded';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'uuid',
        'gateway_provider_account_id',
        'driver',
        'provider_reference',
        'amount',
        'currency',
        'status',
        'payable_type',
        'payable_id',
        'idempotency_key',
        'checkout_url',
        'client_secret',
        'raw_last_payload',
        'paid_at',
        'failure_reason',
        'fin_transaction_id',
        'user_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'raw_last_payload' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (GatewayPayment $payment): void {
            if (empty($payment->uuid)) {
                $payment->uuid = (string) Str::uuid();
            }
            if (empty($payment->idempotency_key)) {
                $payment->idempotency_key = (string) Str::uuid();
            }
        });
    }

    public function providerAccount(): BelongsTo
    {
        return $this->belongsTo(GatewayProviderAccount::class, 'gateway_provider_account_id');
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function finTransaction(): BelongsTo
    {
        return $this->belongsTo(FinTransaction::class, 'fin_transaction_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_PAID => 'Pago',
            self::STATUS_FAILED => 'Falhou',
            self::STATUS_REFUNDED => 'Reembolsado',
            self::STATUS_CANCELLED => 'Cancelado',
            default => $this->status,
        };
    }
}
