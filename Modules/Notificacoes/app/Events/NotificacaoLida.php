<?php

namespace Modules\Notificacoes\App\Events;

use Modules\Notificacoes\App\Models\Notificacao;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificacaoLida implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacao $notificacao;

    /**
     * Create a new event instance.
     */
    public function __construct(Notificacao $notificacao)
    {
        $this->notificacao = $notificacao;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        if ($this->notificacao->user_id) {
            return [
                new PrivateChannel('user.' . $this->notificacao->user_id),
            ];
        }

        return [
            new Channel('notifications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'notificacao.lida';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notificacao->id,
            'is_read' => $this->notificacao->is_read,
            'read_at' => $this->notificacao->read_at?->toIso8601String(),
        ];
    }
}

