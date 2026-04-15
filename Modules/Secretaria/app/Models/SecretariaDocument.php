<?php

namespace Modules\Secretaria\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Igrejas\App\Models\Church;

class SecretariaDocument extends Model
{
    protected $table = 'secretaria_ged_documents';

    protected $fillable = [
        'uuid',
        'title',
        'category',
        'file_path',
        'igreja_id',
        'is_public',
        'uploaded_by_id',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class, 'igreja_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }

    public function getPathAttribute(): string
    {
        return (string) ($this->attributes['path'] ?? $this->attributes['file_path'] ?? '');
    }

    public function setPathAttribute(string $value): void
    {
        $this->attributes['file_path'] = $value;
    }

    public function getChurchIdAttribute(): ?int
    {
        $value = $this->attributes['church_id'] ?? $this->attributes['igreja_id'] ?? null;

        return $value !== null ? (int) $value : null;
    }

    public function setChurchIdAttribute(?int $value): void
    {
        $this->attributes['igreja_id'] = $value;
    }

    public function getVisibilityAttribute(): string
    {
        return $this->is_public ? 'public' : 'directorate';
    }
}
