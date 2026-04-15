<?php

namespace Modules\Secretaria\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Convocation extends Model
{
    protected $table = 'secretaria_convocations';

    protected $fillable = [
        'title',
        'assembly_at',
        'notice_days',
        'body',
        'status',
        'meeting_id',
        'created_by_id',
        'approved_by_id',
        'approved_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'assembly_at' => 'datetime',
            'approved_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function noticeDeadline(): \Carbon\Carbon
    {
        return $this->assembly_at->copy()->subDays($this->notice_days);
    }
}
