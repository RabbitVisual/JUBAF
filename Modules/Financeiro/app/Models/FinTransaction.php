<?php

namespace Modules\Financeiro\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $table = 'fin_transactions';

    protected $fillable = [
        'category_id',
        'occurred_on',
        'amount',
        'direction',
        'scope',
        'church_id',
        'description',
        'reference',
        'source',
        'document_ref',
        'secretaria_minute_id',
        'calendar_event_id',
        'metadata',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'occurred_on' => 'date',
            'amount' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinCategory::class, 'category_id');
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

    public static function sourceLabel(?string $source): string
    {
        return match ($source) {
            self::SOURCE_GATEWAY => 'Gateway (online)',
            self::SOURCE_ADJUSTMENT => 'Ajuste / estorno',
            default => 'Manual',
        };
    }
}
