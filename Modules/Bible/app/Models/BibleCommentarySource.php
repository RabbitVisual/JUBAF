<?php

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BibleCommentarySource extends Model
{
    protected $table = 'bible_commentary_sources';

    protected $fillable = [
        'slug',
        'title',
        'language',
        'license_note',
        'url_template',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<BibleCommentaryEntry, $this> */
    public function entries(): HasMany
    {
        return $this->hasMany(BibleCommentaryEntry::class, 'source_id');
    }
}
