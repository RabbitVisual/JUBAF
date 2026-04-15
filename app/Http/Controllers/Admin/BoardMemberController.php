<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardMemberRequest;
use App\Models\BoardMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BoardMemberController extends Controller
{
    protected function routePrefix(): string
    {
        return 'admin.board-members';
    }

    protected function viewPrefix(): string
    {
        return 'admin::board-members';
    }

    public function index(): View
    {
        $this->authorize('viewAny', BoardMember::class);

        $members = BoardMember::query()->orderBy('sort_order')->orderBy('full_name')->paginate(20);

        return view($this->viewPrefix().'.index', compact('members'));
    }

    public function create(): View
    {
        $this->authorize('create', BoardMember::class);

        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);
        $boardMember = new BoardMember([
            'is_active' => true,
            'sort_order' => (int) BoardMember::query()->max('sort_order') + 1,
        ]);

        return view($this->viewPrefix().'.create', compact('users', 'boardMember'));
    }

    public function store(BoardMemberRequest $request): RedirectResponse
    {
        $this->authorize('create', BoardMember::class);

        $data = $this->validatedData($request);
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('board-members', 'public');
        }
        BoardMember::create($data);

        return redirect()->route($this->routePrefix().'.index')
            ->with('success', 'Membro da diretoria criado com sucesso.');
    }

    public function edit(BoardMember $boardMember): View
    {
        $this->authorize('update', $boardMember);

        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);

        return view($this->viewPrefix().'.edit', compact('boardMember', 'users'));
    }

    public function update(BoardMemberRequest $request, BoardMember $boardMember): RedirectResponse
    {
        $this->authorize('update', $boardMember);

        $data = $this->validatedData($request);
        if ($request->hasFile('photo')) {
            if ($boardMember->photo_path) {
                Storage::disk('public')->delete($boardMember->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('board-members', 'public');
        }
        $boardMember->update($data);

        return redirect()->route($this->routePrefix().'.index')
            ->with('success', 'Membro atualizado com sucesso.');
    }

    public function destroy(BoardMember $boardMember): RedirectResponse
    {
        $this->authorize('delete', $boardMember);

        if ($boardMember->photo_path) {
            Storage::disk('public')->delete($boardMember->photo_path);
        }
        $boardMember->delete();

        return redirect()->route($this->routePrefix().'.index')
            ->with('success', 'Membro removido.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedData(BoardMemberRequest $request): array
    {
        $data = $request->validated();
        unset($data['photo']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        if (empty($data['user_id'])) {
            $data['user_id'] = null;
        }

        return $data;
    }
}
