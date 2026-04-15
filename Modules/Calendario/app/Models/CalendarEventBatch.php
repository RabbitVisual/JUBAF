<?php

namespace Modules\Calendario\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalendarEventBatch extends Model
{
    protected $table = 'calendar_event_batches';

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'sale_starts_at',
        'sale_ends_at',
        'capacity',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_starts_at' => 'datetime',
            'sale_ends_at' => 'datetime',
            'capacity' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(CalendarEvent::class, 'event_id');
    }

    public function priceRules(): HasMany
    {
        return $this->hasMany(CalendarPriceRule::class, 'event_batch_id');
    }

    public function isSaleOpen(?\Carbon\CarbonInterface $at = null): bool
    {
        $at = $at ?? now();
        if ($this->sale_starts_at && $at->lt($this->sale_starts_at)) {
            return false;
        }
        if ($this->sale_ends_at && $at->gt($this->sale_ends_at)) {
            return false;
        }

        return true;
    }
}
