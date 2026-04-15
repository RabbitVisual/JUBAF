<?php

namespace Modules\Chat\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Chat\App\Models\ChatSession;
use Modules\Chat\App\Models\ChatMessage;
use Modules\Chat\App\Services\ChatService;
use Modules\Chat\App\Events\UserTyping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatApiController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function sendMessage(Request $request, $sessionId)
    {
        // Verificar autenticação
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autenticado.',
            ], 401);
        }

        $session = ChatSession::findOrFail($sessionId);

        // Verificar se usuário pode enviar mensagem nesta sessão
        if (Auth::user()->id !== $session->assigned_to && ! Auth::user()->hasAnyRole(jubaf_chat_agent_role_names())) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para enviar mensagens nesta sessão.',
            ], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        try {
            $message = $this->chatService->sendMessage(
                $session,
                $validated['message'],
                'user',
                Auth::user()
            );

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'sender_type' => $message->sender_type,
                    'message' => $message->message,
                    'created_at' => $message->created_at->toISOString(),
                    'sender' => $message->user ? [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                    ] : null,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar mensagem: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function sendTypingIndicator(Request $request, $sessionId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $session = ChatSession::findOrFail($sessionId);
        $isTyping = $request->input('is_typing', true);

        // Disparar evento de digitação
        broadcast(new UserTyping(
            $session->session_id,
            Auth::id(),
            Auth::user()->name,
            $isTyping
        ))->toOthers();

        return response()->json(['success' => true]);
    }

    public function getMessages(Request $request, $sessionId)
    {
        // Verificar autenticação
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autenticado.',
            ], 401);
        }

        $session = ChatSession::with(['messages.user', 'assignedTo'])
            ->findOrFail($sessionId);

        // Verificar permissão
        if (Auth::user()->id !== $session->assigned_to && ! Auth::user()->hasAnyRole(jubaf_chat_agent_role_names())) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para ver esta sessão.',
            ], 403);
        }

        // Filtrar mensagens por last_id se fornecido
        $lastId = $request->query('last_id', 0);
        $messages = $session->messages()->with('user')
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Marcar mensagens como lidas apenas se não for apenas uma verificação de novas mensagens
        if ($lastId == 0) {
            $session->markAsRead('user');
        }

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->toISOString(),
                    'sender' => $msg->sender ? [
                        'id' => $msg->sender->id,
                        'name' => $msg->sender->name,
                    ] : null,
                ];
            }),
        ]);
    }

    public function getActiveSessions()
    {
        $sessions = ChatSession::with(['lastMessage', 'assignedTo'])
            ->where(function($query) {
                $query->where('assigned_to', Auth::id())
                      ->orWhereNull('assigned_to');
            })
            ->whereIn('status', ['waiting', 'active'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'sessions' => $sessions,
        ]);
    }

    public function markAsRead($sessionId)
    {
        $session = ChatSession::findOrFail($sessionId);
        $this->chatService->markAsRead($session, 'user');

        return response()->json([
            'success' => true,
        ]);
    }

    public function updateStatus(Request $request, $sessionId)
    {
        $session = ChatSession::findOrFail($sessionId);

        $validated = $request->validate([
            'status' => 'required|in:waiting,active,closed',
        ]);

        $session->update(['status' => $validated['status']]);

        if ($validated['status'] === 'closed') {
            $session->update(['closed_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }
}

