<?php

namespace Modules\Chat\App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Chat\App\Models\ChatSession;

class ChatSessionUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatSession $session)
    {
        $this->session = $session->load(['assignedTo', 'lastMessage', 'user']);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('chat.session.' . $this->session->id),
            new Channel('chat.session.' . $this->session->session_id),
        ];

        // Notificar atendentes sobre mudanças
        $channels[] = new Channel('chat.agents');

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'session.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->session->id,
            'session_id' => $this->session->session_id,
            'status' => $this->session->status,
            'assigned_to' => $this->session->assigned_to,
            'assigned_to_name' => $this->session->assignedTo?->name,
            'unread_count_user' => $this->session->unread_count_user,
            'unread_count_visitor' => $this->session->unread_count_visitor,
            'last_activity_at' => $this->session->last_activity_at?->toISOString(),
            'last_message' => $this->session->lastMessage ? [
                'id' => $this->session->lastMessage->id,
                'message' => $this->session->lastMessage->message,
                'sender_type' => $this->session->lastMessage->sender_type,
                'created_at' => $this->session->lastMessage->created_at->toISOString(),
            ] : null,
        ];
    }
}

