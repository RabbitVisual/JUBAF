<?php

namespace Modules\Notifications\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Notifications\App\Services\InAppNotificationService;

class NotificationBroadcastController extends Controller
{
    public function create()
    {
        $roles = \App\Models\Role::orderBy('name')->get();

        return view('notifications::admin.control.broadcast', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'target' => 'required|in:all,roles',
            'target_roles' => 'required_if:target,roles|array',
            'target_roles.*' => 'exists:roles,slug',
        ]);

        $users = $this->resolveUsers($validated);

        if ($users->isEmpty()) {
            return redirect()->back()->withInput()->with('error', 'Nenhum destinatário encontrado para os critérios selecionados.');
        }

        $inApp = app(InAppNotificationService::class);
        $notification = $inApp->sendToUsers($users, $validated['title'], $validated['message'], [
            'type' => $validated['type'],
            'created_by' => auth()->id(),
            'broadcast' => true,
        ]);

        return redirect()->route('admin.notifications.index')
            ->with('success', "Notificação enviada para {$users->count()} destinatário(s).");
    }

    protected function resolveUsers(array $validated): \Illuminate\Support\Collection
    {
        $query = User::where('is_active', true);

        if ($validated['target'] === 'roles' && ! empty($validated['target_roles'])) {
            $query->whereHas('role', fn ($q) => $q->whereIn('slug', $validated['target_roles']));
        }

        return $query->get();
    }
}
