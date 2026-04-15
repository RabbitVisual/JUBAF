<?php

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;

class BibleCrossReference extends Model
{
    protected $table = 'bible_cross_references';

    protected $fillable = [
        'testament',
        'from_book_number',
        'from_chapter',
        'from_verse',
        'to_book_number',
        'to_chapter',
        'to_verse',
        'kind',
        'weight',
        'source_slug',
        'note_pt',
    ];
}
