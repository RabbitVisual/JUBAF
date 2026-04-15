<?php

namespace Modules\Secretaria\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Igrejas\App\Models\Church;

class Minute extends Model
{
    protected $table = 'secretaria_minutes';

    protected $fillable = [
        'meeting_id',
        'church_id',
        'title',
        'protocol_number',
        'body',
        'executive_summary',
        'status',
        'created_by_id',
        'submitted_at',
        'approved_by_id',
        'approved_at',
        'published_at',
        'locked_at',
        'content_checksum',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'published_at' => 'datetime',
            'locked_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Minute $minute) {
            foreach ($minute->attachments as $attachment) {
                Storage::disk('local')->delete($attachment->path);
            }
        });
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MinuteAttachment::class, 'minute_id')->orderBy('sort_order');
    }

    public function scopePublishedForOperationalChurches(Builder $query, User $user): Builder
    {
        $ids = $user->churchIdsForSecretariaScope();

        return $query->where(function (Builder $q) use ($ids) {
            $q->whereNull('church_id');
            if ($ids !== []) {
                $q->orWhereIn('church_id', $ids);
            }
        });
    }

    /**
     * Atas publicadas visíveis para leitores operacionais (líder/jovem/pastor) sem âmbito diretoria.
     */
    public function isPublishedVisibleToChurchScopedUser(User $user): bool
    {
        if (! in_array($this->status, ['published', 'archived'], true)) {
            return false;
        }

        if ($this->church_id === null) {
            return true;
        }

        return in_array((int) $this->church_id, $user->churchIdsForSecretariaScope(), true);
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    public function isLocked(): bool
    {
        return $this->locked_at !== null
            || $this->status === 'published'
            || $this->status === 'archived';
    }

    public function computeContentChecksum(): string
    {
        $payload = (string) $this->title."\n".(string) $this->body."\n".($this->published_at?->toIso8601String() ?? '');

        return hash('sha256', $payload);
    }
}
