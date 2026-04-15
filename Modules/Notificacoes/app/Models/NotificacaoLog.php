<?php

namespace Modules\Notificacoes\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificacaoLog extends Model
{
    protected $table = 'notificacao_logs';

    protected $fillable = [
        'user_id',
        'channel',
        'message',
        'status',
        'response_payload',
    ];

    protected function casts(): array
    {
        return [
            'response_payload' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
