<?php

namespace Modules\Notificacoes\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ChecksModuleEnabled;
use Illuminate\Http\Request;
use Modules\Notificacoes\App\Services\NotificacaoService;

class NotificacoesController extends Controller
{
    use ChecksModuleEnabled;

    protected NotificacaoService $service;

    public function __construct(NotificacaoService $service)
    {
        $this->ensureModuleEnabled('Notificacoes');
        $this->service = $service;
    }

    /**
     * Display a listing of the user's notifications
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $filters = $request->only(['type', 'is_read', 'panel']);

        $query = \Modules\Notificacoes\App\Models\Notificacao::forUser($userId)
            ->forPanel($filters['panel'] ?? null)
            ->with('user')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['is_read']) && $filters['is_read'] !== '') {
            $isRead = $filters['is_read'] === '1' || $filters['is_read'] === 'true';
            $query->where('is_read', $isRead);
        }

        $notifications = $query->paginate(20);
        $unreadCount = $this->service->getUnreadCount($userId);

        return view('notificacoes::index', compact('notifications', 'unreadCount', 'filters'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $success = $this->service->markAsRead($id, auth()->id());

        if ($success) {
            return redirect()->back()->with('success', 'Notificação marcada como lida.');
        }

        return redirect()->back()->with('error', 'Erro ao marcar notificação como lida.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $count = $this->service->markAllAsRead(auth()->id());

        return redirect()->back()->with('success', "{$count} notificações marcadas como lidas.");
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $success = $this->service->delete($id, auth()->id());

        if ($success) {
            return redirect()->back()->with('success', 'Notificação deletada com sucesso.');
        }

        return redirect()->back()->with('error', 'Erro ao deletar notificação.');
    }
}
