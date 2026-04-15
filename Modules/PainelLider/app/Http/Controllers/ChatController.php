<?php

namespace Modules\PainelLider\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Chat\App\Models\ChatSession;
use Modules\Chat\App\Services\ChatService;

class ChatController extends Controller
{
    public function __construct(
        protected ChatService $chatService
    ) {}

    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()
                || $request->header('Accept') === 'application/json') {
                $sessoes = ChatSession::where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhere('assigned_to', $user->id);
                })
                    ->where('type', 'internal')
                    ->with(['lastMessage', 'assignedTo', 'user'])
                    ->orderBy('last_activity_at', 'desc')
                    ->paginate(20);

                $sessoes->getCollection()->transform(function (ChatSession $s) use ($user) {
                    $s->unread_for_me = (int) ($s->user_id === $user->id
                        ? $s->unread_count_user
                        : $s->unread_count_visitor);

                    return $s;
                });

                return response()->json([
                    'success' => true,
                    'sessoes' => $sessoes,
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }

            return view('chat::painellider.chat.index');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()
                || $request->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao carregar conversas: '.$e->getMessage(),
                    'sessoes' => ['data' => []],
                ], 500);
            }

            throw $e;
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        $sessionId = Str::uuid()->toString();

        $session = ChatSession::create([
            'session_id' => $sessionId,
            'type' => 'internal',
            'user_id' => $user->id,
            'assigned_to' => $validated['assigned_to'],
            'status' => 'active',
            'visitor_name' => $user->name,
        ]);

        $message = $this->chatService->sendMessage(
            $session,
            $validated['message'],
            'user',
            $user
        );

        return response()->json([
            'success' => true,
            'session' => $session->load('lastMessage'),
            'message' => $message,
        ]);
    }

    public function getMessages(string $sessionId)
    {
        $user = Auth::user();

        $session = ChatSession::where('session_id', $sessionId)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('assigned_to', $user->id);
            })
            ->with(['assignedTo', 'user'])
            ->firstOrFail();

        $peerName = $session->user_id === $user->id
            ? ($session->assignedTo?->name ?? 'Contacto')
            : ($session->user?->name ?? 'Contacto');

        $messages = $session->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'sender_type' => $msg->sender_type,
                    'created_at' => $msg->created_at->toISOString(),
                    'sender' => $msg->sender ? [
                        'id' => $msg->sender->id,
                        'name' => $msg->sender->name,
                        'email' => $msg->sender->email ?? null,
                    ] : null,
                ];
            });

        try {
            $session->markAsRead($session->user_id === $user->id ? 'user' : 'visitor');
        } catch (\Exception) {
        }

        return response()->json([
            'success' => true,
            'session' => [
                'session_id' => $session->session_id,
                'user_id' => $session->user_id,
                'assigned_to' => $session->assigned_to,
                'peer_name' => $peerName,
            ],
            'messages' => $messages,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function sendMessage(Request $request, string $sessionId)
    {
        $user = Auth::user();

        $session = ChatSession::where('session_id', $sessionId)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('assigned_to', $user->id);
            })
            ->firstOrFail();

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $senderType = $session->user_id === $user->id ? 'user' : 'visitor';

        $message = $this->chatService->sendMessage(
            $session,
            $validated['message'],
            $senderType,
            $user
        );

        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
        ]);
    }

    public function getAvailableUsers()
    {
        $roleNames = array_merge(
            ['super-admin', 'lider'],
            \App\Support\JubafRoleRegistry::directorateRoleNames(),
            array_filter([\App\Support\JubafRoleRegistry::legacyCoAdminName()])
        );

        $users = User::whereHas('roles', function ($query) use ($roleNames) {
            $query->whereIn('name', $roleNames);
        })
            ->where('id', '!=', Auth::id())
            ->select('id', 'name', 'email', 'photo')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }
}
