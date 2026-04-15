<?php

namespace Modules\Notificacoes\App\Events;

use Modules\Notificacoes\App\Models\Notificacao;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificacaoCriada implements ShouldBroadcast
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
        // Se a notificação é para um usuário específico, usar canal privado
        if ($this->notificacao->user_id) {
            return [
                new PrivateChannel('user.' . $this->notificacao->user_id),
            ];
        }

        // Se é para uma role específica, usar canal de role
        if ($this->notificacao->role) {
            return [
                new Channel('role.' . $this->notificacao->role),
            ];
        }

        // Se é para todos, usar canal público
        return [
            new Channel('notifications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'notificacao.criada';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notificacao->id,
            'type' => $this->notificacao->type,
            'title' => $this->notificacao->title,
            'message' => $this->notificacao->message,
            'action_url' => $this->notificacao->action_url,
            'module_source' => $this->notificacao->module_source,
            'entity_type' => $this->notificacao->entity_type,
            'entity_id' => $this->notificacao->entity_id,
            'created_at' => $this->notificacao->created_at->toIso8601String(),
            'created_at_human' => $this->notificacao->created_at->diffForHumans(),
            'type_color' => $this->notificacao->type_color,
            'type_icon' => $this->notificacao->type_icon,
            'is_read' => $this->notificacao->is_read,
        ];
    }
}

