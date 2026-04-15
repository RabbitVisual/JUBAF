<?php

namespace Modules\Secretaria\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinuteAttachment extends Model
{
    public const KIND_ATTACHMENT = 'attachment';

    public const KIND_PREVIOUS_MINUTE = 'ata_anterior';

    public const KIND_OFFICE = 'oficio';

    protected $table = 'secretaria_minute_attachments';

    protected $fillable = [
        'minute_id',
        'related_minute_id',
        'path',
        'original_name',
        'mime',
        'size',
        'kind',
        'sort_order',
    ];

    public function minute(): BelongsTo
    {
        return $this->belongsTo(Minute::class, 'minute_id');
    }

    public function relatedMinute(): BelongsTo
    {
        return $this->belongsTo(Minute::class, 'related_minute_id');
    }
}
