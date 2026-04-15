<?php

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BibleCommentaryEntry extends Model
{
    protected $table = 'bible_commentary_entries';

    protected $fillable = [
        'source_id',
        'book_number',
        'chapter_from',
        'verse_from',
        'chapter_to',
        'verse_to',
        'body',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<BibleCommentarySource, $this> */
    public function source(): BelongsTo
    {
        return $this->belongsTo(BibleCommentarySource::class, 'source_id');
    }

    public function coversVerse(int $bookNumber, int $chapter, int $verse): bool
    {
        if ($this->book_number !== $bookNumber) {
            return false;
        }

        $start = [$this->chapter_from, $this->verse_from];
        $end = [$this->chapter_to, $this->verse_to];
        $point = [$chapter, $verse];

        return $point >= $start && $point <= $end;
    }
}
