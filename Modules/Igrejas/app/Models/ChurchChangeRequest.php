<?php

namespace Modules\Igrejas\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChurchChangeRequest extends Model
{
    public const TYPE_CREATE = 'create';

    public const TYPE_UPDATE_PROFILE = 'update_profile';

    public const TYPE_LEADERSHIP_CHANGE = 'leadership_change';

    public const TYPE_DEACTIVATE = 'deactivate';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $table = 'igrejas_church_change_requests';

    protected $fillable = [
        'church_id',
        'type',
        'status',
        'payload',
        'submitted_by',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public static function types(): array
    {
        return [
            self::TYPE_CREATE,
            self::TYPE_UPDATE_PROFILE,
            self::TYPE_LEADERSHIP_CHANGE,
            self::TYPE_DEACTIVATE,
        ];
    }
}
