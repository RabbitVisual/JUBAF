<?php

namespace Modules\Notificacoes\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ChecksModuleEnabled;
use Modules\Notificacoes\App\Models\Notificacao;
use Modules\Notificacoes\App\Services\NotificacaoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificacoesApiController extends Controller
{
    use ChecksModuleEnabled;

    protected NotificacaoService $service;

    public function __construct(NotificacaoService $service)
    {
        $this->ensureModuleEnabled('Notificacoes');
        $this->service = $service;
    }

    /**
     * Get unread notifications for authenticated user
     */
    public function unread(Request $request): JsonResponse
    {
        try {
            // Verificar se o usuário está autenticado antes de qualquer operação
            if (!auth()->check()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                ]);
            }

            $userId = auth()->id();

            if (!$userId) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                ]);
            }

            $limit = $request->get('limit', 10);
            $panel = $request->get('panel');
            $notifications = $this->service->getUserNotifications(
                $userId,
                $limit,
                true,
                $panel
            );

            return response()->json([
                'success' => true,
                'data' => $notifications->map(function($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'action_url' => $notification->action_url,
                        'module_source' => $notification->module_source,
                        'created_at' => $notification->created_at->format('d/m/Y H:i'),
                        'created_at_human' => $notification->created_at->diffForHumans(),
                        'type_color' => $notification->type_color,
                        'type_icon' => $notification->type_icon,
                    ];
                }),
            ]);
        } catch (\Illuminate\Encryption\MissingAppKeyException $e) {
            // Erro específico de APP_KEY ausente
            \Log::warning('APP_KEY não configurado ao buscar notificações não lidas', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar notificações não lidas: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }
    }

    /**
     * Get unread count for authenticated user
     */
    public function count(): JsonResponse
    {
        try {
            // Verificar se o usuário está autenticado antes de qualquer operação
            if (!auth()->check()) {
                return response()->json([
                    'success' => true,
                    'count' => 0,
                ]);
            }

            $userId = auth()->id();

            if (!$userId) {
                return response()->json([
                    'success' => true,
                    'count' => 0,
                ]);
            }

            $panel = request()->get('panel');
            $count = $this->service->getUnreadCount($userId, $panel);

            return response()->json([
                'success' => true,
                'count' => $count,
            ]);
        } catch (\Illuminate\Encryption\MissingAppKeyException $e) {
            // Erro específico de APP_KEY ausente
            \Log::warning('APP_KEY não configurado ao buscar contagem de notificações', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => true,
                'count' => 0,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar contagem de notificações: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => true,
                'count' => 0,
            ]);
        }
    }

    /**
     * Get all notifications for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Verificar se o usuário está autenticado antes de qualquer operação
            if (!auth()->check()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                ]);
            }

            $userId = auth()->id();

            if (!$userId) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                ]);
            }

            $limit = $request->get('limit', 20);
            $unreadOnly = $request->get('unread_only', false);
            $panel = $request->get('panel');

            $notifications = $this->service->getUserNotifications(
                $userId,
                $limit,
                $unreadOnly,
                $panel
            );

            return response()->json([
                'success' => true,
                'data' => $notifications->map(function($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'action_url' => $notification->action_url,
                        'module_source' => $notification->module_source,
                        'is_read' => $notification->is_read,
                        'read_at' => $notification->read_at?->format('d/m/Y H:i'),
                        'created_at' => $notification->created_at->format('d/m/Y H:i'),
                        'created_at_human' => $notification->created_at->diffForHumans(),
                        'type_color' => $notification->type_color,
                        'type_icon' => $notification->type_icon,
                    ];
                }),
            ]);
        } catch (\Illuminate\Encryption\MissingAppKeyException $e) {
            // Erro específico de APP_KEY ausente
            \Log::warning('APP_KEY não configurado ao buscar notificações', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar notificações: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id): JsonResponse
    {
        try {
            $success = $this->service->markAsRead($id, auth()->id());

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notificação não encontrada ou sem permissão',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Notificação marcada como lida',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar notificação: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        try {
            $count = $this->service->markAllAsRead(auth()->id());

            return response()->json([
                'success' => true,
                'message' => "{$count} notificações marcadas como lidas",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao marcar notificações: ' . $e->getMessage(),
            ], 500);
        }
    }
}
