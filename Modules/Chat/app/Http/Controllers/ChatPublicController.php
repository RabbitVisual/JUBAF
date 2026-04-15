<?php

namespace Modules\Chat\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Chat\App\Models\ChatConfig;
use Modules\Chat\App\Models\ChatSession;
use Modules\Chat\App\Models\ChatMessage;
use Modules\Chat\App\Services\ChatService;
use Modules\Chat\App\Helpers\CpfHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatPublicController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }
    public function status()
    {
        $isAvailable = ChatConfig::isAvailableNow();
        $nextAvailability = ChatConfig::getNextAvailability();
        $agentsOnline = \Modules\Chat\App\Services\ChatAgentPresence::countOnline();

        return response()->json([
            'available' => $isAvailable,
            'welcome_message' => ChatConfig::get('welcome_message'),
            'offline_message' => ChatConfig::get('offline_message'),
            'next_availability' => $nextAvailability,
            'availability_mode' => ChatConfig::publicAvailabilityMode(),
            'agents_online' => $agentsOnline,
            'within_office_hours' => ChatConfig::isWithinOpeningHours(),
        ]);
    }

    public function start(Request $request)
    {
        if (!ChatConfig::isAvailableNow()) {
            return response()->json([
                'success' => false,
                'message' => ChatConfig::get('offline_message'),
            ], 403);
        }

        // Preparar CPF (remover formatação)
        $cpf = $request->input('cpf');
        if ($cpf) {
            $cpf = CpfHelper::clean($cpf);
            $request->merge(['cpf' => $cpf]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|size:11',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ], [
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.size' => 'O CPF deve conter 11 dígitos.',
        ]);

        // Validar CPF
        if (!CpfHelper::validate($validated['cpf'])) {
            return response()->json([
                'success' => false,
                'message' => 'CPF inválido. Por favor, verifique o número informado.',
            ], 422);
        }

        // Verificar se já existe sessão ativa para este CPF
        $existingSession = ChatSession::getActiveSessionForCpf($validated['cpf']);
        
        if ($existingSession) {
            return response()->json([
                'success' => false,
                'message' => 'Você já possui uma sessão de chat ativa. Por favor, aguarde o encerramento da sessão anterior ou entre em contato com o atendente.',
                'existing_session_id' => $existingSession->session_id,
                'session_status' => $existingSession->status,
            ], 409); // 409 Conflict
        }

        $sessionId = Str::uuid()->toString();

        $session = ChatSession::create([
            'session_id' => $sessionId,
            'type' => 'public',
            'visitor_name' => $validated['name'],
            'visitor_cpf' => $validated['cpf'],
            'visitor_email' => $validated['email'] ?? null,
            'visitor_phone' => $validated['phone'] ?? null,
            'visitor_ip' => $request->ip(),
            'status' => 'waiting',
        ]);

        // Mensagem de boas-vindas usando o service
        $this->chatService->sendMessage(
            $session,
            ChatConfig::get('welcome_message', 'Olá! Como posso ajudá-lo?'),
            'system'
        );

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'session' => $session->load('lastMessage'),
        ]);
    }

    public function getSession($sessionId)
    {
        $session = ChatSession::with(['messages.user', 'assignedTo'])
            ->where('session_id', $sessionId)
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sessão não encontrada.',
                'session' => null,
                'messages' => [],
            ], 404);
        }

        // Marcar mensagens como lidas
        $session->markAsRead('visitor');

        return response()->json([
            'success' => true,
            'session' => $session,
            'messages' => $session->messages->map(function($msg) {
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

    public function sendMessage(Request $request, $sessionId)
    {
        $session = ChatSession::where('session_id', $sessionId)->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sessão não encontrada.',
            ], 404);
        }

        if ($session->status === 'closed') {
            return response()->json([
                'success' => false,
                'message' => 'Esta sessão foi encerrada.',
            ], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        try {
            $message = $this->chatService->sendMessage(
                $session,
                $validated['message'],
                'visitor'
            );

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'sender_type' => $message->sender_type,
                    'message' => $message->message,
                    'created_at' => $message->created_at->toISOString(),
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
        $session = ChatSession::where('session_id', $sessionId)->first();
        
        if (!$session) {
            return response()->json(['success' => false], 404);
        }

        $isTyping = $request->input('is_typing', true);
        $visitorName = $session->visitor_name ?? 'Visitante';

        // Disparar evento de digitação
        broadcast(new \Modules\Chat\App\Events\UserTyping(
            $session->session_id,
            null, // Visitante não tem user_id
            $visitorName,
            $isTyping
        ))->toOthers();

        return response()->json(['success' => true]);
    }

    public function updateVisitorInfo(Request $request, $sessionId)
    {
        $session = ChatSession::where('session_id', $sessionId)->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|nullable|email|max:255',
            'phone' => 'sometimes|nullable|string|max:20',
        ]);

        $session->update($validated);

        // Disparar evento de atualização
        broadcast(new \Modules\Chat\App\Events\ChatSessionUpdated($session->fresh()))->toOthers();

        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }
}

