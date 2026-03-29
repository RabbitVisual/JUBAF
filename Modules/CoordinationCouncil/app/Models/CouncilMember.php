<?php

namespace Modules\CoordinationCouncil\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CouncilMember extends Model
{
    protected $table = 'council_members';

    protected $fillable = [
        'user_id', 'full_name', 'email', 'phone', 'kind',
        'term_started_at', 'term_ended_at', 'mandate_third', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'term_started_at' => 'date',
            'term_ended_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(CouncilAttendance::class, 'council_member_id');
    }
}
