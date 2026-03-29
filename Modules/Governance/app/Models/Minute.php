<?php

namespace Modules\Governance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Minute extends Model
{
    protected $table = 'governance_minutes';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'assembly_id', 'slug', 'status', 'body', 'pdf_path', 'published_at',
        'president_signed_at', 'secretary_signed_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'president_signed_at' => 'datetime',
            'secretary_signed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Minute $minute): void {
            if (empty($minute->slug)) {
                $minute->slug = Str::slug('ata-'.Str::random(8).'-'.now()->format('Y-m-d'));
            }
        });
    }

    public function assembly(): BelongsTo
    {
        return $this->belongsTo(Assembly::class, 'assembly_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'published')->whereNotNull('published_at');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at !== null;
    }
}
