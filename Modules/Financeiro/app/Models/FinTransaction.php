<?php

namespace Modules\Financeiro\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Modules\Igrejas\App\Models\Church;
use Modules\Secretaria\App\Models\Minute;

class FinTransaction extends Model
{
    public const SCOPE_REGIONAL = 'regional';

    public const SCOPE_CHURCH = 'igreja';

    /** @deprecated legado; migrado para {@see SCOPE_REGIONAL} */
    public const SCOPE_LEGACY_NACIONAL = 'nacional';

    public const SOURCE_MANUAL = 'manual';

    public const SOURCE_GATEWAY = 'gateway';

    public const SOURCE_ADJUSTMENT = 'adjustment';

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_OVERDUE = 'overdue';

    protected $table = 'fin_transactions';

    protected $fillable = [
        'uuid',
        'category_id',
        'bank_account_id',
        'occurred_on',
        'due_on',
        'paid_on',
        'amount',
        'direction',
        'scope',
        'church_id',
        'description',
        'reference',
        'source',
        'document_ref',
        'comprovante_path',
        'status',
        'reconciled',
        'is_extraordinary',
        'secretaria_minute_id',
        'evento_id',
        'metadata',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'occurred_on' => 'date',
            'due_on' => 'date',
            'paid_on' => 'date',
            'amount' => 'decimal:2',
            'metadata' => 'array',
            'reconciled' => 'boolean',
            'is_extraordinary' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (FinTransaction $tx): void {
            if (empty($tx->uuid)) {
                $tx->uuid = (string) Str::uuid();
            }
            if ($tx->status === null) {
                $tx->status = self::STATUS_PAID;
            }
            if ($tx->paid_on === null && $tx->status === self::STATUS_PAID && $tx->occurred_on) {
                $tx->paid_on = $tx->occurred_on;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinCategory::class, 'category_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(FinBankAccount::class, 'bank_account_id');
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function secretariaMinute(): BelongsTo
    {
        return $this->belongsTo(Minute::class, 'secretaria_minute_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PENDING);
    }

    public function scopeOverdue(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_OVERDUE);
    }

    public static function normalizeScopeLabel(?string $scope): string
    {
        $s = $scope === self::SCOPE_LEGACY_NACIONAL ? self::SCOPE_REGIONAL : (string) $scope;

        return match ($s) {
            self::SCOPE_REGIONAL => 'Regional (JUBAF)',
            self::SCOPE_CHURCH => 'Por igreja',
            default => (string) $scope,
        };
    }

    public function scopeLabel(): string
    {
        return self::normalizeScopeLabel($this->scope);
    }

    public function isFromGateway(): bool
    {
        if ($this->source === self::SOURCE_GATEWAY) {
            return true;
        }

        $meta = $this->metadata;

        return is_array($meta) && ! empty($meta['gateway_payment_id']);
    }

    public function isLocked(): bool
    {
        return $this->reconciled || $this->isFromGateway();
    }

    public static function sourceLabel(?string $source): string
    {
        return match ($source) {
            self::SOURCE_GATEWAY => 'Gateway (online)',
            self::SOURCE_ADJUSTMENT => 'Ajuste / estorno',
            default => 'Manual',
        };
    }

    public static function statusLabel(?string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_OVERDUE => 'Atrasado',
            self::STATUS_PAID => 'Pago',
            default => (string) $status,
        };
    }
}
