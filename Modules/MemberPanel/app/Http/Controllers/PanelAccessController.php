<?php

namespace Modules\MemberPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MemberPanelModuleGrant;
use App\Models\User;
use App\Services\MemberPanelAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PanelAccessController extends Controller
{
    public function index()
    {
        $grants = MemberPanelModuleGrant::query()
            ->with(['user', 'grantedBy'])
            ->orderByDesc('created_at')
            ->paginate(30);

        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);

        return view('memberpanel::panel-access.index', compact('grants', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'module_key' => ['required', 'string', Rule::in(MemberPanelAccess::moduleKeys())],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        MemberPanelModuleGrant::query()->updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'module_key' => $data['module_key'],
            ],
            [
                'granted_by_user_id' => Auth::id(),
                'expires_at' => $data['expires_at'] ?? null,
            ]
        );

        return back()->with('success', 'Acesso atualizado.');
    }

    public function destroy(MemberPanelModuleGrant $grant)
    {
        if ($grant->user_id === Auth::id() && ! Auth::user()?->isAdmin()) {
            abort(403);
        }
        $grant->delete();

        return back()->with('success', 'Acesso revogado.');
    }
}
