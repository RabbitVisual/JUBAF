<?php

namespace Modules\Chat\App\Services;

use Modules\Chat\App\Models\ChatSession;
use Modules\Chat\App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ChatService
{
    /**
     * Enviar mensagem
     */
    public function sendMessage(ChatSession $session, string $message, string $senderType, ?User $user = null, array $options = []): ChatMessage
    {
        DB::beginTransaction();
        try {
            $chatMessage = ChatMessage::create([
                'chat_session_id' => $session->id,
                'user_id' => $user?->id,
                'sender_type' => $senderType,
                'message' => $message,
                'message_type' => $options['message_type'] ?? 'text',
                'attachment_path' => $options['attachment_path'] ?? null,
                'attachment_name' => $options['attachment_name'] ?? null,
                'is_read' => false,
            ]);

            // Atualizar contadores de não lidas
            if ($senderType === 'visitor') {
                $session->incrementUnread('user');
            } elseif ($senderType === 'user') {
                $session->incrementUnread('visitor');
            }

            // Atualizar status se estava aguardando e atendente respondeu
            if ($session->status === 'waiting' && $senderType === 'user') {
                $session->update(['status' => 'active']);
            }

            // Atualizar última atividade
            $session->update(['last_activity_at' => now()]);

            // Limpar cache de sessões
            $this->clearSessionCache($session);

            // Notificar via sistema interno
            $this->notifyNewMessage($session, $chatMessage, $senderType);

            DB::commit();
            return $chatMessage->fresh(['user', 'session']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao enviar mensagem de chat', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Notificar sobre nova mensagem
     */
    protected function notifyNewMessage(ChatSession $session, ChatMessage $message, string $senderType): void
    {
        try {
            if ($session->type !== 'public') {
                return;
            }

            // Se for mensagem de visitante, notificar atendentes
            if ($senderType === 'visitor') {
                if ($session->assigned_to) {
                    $this->notifyAgent($session, $message);
                } else {
                    $this->notifyAvailableAgents($session, $message);
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao notificar sobre mensagem', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notificar atendente atribuído
     */
    protected function notifyAgent(ChatSession $session, ChatMessage $message): void
    {
        if (!$session->assigned_to) {
            return;
        }

        try {
            // Usar sistema de notificações interno se disponível
            if (class_exists(\Modules\Notificacoes\App\Services\NotificacaoService::class)) {
                $notificationService = app(\Modules\Notificacoes\App\Services\NotificacaoService::class);
                $notificationService->sendToUser(
                    $session->assigned_to,
                    'info',
                    'Nova Mensagem no Chat',
                    "Nova mensagem de {$session->visitor_name}",
                    route('diretoria.chat.show', $session->id),
                    [
                        'session_id' => $session->id,
                        'message_id' => $message->id,
                    ],
                    'Chat',
                    'ChatSession',
                    $session->id,
                    false,
                    'diretoria'
                );
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação para atendente', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notificar atendentes disponíveis
     */
    protected function notifyAvailableAgents(ChatSession $session, ChatMessage $message): void
    {
        try {
            $agents = User::whereHas('roles', function ($q) {
                $q->whereIn('name', jubaf_chat_agent_role_names());
            })->get();

            if (class_exists(\Modules\Notificacoes\App\Services\NotificacaoService::class)) {
                $notificationService = app(\Modules\Notificacoes\App\Services\NotificacaoService::class);

                foreach ($agents as $agent) {
                    $notificationService->sendToUser(
                        $agent->id,
                        'alert',
                        'Nova Sessão de Chat',
                        "Nova sessão de chat de {$session->visitor_name} aguardando atendimento",
                        route('diretoria.chat.show', $session->id),
                        [
                            'session_id' => $session->id,
                            'message_id' => $message->id,
                        ],
                        'Chat',
                        'ChatSession',
                        $session->id,
                        false,
                        'diretoria'
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao notificar atendentes disponíveis', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Atribuir sessão a um atendente
     */
    public function assignSession(ChatSession $session, User $agent): ChatSession
    {
        $session->assignTo($agent);

        // Criar mensagem do sistema
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender_type' => 'system',
            'message' => "Atendimento atribuído para {$agent->name}",
            'message_type' => 'system',
        ]);

        $this->clearSessionCache($session);

        return $session->fresh();
    }

    /**
     * Transferir sessão para outro atendente
     */
    public function transferSession(ChatSession $session, User $newAgent, ?string $reason = null): ChatSession
    {
        $oldAgentName = $session->assignedTo?->name ?? 'Não atribuído';

        $session->update([
            'assigned_to' => $newAgent->id,
            'status' => 'active',
        ]);

        // Criar mensagem do sistema
        $message = "Atendimento transferido de {$oldAgentName} para {$newAgent->name}";
        if ($reason) {
            $message .= ". Motivo: {$reason}";
        }

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender_type' => 'system',
            'message' => $message,
            'message_type' => 'system',
        ]);

        $this->clearSessionCache($session);

        return $session->fresh();
    }

    /**
     * Fechar sessão
     */
    public function closeSession(ChatSession $session, ?string $reason = null, ?User $closedBy = null): ChatSession
    {
        $session->close();

        // Criar mensagem do sistema
        $message = $reason ?? 'Sessão encerrada.';
        if ($closedBy) {
            $message = "Sessão encerrada por {$closedBy->name}. " . ($reason ?? '');
        }

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender_type' => 'system',
            'message' => $message,
            'message_type' => 'system',
        ]);

        $this->clearSessionCache($session);

        return $session->fresh();
    }

    /**
     * Reabrir sessão
     */
    public function reopenSession(ChatSession $session, ?User $reopenedBy = null): ChatSession
    {
        $session->update([
            'status' => 'active',
            'closed_at' => null,
        ]);

        $message = 'Sessão reaberta.';
        if ($reopenedBy) {
            $message = "Sessão reaberta por {$reopenedBy->name}.";
        }

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender_type' => 'system',
            'message' => $message,
            'message_type' => 'system',
        ]);

        $this->clearSessionCache($session);

        return $session->fresh();
    }

    /**
     * Marcar mensagens como lidas
     */
    public function markAsRead(ChatSession $session, string $userType = 'user'): void
    {
        $session->markAsRead($userType);
        $this->clearSessionCache($session);
    }

    /**
     * Obter sessões ativas para um atendente
     */
    public function getActiveSessionsForAgent(?User $agent = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = ChatSession::with(['lastMessage', 'assignedTo', 'user'])
            ->whereIn('status', ['waiting', 'active']);

        if ($agent) {
            $query->where(function ($q) use ($agent) {
                $q->where('assigned_to', $agent->id)
                  ->orWhereNull('assigned_to');
            });
        }

        return $query->orderByRaw("CASE WHEN status = 'waiting' THEN 0 ELSE 1 END")
            ->orderBy('last_activity_at', 'desc')
            ->get();
    }

    /**
     * Obter mensagens de uma sessão para API
     */
    public function getMessagesForApi(ChatSession $session, int $lastId = 0): array
    {
        $messages = $session->messages()
            ->with('user')
            ->when($lastId > 0, fn($q) => $q->where('id', '>', $lastId))
            ->orderBy('created_at', 'asc')
            ->get();

        return $messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'message' => $msg->message,
                'message_type' => $msg->message_type,
                'created_at' => $msg->created_at->toISOString(),
                'is_read' => $msg->is_read,
                'sender' => $msg->user ? [
                    'id' => $msg->user->id,
                    'name' => $msg->user->name,
                ] : null,
            ];
        })->toArray();
    }

    /**
     * Obter estatísticas de chat
     */
    public function getStatistics(): array
    {
        return Cache::remember('chat_statistics', 60, function () {
            return [
                'total' => ChatSession::count(),
                'waiting' => ChatSession::where('status', 'waiting')->count(),
                'active' => ChatSession::where('status', 'active')->count(),
                'closed' => ChatSession::where('status', 'closed')->count(),
                'unassigned' => ChatSession::where('status', 'waiting')->whereNull('assigned_to')->count(),
                'total_messages' => ChatMessage::count(),
                'messages_today' => ChatMessage::whereDate('created_at', today())->count(),
                'sessions_today' => ChatSession::whereDate('created_at', today())->count(),
                'avg_response_time' => $this->calculateAverageResponseTime(),
            ];
        });
    }

    /**
     * Calcular tempo médio de resposta
     */
    protected function calculateAverageResponseTime(): ?string
    {
        try {
            // Obter sessões dos últimos 30 dias que foram atendidas
            $sessions = ChatSession::where('status', '!=', 'waiting')
                ->whereNotNull('assigned_to')
                ->where('created_at', '>=', now()->subDays(30))
                ->get();

            if ($sessions->isEmpty()) {
                return null;
            }

            $totalMinutes = 0;
            $count = 0;

            foreach ($sessions as $session) {
                // Primeira mensagem do atendente
                $firstAgentMessage = $session->messages()
                    ->where('sender_type', 'user')
                    ->orderBy('created_at', 'asc')
                    ->first();

                if ($firstAgentMessage) {
                    $diff = $session->created_at->diffInMinutes($firstAgentMessage->created_at);
                    $totalMinutes += $diff;
                    $count++;
                }
            }

            if ($count === 0) {
                return null;
            }

            $avgMinutes = round($totalMinutes / $count);

            if ($avgMinutes < 60) {
                return "{$avgMinutes} min";
            }

            $hours = floor($avgMinutes / 60);
            $minutes = $avgMinutes % 60;
            return "{$hours}h {$minutes}min";
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Limpar cache de sessão
     */
    protected function clearSessionCache(ChatSession $session): void
    {
        Cache::forget('chat_statistics');
        Cache::forget("chat_session_{$session->id}");
        Cache::forget("chat_session_uuid_{$session->session_id}");
    }

    /**
     * Obter sessões para listagem com filtros
     */
    public function getSessionsWithFilters(array $filters = [], int $perPage = 20)
    {
        $query = ChatSession::with(['assignedTo', 'user', 'lastMessage']);

        // Filtros
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('visitor_name', 'like', "%{$search}%")
                  ->orWhere('visitor_email', 'like', "%{$search}%")
                  ->orWhere('visitor_cpf', 'like', "%{$search}%")
                  ->orWhere('visitor_phone', 'like', "%{$search}%")
                  ->orWhere('session_id', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderByRaw("CASE
            WHEN status = 'waiting' THEN 0
            WHEN status = 'active' THEN 1
            ELSE 2
        END")
        ->orderBy('last_activity_at', 'desc')
        ->paginate($perPage);
    }

    /**
     * Verificar se usuário pode acessar sessão
     */
    public function canAccessSession(ChatSession $session, User $user): bool
    {
        if ($user->hasAnyRole(\App\Support\JubafRoleRegistry::superAdminRoleNames())) {
            return true;
        }

        $directorate = array_merge(
            \App\Support\JubafRoleRegistry::directorateRoleNames(),
            array_filter([\App\Support\JubafRoleRegistry::legacyCoAdminName()])
        );

        if ($user->hasAnyRole($directorate)) {
            if ($session->type === 'public') {
                return true;
            }

            return $session->assigned_to === $user->id || $session->user_id === $user->id;
        }

        if ($user->hasRole('lider')) {
            return $session->type === 'internal' &&
                ($session->user_id === $user->id || $session->assigned_to === $user->id);
        }

        return false;
    }

    /**
     * Obter TODAS as sessões para atendentes (sem filtro de atribuição)
     * Inclui sessões ativas e fechadas recentemente (últimas 24h)
     */
    public function getAllSessionsForAgent(?User $agent = null, bool $includeRecent = true): \Illuminate\Database\Eloquent\Collection
    {
        $query = ChatSession::with(['lastMessage', 'assignedTo', 'user'])
            ->where('type', 'public'); // Apenas sessões públicas para atendentes

        if ($includeRecent) {
            // Incluir sessões ativas OU fechadas nos últimos 30 dias
            $query->where(function ($q) {
                $q->whereIn('status', ['waiting', 'active'])
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'closed')
                         ->where('closed_at', '>=', now()->subDays(30));
                  });
            });
        } else {
            $query->whereIn('status', ['waiting', 'active']);
        }

        return $query->orderByRaw("CASE
            WHEN status = 'waiting' THEN 0
            WHEN status = 'active' THEN 1
            ELSE 2
        END")
            ->orderBy('last_activity_at', 'desc')
            ->get();
    }
}
