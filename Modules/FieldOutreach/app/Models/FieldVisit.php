<?php

namespace Modules\FieldOutreach\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Church\Models\Church;

class FieldVisit extends Model
{
    protected $table = 'field_visits';

    protected $fillable = ['church_id', 'visited_at', 'notes', 'next_steps', 'created_by'];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'field_visit_attendee', 'field_visit_id', 'user_id')
            ->withTimestamps();
    }
}
