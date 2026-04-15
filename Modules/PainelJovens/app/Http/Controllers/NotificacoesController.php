<?php

namespace Modules\PainelJovens\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Notificacoes\App\Models\Notificacao;

class NotificacoesController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notificacao::query()
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notificacoes::paineljovens.index', compact('notifications'));
    }

    public function show(int $id)
    {
        $notification = Notificacao::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        return view('notificacoes::paineljovens.show', compact('notification'));
    }
}
