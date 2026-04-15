<?php

namespace Modules\Chat\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ChatSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chat_sessions';

    protected $fillable = [
        'session_id',
        'type',
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'visitor_cpf',
        'visitor_ip',
        'user_id',
        'assigned_to',
        'status',
        'unread_count_user',
        'unread_count_visitor',
        'last_activity_at',
        'closed_at',
        'notes',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    // Accessors
    public function getStatusTextoAttribute(): string
    {
        $status = [
            'waiting' => 'Aguardando',
            'active' => 'Em Atendimento',
            'closed' => 'Encerrada',
            'transferred' => 'Transferida',
        ];
        return $status[$this->status] ?? $this->status;
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' || $this->status === 'waiting';
    }

    public function getHasUnreadMessagesAttribute(): bool
    {
        return $this->unread_count_user > 0 || $this->unread_count_visitor > 0;
    }

    // Métodos
    public function markAsRead($userType = 'user')
    {
        if ($userType === 'user') {
            $this->update([
                'unread_count_user' => 0,
                'last_activity_at' => now(),
            ]);
            $this->messages()->where('sender_type', 'visitor')->update(['is_read' => true, 'read_at' => now()]);
        } else {
            $this->update([
                'unread_count_visitor' => 0,
                'last_activity_at' => now(),
            ]);
            $this->messages()->where('sender_type', 'user')->update(['is_read' => true, 'read_at' => now()]);
        }
    }

    public function incrementUnread($userType = 'visitor')
    {
        if ($userType === 'user') {
            $this->increment('unread_count_user');
        } else {
            $this->increment('unread_count_visitor');
        }
        $this->update(['last_activity_at' => now()]);
    }

    public function assignTo(User $user)
    {
        $this->update([
            'assigned_to' => $user->id,
            'status' => 'active',
        ]);
    }

    public function close()
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['waiting', 'active']);
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopePublic($query)
    {
        return $query->where('type', 'public');
    }

    public function scopeInternal($query)
    {
        return $query->where('type', 'internal');
    }

    /**
     * Verificar se existe sessão ativa para um CPF
     */
    public static function hasActiveSessionForCpf(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        return self::where('visitor_cpf', $cpf)
            ->whereIn('status', ['waiting', 'active'])
            ->exists();
    }

    /**
     * Obter sessão ativa para um CPF
     */
    public static function getActiveSessionForCpf(string $cpf): ?self
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        return self::where('visitor_cpf', $cpf)
            ->whereIn('status', ['waiting', 'active'])
            ->orderBy('created_at', 'desc')
            ->first();
    }
}

