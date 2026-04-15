<?php

namespace Modules\Secretaria\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    protected $table = 'secretaria_meetings';

    protected $fillable = [
        'type',
        'title',
        'starts_at',
        'ends_at',
        'location',
        'status',
        'notes',
        'created_by_id',
        'calendar_event_id',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function minutes(): HasMany
    {
        return $this->hasMany(Minute::class, 'meeting_id');
    }

    public function convocations(): HasMany
    {
        return $this->hasMany(Convocation::class, 'meeting_id');
    }

    public function calendarEvent(): BelongsTo
    {
        return $this->belongsTo(\Modules\Calendario\App\Models\CalendarEvent::class, 'calendar_event_id');
    }
}
