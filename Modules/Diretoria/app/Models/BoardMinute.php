<?php

namespace Modules\Diretoria\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoardMinute extends Model
{
    protected $table = 'diretoria_board_minutes';

    public const TAG_REUNIAO_ORDINARIA = 'reuniao_ordinaria';

    public const TAG_ASSEMBLEIA = 'assembleia';

    public const TAG_CONSELHO = 'conselho';

    protected $fillable = [
        'title',
        'meeting_date',
        'tag',
        'pdf_path',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'meeting_date' => 'date',
        ];
    }

    public static function tagLabels(): array
    {
        return [
            self::TAG_REUNIAO_ORDINARIA => 'Reunião ordinária',
            self::TAG_ASSEMBLEIA => 'Assembleia',
            self::TAG_CONSELHO => 'Conselho',
        ];
    }

    public function getTagLabelAttribute(): string
    {
        return self::tagLabels()[$this->tag] ?? $this->tag;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
