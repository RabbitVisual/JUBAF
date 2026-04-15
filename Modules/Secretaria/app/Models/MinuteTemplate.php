<?php

namespace Modules\Secretaria\App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MinuteTemplate extends Model
{
    protected $table = 'secretaria_minute_templates';

    protected $fillable = [
        'slug',
        'title',
        'body',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }
}
