<?php

namespace Modules\CoordinationCouncil\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CouncilMeeting extends Model
{
    protected $table = 'council_meetings';

    protected $fillable = [
        'scheduled_at', 'location', 'meeting_type', 'quorum_required',
        'quorum_actual', 'minutes_notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(CouncilAttendance::class, 'council_meeting_id');
    }
}
