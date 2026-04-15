<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileSensitiveDataRequest extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const FIELD_EMAIL = 'email';

    public const FIELD_CPF = 'cpf';

    protected $fillable = [
        'user_id',
        'field',
        'previous_value',
        'requested_value',
        'reason',
        'status',
        'reviewed_by_user_id',
        'reviewed_at',
        'reviewer_note',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public static function fieldLabel(string $field): string
    {
        return match ($field) {
            self::FIELD_EMAIL => 'E-mail',
            self::FIELD_CPF => 'CPF',
            default => $field,
        };
    }
}
