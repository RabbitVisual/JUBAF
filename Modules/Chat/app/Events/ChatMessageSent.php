<?php

namespace Modules\Chat\App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Chat\App\Models\ChatMessage;
use Modules\Chat\App\Models\ChatSession;

class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $session;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message, ChatSession $session)
    {
        $this->message = $message->load('user');
        $this->session = $session;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('chat.session.' . $this->session->id),
        ];

        // Se for mensagem de visitante, notificar atendentes
        if ($this->message->sender_type === 'visitor') {
            $channels[] = new Channel('chat.agents');
        }

        // Se for mensagem de atendente, notificar visitante via session_id
        if ($this->message->sender_type === 'user') {
            $channels[] = new Channel('chat.session.' . $this->session->session_id);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'chat_session_id' => $this->message->chat_session_id,
            'sender_type' => $this->message->sender_type,
            'message' => $this->message->message,
            'message_type' => $this->message->message_type,
            'created_at' => $this->message->created_at->toISOString(),
            'sender' => $this->message->user ? [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
            ] : null,
            'session' => [
                'id' => $this->session->id,
                'session_id' => $this->session->session_id,
                'status' => $this->session->status,
                'unread_count_user' => $this->session->unread_count_user,
                'unread_count_visitor' => $this->session->unread_count_visitor,
            ],
        ];
    }
}

