<?php

namespace Modules\Chat\App\Policies;

use App\Models\User;
use Modules\Chat\App\Models\ChatConversation;

class ChatConversationPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, ChatConversation $conversation): bool
    {
        return $conversation->hasParticipant($user);
    }

    public function sendMessage(User $user, ChatConversation $conversation): bool
    {
        return $conversation->hasParticipant($user);
    }
}
