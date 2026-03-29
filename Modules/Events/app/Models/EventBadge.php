<?php

namespace Modules\Events\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventBadge extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'template_html',
        'orientation',
        'paper_size',
        'badges_per_page',
        'is_active',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
