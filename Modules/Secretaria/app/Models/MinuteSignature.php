<?php

namespace Modules\Secretaria\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinuteSignature extends Model
{
    protected $table = 'secretaria_minute_signatures';

    protected $fillable = [
        'minute_id',
        'user_id',
        'role_at_the_time',
        'ip_address',
        'user_agent',
        'signed_at',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
        ];
    }

    public function minute(): BelongsTo
    {
        return $this->belongsTo(Minute::class, 'minute_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
