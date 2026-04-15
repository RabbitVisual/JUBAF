<?php

namespace Modules\Chat\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\JubafRoleRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Chat\App\Models\ChatConversation;
use Modules\Chat\App\Services\ErpConversationService;
use Modules\Chat\App\Support\ErpChatAuthority;

class ErpChatController extends Controller
{
    public function __construct(
        private ErpConversationService $erpConversationService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', ChatConversation::class);

        $isDiretoria = $request->routeIs('diretoria.*');
        $view = $isDiretoria ? 'chat::paineldiretoria.erp-chat.index' : 'chat::painellider.erp-chat.index';

        return view($view, [
            'conversationsJsonUrl' => $isDiretoria
                ? route('diretoria.chat.interno.conversations')
                : route('lideres.chat.interno.conversations'),
            'usersJsonUrl' => $isDiretoria
                ? route('diretoria.chat.interno.users')
                : route('lideres.chat.interno.users'),
            'openUrl' => $isDiretoria
                ? route('diretoria.chat.interno.open')
                : route('lideres.chat.interno.open'),
            'echoReverb' => [
                'key' => config('broadcasting.connections.reverb.key'),
                'host' => env('REVERB_HOST', '127.0.0.1'),
                'port' => (int) env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
            ],
            'broadcastDriver' => config('broadcasting.default'),
            'authUserId' => auth()->id(),
            'messagesUrlTemplate' => str_replace(
                '00000000-0000-0000-0000-000000000000',
                '__UUID__',
                $isDiretoria
                    ? route('diretoria.chat.interno.messages', ['conversation' => '00000000-0000-0000-0000-000000000000'])
                    : route('lideres.chat.interno.messages', ['conversation' => '00000000-0000-0000-0000-000000000000'])
            ),
            'sendUrlTemplate' => str_replace(
                '00000000-0000-0000-0000-000000000000',
                '__UUID__',
                $isDiretoria
                    ? route('diretoria.chat.interno.send', ['conversation' => '00000000-0000-0000-0000-000000000000'])
                    : route('lideres.chat.interno.send', ['conversation' => '00000000-0000-0000-0000-000000000000'])
            ),
        ]);
    }

    public function eligiblePeers(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ChatConversation::class);

        $auth = $request->user();
        $auth->loadMissing('roles');

        if ($auth->hasAnyRole(JubafRoleRegistry::directorateRoleNames())) {
            $roleNames = array_unique(array_merge(
                JubafRoleRegistry::directorateRoleNames(),
                ['lider', 'pastor'],
                JubafRoleRegistry::superAdminRoleNames(),
            ));

            $users = User::query()
                ->where('id', '!=', $auth->id)
                ->where('active', true)
                ->whereHas('roles', fn ($q) => $q->whereIn('name', $roleNames))
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'photo']);

            return response()->json(['success' => true, 'users' => $users]);
        }

        if ($auth->hasAnyRole(['lider', 'pastor'])) {
            $agents = jubaf_chat_agent_role_names();
            $users = User::query()
                ->where('id', '!=', $auth->id)
                ->where('active', true)
                ->whereHas('roles', fn ($q) => $q->whereIn('name', $agents))
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'photo']);

            return response()->json(['success' => true, 'users' => $users]);
        }

        abort(403);
    }

    public function conversations(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ChatConversation::class);

        $user = $request->user();
        $list = $this->erpConversationService->listConversationsFor($user);

        $data = $list->map(function (ChatConversation $c) use ($user) {
            $last = $c->conversationMessages->first();
            $peer = $c->participants->firstWhere('id', '!=', $user->id);

            return [
                'id' => $c->id,
                'uuid' => $c->uuid,
                'peer' => $peer ? ['id' => $peer->id, 'name' => $peer->name] : null,
                'last_message' => $last ? mb_strimwidth(strip_tags($last->body), 0, 120, '…') : null,
                'updated_at' => $c->updated_at?->toIso8601String(),
            ];
        });

        return response()->json(['success' => true, 'conversations' => $data]);
    }

    public function open(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ChatConversation::class);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $auth = $request->user();
        $target = User::query()->whereKey($validated['user_id'])->with('roles')->firstOrFail();

        if (! ErpChatAuthority::canInitiateDirect($auth, $target)) {
            abort(403, 'Não pode iniciar conversa com este utilizador.');
        }

        $conversation = $this->erpConversationService->findOrCreateDirectDm($auth, $target);

        return response()->json([
            'success' => true,
            'conversation' => [
                'uuid' => $conversation->uuid,
            ],
        ]);
    }

    public function messages(Request $request, ChatConversation $conversation): JsonResponse
    {
        $this->authorize('view', $conversation);

        $rows = $conversation->conversationMessages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->limit(200)
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'body' => $m->body,
                'created_at' => $m->created_at?->toIso8601String(),
                'sender' => $m->sender ? [
                    'id' => $m->sender->id,
                    'name' => $m->sender->name,
                ] : null,
            ]);

        return response()->json([
            'success' => true,
            'conversation_uuid' => $conversation->uuid,
            'conversation_id' => $conversation->id,
            'messages' => $rows,
        ]);
    }

    public function send(Request $request, ChatConversation $conversation): JsonResponse
    {
        $this->authorize('sendMessage', $conversation);

        $validated = $request->validate([
            'body' => 'required|string|max:8000',
        ]);

        $msg = $this->erpConversationService->appendMessage(
            $conversation,
            $request->user(),
            $validated['body']
        );

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $msg->id,
                'body' => $msg->body,
                'created_at' => $msg->created_at?->toIso8601String(),
            ],
        ]);
    }
}
