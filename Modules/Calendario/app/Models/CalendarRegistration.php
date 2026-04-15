<?php

namespace Modules\Calendario\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CalendarRegistration extends Model
{
    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_WAITLIST = 'waitlist';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_PENDING_PAYMENT = 'pending';

    protected $table = 'evento_inscricoes';

    protected $fillable = [
        'evento_id',
        'event_batch_id',
        'user_id',
        'status',
        'checked_in_at',
        'payment_status',
        'payment_id',
        'discount_code',
        'amount_charged',
        'custom_responses',
        'checkin_token',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
            'custom_responses' => 'array',
            'amount_charged' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (CalendarRegistration $r): void {
            if (empty($r->checkin_token)) {
                $r->checkin_token = Str::random(40);
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(CalendarEvent::class, 'evento_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(CalendarEventBatch::class, 'event_batch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gatewayPayment(): BelongsTo
    {
        return $this->belongsTo(\Modules\Gateway\App\Models\GatewayPayment::class, 'payment_id');
    }

    public function getEventIdAttribute(): mixed
    {
        return $this->evento_id;
    }

    public function setEventIdAttribute(mixed $value): void
    {
        $this->attributes['evento_id'] = $value;
    }

    public function getGatewayPaymentIdAttribute(): mixed
    {
        return $this->payment_id;
    }

    public function setGatewayPaymentIdAttribute(mixed $value): void
    {
        $this->attributes['payment_id'] = $value;
    }
}
