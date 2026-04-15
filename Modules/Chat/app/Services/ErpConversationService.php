<?php

namespace Modules\Chat\App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Chat\App\Events\ConversationMessageCreated;
use Modules\Chat\App\Models\ChatConversation;
use Modules\Chat\App\Models\ChatConversationMessage;

final class ErpConversationService
{
    public function findOrCreateDirectDm(User $a, User $b): ChatConversation
    {
        return DB::transaction(function () use ($a, $b) {
            $candidates = ChatConversation::query()
                ->where('is_group', false)
                ->whereHas('participants', fn ($q) => $q->where('users.id', $a->id))
                ->whereHas('participants', fn ($q) => $q->where('users.id', $b->id))
                ->withCount('participants')
                ->get();

            $existing = $candidates->first(fn (ChatConversation $c) => (int) $c->participants_count === 2);

            if ($existing) {
                return $existing;
            }

            $c = ChatConversation::create([
                'is_group' => false,
                'created_by' => $a->id,
            ]);
            $c->participants()->attach([$a->id => [], $b->id => []]);

            return $c;
        });
    }

    public function appendMessage(ChatConversation $conversation, User $sender, string $body, ?string $attachmentPath = null): ChatConversationMessage
    {
        $message = $conversation->conversationMessages()->create([
            'sender_id' => $sender->id,
            'body' => $body,
            'attachment_path' => $attachmentPath,
        ]);

        $conversation->touch();

        event(new ConversationMessageCreated($message->load('sender')));

        return $message;
    }

    /**
     * @return Collection<int, ChatConversation>
     */
    public function listConversationsFor(User $user): Collection
    {
        return ChatConversation::query()
            ->whereHas('participants', fn ($q) => $q->where('users.id', $user->id))
            ->with([
                'participants',
                'conversationMessages' => fn ($q) => $q->latest('id')->limit(1),
            ])
            ->orderByDesc('updated_at')
            ->limit(50)
            ->get();
    }
}
