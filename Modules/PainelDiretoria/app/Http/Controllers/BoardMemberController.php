<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use App\Http\Controllers\Admin\BoardMemberController as AdminBoardMemberController;
use App\Models\BoardMember;
use App\Models\User;
use Illuminate\View\View;

class BoardMemberController extends AdminBoardMemberController
{
    protected function routePrefix(): string
    {
        return 'diretoria.board-members';
    }

    protected function viewPrefix(): string
    {
        return 'paineldiretoria::board-members';
    }

    public function index(): View
    {
        $this->authorize('viewAny', BoardMember::class);

        $members = BoardMember::query()->orderBy('sort_order')->orderBy('full_name')->paginate(20);

        $stats = [
            'total' => (int) BoardMember::query()->count(),
            'active' => (int) BoardMember::query()->where('is_active', true)->count(),
            'inactive' => (int) BoardMember::query()->where('is_active', false)->count(),
        ];

        $routePrefix = $this->routePrefix();

        return view($this->viewPrefix().'.index', compact('members', 'stats', 'routePrefix'));
    }

    public function create(): View
    {
        $this->authorize('create', BoardMember::class);

        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);
        $boardMember = new BoardMember([
            'is_active' => true,
            'sort_order' => (int) BoardMember::query()->max('sort_order') + 1,
        ]);

        $routePrefix = $this->routePrefix();

        return view($this->viewPrefix().'.create', compact('users', 'boardMember', 'routePrefix'));
    }

    public function edit(BoardMember $boardMember): View
    {
        $this->authorize('update', $boardMember);

        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);
        $routePrefix = $this->routePrefix();

        return view($this->viewPrefix().'.edit', compact('boardMember', 'users', 'routePrefix'));
    }
}
