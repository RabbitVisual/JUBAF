<?php

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;

class BibleInterlinearToken extends Model
{
    protected $table = 'bible_interlinear_tokens';

    protected $fillable = [
        'testament',
        'book_number',
        'chapter_number',
        'verse_number',
        'token_index',
        'surface_text',
        'strongs_key',
        'strongs_raw',
        'morphology',
    ];

    public function scopeForChapter($query, string $testament, int $bookNumber, int $chapterNumber)
    {
        return $query->where('testament', $testament)
            ->where('book_number', $bookNumber)
            ->where('chapter_number', $chapterNumber)
            ->orderBy('verse_number')
            ->orderBy('token_index');
    }
}
