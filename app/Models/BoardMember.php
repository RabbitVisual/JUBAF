<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoardMember extends Model
{
    protected $fillable = [
        'full_name',
        'public_title',
        'group_label',
        'bio_short',
        'city',
        'state',
        'birth_date',
        'photo_path',
        'sort_order',
        'is_active',
        'mandate_year',
        'mandate_end',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'birth_date' => 'date',
            'mandate_end' => 'date',
            'sort_order' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActiveOrdered($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('full_name');
    }

    public function photoUrl(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        return asset('storage/'.$this->photo_path);
    }

    /** @deprecated use public_title; kept for Blade compatibility */
    public function getTitleDisplayAttribute(): string
    {
        return $this->public_title;
    }

    /** @deprecated use bio_short */
    public function getFormationAttribute(): ?string
    {
        return $this->bio_short;
    }

    public function publicAge(): ?int
    {
        if (! $this->birth_date) {
            return null;
        }

        return $this->birth_date->age;
    }

    public function resolvedLocation(): ?string
    {
        $parts = array_filter([$this->city, $this->state]);

        return $parts === [] ? null : implode(', ', $parts);
    }
}
