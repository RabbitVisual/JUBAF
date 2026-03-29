<?php

namespace Modules\Governance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OfficialCommunication extends Model
{
    protected $table = 'governance_official_communications';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'title', 'slug', 'summary', 'body', 'is_published', 'published_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (OfficialCommunication $row): void {
            if (empty($row->slug)) {
                $row->slug = Str::slug($row->title.'-'.Str::random(6));
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)->whereNotNull('published_at');
    }
}
