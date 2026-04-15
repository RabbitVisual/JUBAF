<?php

namespace Modules\Chat\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatConversationMessage extends Model
{
    protected $table = 'chat_conversation_messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'attachment_path',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
