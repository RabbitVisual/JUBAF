<?php

namespace Modules\Governance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Assembly extends Model
{
    protected $table = 'governance_assemblies';

    protected $fillable = [
        'type', 'title', 'scheduled_at', 'location', 'event_id',
        'convocation_notes', 'convocation_sent_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'convocation_sent_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agendaItems(): HasMany
    {
        return $this->hasMany(AgendaItem::class, 'assembly_id')->orderBy('sort_order');
    }

    public function minute(): HasOne
    {
        return $this->hasOne(Minute::class, 'assembly_id');
    }
}
