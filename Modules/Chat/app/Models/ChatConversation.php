<?php

namespace Modules\Chat\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ChatConversation extends Model
{
    protected $table = 'chat_conversations';

    protected $fillable = [
        'uuid',
        'is_group',
        'name',
        'created_by',
        'whatsapp_remote_jid',
    ];

    protected function casts(): array
    {
        return [
            'is_group' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ChatConversation $c): void {
            if (empty($c->uuid)) {
                $c->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_conversation_user', 'conversation_id', 'user_id')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    /**
     * @return HasMany<ChatConversationMessage, $this>
     */
    public function conversationMessages(): HasMany
    {
        return $this->hasMany(ChatConversationMessage::class, 'conversation_id');
    }

    public function hasParticipant(User $user): bool
    {
        return $this->participants()->where('users.id', $user->id)->exists();
    }
}
