<?php

namespace Modules\Chat\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    protected $fillable = [
        'chat_session_id',
        'user_id',
        'sender_type',
        'message',
        'message_type',
        'attachment_path',
        'attachment_name',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relacionamentos
    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias para API dos painéis (mensagens de atendentes com user_id).
     */
    public function sender()
    {
        return $this->user();
    }

    // Accessors
    public function getSenderNameAttribute(): string
    {
        if ($this->sender_type === 'system') {
            return 'Sistema';
        }
        
        if ($this->sender_type === 'user' && $this->user) {
            return $this->user->name;
        }

        if ($this->sender_type === 'visitor' && $this->session) {
            return $this->session->visitor_name ?? 'Visitante';
        }

        return 'Desconhecido';
    }

    public function getFormattedMessageAttribute(): string
    {
        return nl2br(e($this->message));
    }

    // Métodos
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}

