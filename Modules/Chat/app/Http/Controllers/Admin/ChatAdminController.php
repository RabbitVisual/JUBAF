<?php

namespace Modules\Chat\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Chat\App\Models\ChatConfig;
use Modules\Chat\App\Models\ChatSession;
use Modules\Chat\App\Models\ChatMessage;
use Modules\Chat\App\Services\ChatAgentPresence;
use Modules\Chat\App\Services\ChatService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatAdminController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Listagem de sessões de chat
     */
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'type', 'assigned_to', 'search', 'date_from', 'date_to']);

        $sessions = $this->chatService->getSessionsWithFilters($filters, 20);
        $stats = $this->chatService->getStatistics();

        // Atendentes disponíveis para filtro
        $agents = User::whereHas('roles', function ($q) {
            $q->whereIn('name', jubaf_chat_agent_role_names());
        })->orderBy('name')->get();

        $view = request()->routeIs('diretoria.*') ? 'chat::paineldiretoria.index' : 'chat::admin.index';
        return view($view, compact('sessions', 'filters', 'stats', 'agents'));
    }

    /**
     * Página de chat em tempo real (estilo WhatsApp)
     */
    public function realtime(Request $request)
    {
        // Usar getAllSessionsForAgent para mostrar TODAS as sessões públicas ativas
        $sessions = $this->chatService->getAllSessionsForAgent(Auth::user());
        $stats = $this->chatService->getStatistics();

        // Sessão ativa (se especificada)
        $activeSession = null;
        if ($request->has('session')) {
            $activeSession = ChatSession::with(['messages.user', 'assignedTo'])
                ->find($request->session);

            if ($activeSession) {
                $activeSession->markAsRead('user');
            }
        }

        $view = request()->routeIs('diretoria.*') ? 'chat::paineldiretoria.realtime' : 'chat::admin.realtime';
        return view($view, compact('sessions', 'stats', 'activeSession'));
    }

    /**
     * Configurações do chat
     */
    public function config()
    {
        $allConfigs = ChatConfig::all()->keyBy('key');

        $configs = collect([
            'chat_enabled' => $allConfigs->get('chat_enabled') ?? (object)['value' => 'true'],
            'public_chat_enabled' => $allConfigs->get('public_chat_enabled') ?? (object)['value' => 'true'],
            'welcome_message' => $allConfigs->get('welcome_message') ?? (object)['value' => 'Olá! Bem-vindo ao apoio JUBAF. Em que podemos ajudar?'],
            'offline_message' => $allConfigs->get('offline_message') ?? (object)['value' => 'No momento não há atendimento pelo chat. Tente novamente no horário configurado ou aguarde um membro da diretoria estar disponível no painel.'],
            'auto_close_timeout' => $allConfigs->get('auto_close_timeout') ?? (object)['value' => '30'],
            'max_concurrent_sessions' => $allConfigs->get('max_concurrent_sessions') ?? (object)['value' => '10'],
            'notification_sound' => $allConfigs->get('notification_sound') ?? (object)['value' => 'true'],
            'opening_hours' => $allConfigs->get('opening_hours') ?? (object)['value' => '{}'],
            'public_availability_mode' => $allConfigs->get('public_availability_mode') ?? (object)['value' => 'either'],
        ])->keyBy(function($item, $key) {
            return $key;
        });

        $openingHours = json_decode($configs->get('opening_hours')?->value ?? '{}', true);

        return view('chat::admin.config', compact('configs', 'openingHours'));
    }

    /**
     * Atualizar configurações
     */
    public function updateConfig(Request $request)
    {
        $validated = $request->validate([
            'chat_enabled' => 'sometimes|in:1',
            'public_chat_enabled' => 'sometimes|in:1',
            'welcome_message' => 'nullable|string|max:1000',
            'offline_message' => 'nullable|string|max:1000',
            'auto_close_timeout' => 'nullable|integer|min:1|max:1440',
            'max_concurrent_sessions' => 'nullable|integer|min:1|max:100',
            'notification_sound' => 'sometimes|in:1',
            'opening_hours' => 'nullable|array',
            'public_availability_mode' => 'required|in:office_hours,agents_online,either',
        ]);

        // Processar checkboxes
        ChatConfig::set('chat_enabled', $request->has('chat_enabled') ? 'true' : 'false');
        ChatConfig::set('public_chat_enabled', $request->has('public_chat_enabled') ? 'true' : 'false');
        ChatConfig::set('notification_sound', $request->has('notification_sound') ? 'true' : 'false');

        // Processar outros campos
        if ($request->has('welcome_message')) {
            ChatConfig::set('welcome_message', $validated['welcome_message']);
        }
        if ($request->has('offline_message')) {
            ChatConfig::set('offline_message', $validated['offline_message']);
        }
        if ($request->has('auto_close_timeout')) {
            ChatConfig::set('auto_close_timeout', (string)$validated['auto_close_timeout']);
        }
        if ($request->has('max_concurrent_sessions')) {
            ChatConfig::set('max_concurrent_sessions', (string)$validated['max_concurrent_sessions']);
        }

        // Processar horários de funcionamento
        if ($request->has('opening_hours')) {
            $hours = [];
            foreach ($request->input('opening_hours', []) as $day => $config) {
                $hours[$day] = [
                    'enabled' => isset($config['enabled']),
                    'start' => $config['start'] ?? '08:00',
                    'end' => $config['end'] ?? '17:00',
                ];
            }
            ChatConfig::set('opening_hours', json_encode($hours));
        }

        ChatConfig::set('public_availability_mode', $validated['public_availability_mode'], 'Disponibilidade do chat público');

        return redirect()->route('admin.chat.config')
            ->with('success', 'Configurações do chat atualizadas com sucesso!');
    }

    /**
     * Visualizar sessão de chat
     */
    public function show($id)
    {
        $session = ChatSession::with(['messages.user', 'assignedTo', 'user'])
            ->findOrFail($id);

        // Verificar permissão
        if (!$this->chatService->canAccessSession($session, Auth::user())) {
            abort(403, 'Você não tem permissão para acessar esta sessão.');
        }

        // Marcar mensagens como lidas
        $session->markAsRead('user');

        // Atendentes disponíveis para atribuição
        $agents = User::whereHas('roles', function ($q) {
            $q->whereIn('name', jubaf_chat_agent_role_names());
        })->orderBy('name')->get();

        $view = request()->routeIs('diretoria.*') ? 'chat::paineldiretoria.show' : 'chat::admin.show';
        return view($view, compact('session', 'agents'));
    }

    /**
     * Atribuir atendente à sessão
     */
    public function assign(Request $request, $id)
    {
        $session = ChatSession::findOrFail($id);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $agent = User::findOrFail($validated['assigned_to']);
        $this->chatService->assignSession($session, $agent);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sessão atribuída com sucesso!',
            ]);
        }

        return redirect()->back()->with('success', 'Sessão atribuída com sucesso!');
    }

    /**
     * Transferir sessão para outro atendente
     */
    public function transfer(Request $request, $id)
    {
        $session = ChatSession::findOrFail($id);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $agent = User::findOrFail($validated['assigned_to']);
        $this->chatService->transferSession($session, $agent, $validated['reason'] ?? null);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sessão transferida com sucesso!',
            ]);
        }

        return redirect()->back()->with('success', 'Sessão transferida com sucesso!');
    }

    /**
     * Encerrar sessão
     */
    public function close(Request $request, $id)
    {
        $session = ChatSession::findOrFail($id);

        $reason = $request->input('reason');
        $this->chatService->closeSession($session, $reason, Auth::user());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sessão encerrada com sucesso!',
            ]);
        }

        $route = request()->routeIs('diretoria.*') ? 'diretoria.chat.index' : 'admin.chat.index';
        return redirect()->route($route)->with('success', 'Sessão encerrada com sucesso!');
    }

    /**
     * Reabrir sessão
     */
    public function reopen($id)
    {
        $session = ChatSession::findOrFail($id);
        $this->chatService->reopenSession($session, Auth::user());

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sessão reaberta com sucesso!',
            ]);
        }

        return redirect()->back()->with('success', 'Sessão reaberta com sucesso!');
    }

    /**
     * API: Obter sessões ativas
     */
    public function apiGetSessions(Request $request)
    {
        // Usar getAllSessionsForAgent para mostrar TODAS as sessões públicas
        $sessions = $this->chatService->getAllSessionsForAgent(Auth::user());

        return response()->json([
            'success' => true,
            'sessions' => $sessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'session_id' => $session->session_id,
                    'visitor_name' => $session->visitor_name,
                    'visitor_cpf' => $session->visitor_cpf ? substr($session->visitor_cpf, 0, 3) . '.***.***-**' : null,
                    'status' => $session->status,
                    'status_texto' => $session->status_texto,
                    'unread_count' => $session->unread_count_user,
                    'assigned_to' => $session->assignedTo ? [
                        'id' => $session->assignedTo->id,
                        'name' => $session->assignedTo->name,
                    ] : null,
                    'last_message' => $session->lastMessage ? [
                        'message' => \Illuminate\Support\Str::limit($session->lastMessage->message, 50),
                        'sender_type' => $session->lastMessage->sender_type,
                        'created_at' => $session->lastMessage->created_at->toISOString(),
                    ] : null,
                    'created_at' => $session->created_at->toISOString(),
                    'last_activity_at' => $session->last_activity_at?->toISOString(),
                ];
            }),
        ]);
    }

    /**
     * API: Obter mensagens de uma sessão
     */
    public function apiGetMessages(Request $request, $id)
    {
        $session = ChatSession::findOrFail($id);

        // Verificar permissão
        if (!$this->chatService->canAccessSession($session, Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissão para acessar esta sessão.',
            ], 403);
        }

        $lastId = $request->query('last_id', 0);

        // Marcar como lidas apenas se não for apenas verificação de novas
        if ($lastId == 0) {
            $session->markAsRead('user');
        }

        $messages = $this->chatService->getMessagesForApi($session, $lastId);

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'session' => [
                'id' => $session->id,
                'status' => $session->status,
                'visitor_name' => $session->visitor_name,
            ],
        ]);
    }

    /**
     * API: Enviar mensagem
     */
    public function apiSendMessage(Request $request, $id)
    {
        $session = ChatSession::findOrFail($id);

        // Verificar permissão
        if (!$this->chatService->canAccessSession($session, Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'Sem permissão para enviar mensagens nesta sessão.',
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

    /**
     * API: Marcar como lida
     */
    public function apiMarkAsRead($id)
    {
        $session = ChatSession::findOrFail($id);
        $this->chatService->markAsRead($session, 'user');

        return response()->json(['success' => true]);
    }

    /**
     * API: Indicador de digitação
     */
    public function apiTyping(Request $request, $id)
    {
        // Por enquanto, apenas retorna sucesso
        // Em uma implementação com WebSockets, isso dispararia um evento
        return response()->json(['success' => true]);
    }

    /**
     * API: Atualizar status da sessão
     */
    public function apiUpdateStatus(Request $request, $id)
    {
        $session = ChatSession::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:waiting,active,closed',
        ]);

        if ($validated['status'] === 'closed') {
            $this->chatService->closeSession($session, null, Auth::user());
        } else {
            $session->update(['status' => $validated['status']]);
        }

        return response()->json([
            'success' => true,
            'session' => [
                'id' => $session->id,
                'status' => $session->fresh()->status,
            ],
        ]);
    }

    /**
     * Heartbeat: marca atendente como online no painel (habilita widget quando configurado).
     */
    public function apiPresencePing(Request $request)
    {
        $user = Auth::user();
        if (! $user || ! $user->hasAnyRole(jubaf_chat_agent_role_names())) {
            return response()->json([
                'success' => false,
                'message' => 'Não autorizado.',
            ], 403);
        }

        ChatAgentPresence::touch((int) $user->id);

        return response()->json([
            'success' => true,
            'agents_online' => ChatAgentPresence::countOnline(),
        ]);
    }

    /**
     * Estatísticas do chat
     */
    public function statistics()
    {
        $stats = $this->chatService->getStatistics();

        // Dados para gráficos
        $sessionsPerDay = ChatSession::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $messagesByType = ChatMessage::selectRaw('sender_type, COUNT(*) as count')
            ->groupBy('sender_type')
            ->get();

        $view = request()->routeIs('diretoria.*') ? 'chat::paineldiretoria.statistics' : 'chat::admin.statistics';
        return view($view, compact('stats', 'sessionsPerDay', 'messagesByType'));
    }
}
