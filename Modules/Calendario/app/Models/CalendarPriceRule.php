<?php

namespace Modules\Calendario\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarPriceRule extends Model
{
    public const TYPE_DISCOUNT_CODE = 'discount_code';

    public const TYPE_PERCENT_OFF = 'percent_off';

    public const TYPE_EARLY_BIRD = 'early_bird';

    public const TYPE_FIXED_PRICE = 'fixed_price';

    protected $table = 'evento_price_rules';

    protected $fillable = [
        'event_id',
        'event_batch_id',
        'rule_type',
        'priority',
        'is_active',
        'config',
    ];

    protected function casts(): array
    {
        return [
            'priority' => 'integer',
            'is_active' => 'boolean',
            'config' => 'array',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(CalendarEvent::class, 'event_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(CalendarEventBatch::class, 'event_batch_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority')->orderBy('id');
    }
}
