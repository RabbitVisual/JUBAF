<?php

namespace Modules\Notificacoes\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Notificacoes\App\Models\Notificacao;
use Modules\Notificacoes\App\Services\NotificacaoService;
use Spatie\Permission\Models\Role;

class NotificacoesAdminController extends Controller
{
    protected NotificacaoService $service;

    public function __construct(NotificacaoService $service)
    {
        $this->service = $service;
    }

    protected function notificacoesRoute(string $suffix): string
    {
        return request()->routeIs('diretoria.*')
            ? 'diretoria.notificacoes.'.$suffix
            : 'admin.notificacoes.'.$suffix;
    }

    protected function notificacoesView(string $blade): string
    {
        return request()->routeIs('diretoria.*')
            ? 'notificacoes::paineldiretoria.'.$blade
            : 'notificacoes::admin.'.$blade;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['type', 'user_id', 'role', 'module_source', 'is_read', 'search']);
        $query = Notificacao::with('user');

        if (isset($filters['type']) && $filters['type'] !== '') {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['user_id']) && $filters['user_id'] !== '') {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['role']) && $filters['role'] !== '') {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['module_source']) && $filters['module_source'] !== '') {
            $query->where('module_source', $filters['module_source']);
        }

        if (isset($filters['is_read']) && $filters['is_read'] !== '' && $filters['is_read'] !== null) {
            $query->where('is_read', filter_var($filters['is_read'], FILTER_VALIDATE_BOOLEAN));
        }

        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::select('id', 'name')->orderBy('name')->get();
        $roles = Role::all();

        $modules = config('notificacoes.module_sources', []);

        $stats = [
            'total' => Notificacao::query()->count(),
            'unread' => Notificacao::query()->where('is_read', false)->count(),
            'read' => Notificacao::query()->where('is_read', true)->count(),
        ];

        return view($this->notificacoesView('index'), compact('notifications', 'users', 'roles', 'modules', 'filters', 'stats'));
    }

    public function create()
    {
        $users = User::select('id', 'name')->orderBy('name')->get();
        $roles = Role::all();

        $modules = config('notificacoes.module_sources', []);

        return view($this->notificacoesView('create'), compact('users', 'roles', 'modules'));
    }

    public function store(Request $request)
    {
        $moduleKeys = array_keys(config('notificacoes.module_sources', []));
        if ($request->input('module_source') === '') {
            $request->merge(['module_source' => null]);
        }

        $validated = $request->validate([
            'type' => 'required|in:info,success,warning,error,system,alert',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_type' => 'required|in:user,role,all',
            'user_id' => 'nullable|required_if:recipient_type,user|exists:users,id',
            'role' => 'nullable|required_if:recipient_type,role|exists:roles,name',
            'module_source' => ['nullable', 'string', Rule::in($moduleKeys)],
            'action_url' => 'nullable|url',
        ]);

        try {
            if ($validated['recipient_type'] === 'user') {
                $this->service->sendToUser(
                    $validated['user_id'],
                    $validated['type'],
                    $validated['title'],
                    $validated['message'],
                    $validated['action_url'] ?? null,
                    null,
                    $validated['module_source'] ?? null
                );
            } elseif ($validated['recipient_type'] === 'role') {
                $this->service->sendToRole(
                    $validated['role'],
                    $validated['type'],
                    $validated['title'],
                    $validated['message'],
                    $validated['action_url'] ?? null,
                    null,
                    $validated['module_source'] ?? null
                );
            } else {
                $this->service->sendToAll(
                    $validated['type'],
                    $validated['title'],
                    $validated['message'],
                    $validated['action_url'] ?? null,
                    null,
                    $validated['module_source'] ?? null
                );
            }

            return redirect()->route($this->notificacoesRoute('index'))
                ->with('success', 'Notificação criada com sucesso');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar notificação: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        $notification = Notificacao::with('user')->findOrFail($id);

        return view($this->notificacoesView('show'), compact('notification'));
    }

    public function destroy($id)
    {
        try {
            $notification = Notificacao::findOrFail($id);
            $notification->delete();

            return redirect()->route($this->notificacoesRoute('index'))
                ->with('success', 'Notificação deletada com sucesso');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao deletar notificação: '.$e->getMessage());
        }
    }

    public function markAsRead($id)
    {
        try {
            $success = $this->service->markAsRead($id, auth()->id());

            if ($success) {
                return redirect()->back()->with('success', 'Notificação marcada como lida.');
            }

            return redirect()->back()->with('error', 'Erro ao marcar notificação como lida.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao processar: '.$e->getMessage());
        }
    }

    public function markAllAsRead()
    {
        try {
            $count = $this->service->markAllAsRead(auth()->id());

            return redirect()->back()->with('success', "{$count} notificações marcadas como lidas.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao processar: '.$e->getMessage());
        }
    }
}
