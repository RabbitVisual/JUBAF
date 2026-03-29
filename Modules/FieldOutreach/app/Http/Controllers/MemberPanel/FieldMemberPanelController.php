<?php

namespace Modules\FieldOutreach\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Church\Models\Church;
use Modules\FieldOutreach\Models\FieldVisit;

class FieldMemberPanelController extends Controller
{
    public function dashboard()
    {
        abort_unless(Auth::user()?->canAccess('field_view'), 403);
        $canManage = Auth::user()?->canAccess('field_manage') ?? false;
        $visitCount = FieldVisit::query()->count();

        return view('fieldoutreach::memberpanel.dashboard', compact('canManage', 'visitCount'));
    }

    public function visitsIndex(Request $request)
    {
        abort_unless(Auth::user()?->canAccess('field_view'), 403);
        $user = Auth::user();
        $q = FieldVisit::query()->with(['church', 'creator'])->orderByDesc('visited_at');

        if ($request->filled('church_id')) {
            $q->where('church_id', $request->church_id);
        }

        if ($user && $user->canAccess('field_view') && ! $user->canAccess('field_manage') && ! $user->isAdmin()) {
            if ($user->isYouthLeader() && $user->church_id) {
                $q->where('church_id', $user->church_id);
            }
        }

        $visits = $q->paginate(20)->withQueryString();
        $churches = Church::query()->orderBy('name')->get(['id', 'name']);

        return view('fieldoutreach::memberpanel.visits.index', compact('visits', 'churches'));
    }

    public function visitsShow(FieldVisit $visit)
    {
        abort_unless(Auth::user()?->canAccess('field_view'), 403);
        $user = Auth::user();
        abort_unless($this->userCanAccessVisit($user, $visit), 403);
        $visit->load(['church', 'attendees', 'creator']);
        $canManage = $user?->canAccess('field_manage') ?? false;

        return view('fieldoutreach::memberpanel.visits.show', compact('visit', 'canManage'));
    }

    public function visitsCreate()
    {
        abort_unless(Auth::user()?->canAccess('field_manage'), 403);
        $churches = Church::query()->where('is_active', true)->orderBy('name')->get();
        $users = User::query()->orderBy('name')->get(['id', 'name']);

        return view('fieldoutreach::memberpanel.visits.create', compact('churches', 'users'));
    }

    public function visitsStore(Request $request)
    {
        abort_unless(Auth::user()?->canAccess('field_manage'), 403);
        $data = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'visited_at' => 'required|date',
            'notes' => 'nullable|string',
            'next_steps' => 'nullable|string',
            'attendee_ids' => 'nullable|array',
            'attendee_ids.*' => 'exists:users,id',
        ]);
        $data['created_by'] = Auth::id();
        $attendees = $data['attendee_ids'] ?? [];
        unset($data['attendee_ids']);
        $visit = FieldVisit::create($data);
        $visit->attendees()->sync($attendees);

        return redirect()->route('memberpanel.field.visits.show', $visit)->with('success', 'Visita registada.');
    }

    public function visitsEdit(FieldVisit $visit)
    {
        abort_unless(Auth::user()?->canAccess('field_manage'), 403);
        abort_unless($this->userCanAccessVisit(Auth::user(), $visit), 403);
        $churches = Church::query()->where('is_active', true)->orderBy('name')->get();
        $users = User::query()->orderBy('name')->get(['id', 'name']);
        $visit->load('attendees');

        return view('fieldoutreach::memberpanel.visits.edit', compact('visit', 'churches', 'users'));
    }

    public function visitsUpdate(Request $request, FieldVisit $visit)
    {
        abort_unless(Auth::user()?->canAccess('field_manage'), 403);
        abort_unless($this->userCanAccessVisit(Auth::user(), $visit), 403);
        $data = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'visited_at' => 'required|date',
            'notes' => 'nullable|string',
            'next_steps' => 'nullable|string',
            'attendee_ids' => 'nullable|array',
            'attendee_ids.*' => 'exists:users,id',
        ]);
        $attendees = $data['attendee_ids'] ?? [];
        unset($data['attendee_ids']);
        $visit->update($data);
        $visit->attendees()->sync($attendees);

        return redirect()->route('memberpanel.field.visits.show', $visit)->with('success', 'Atualizado.');
    }

    public function visitsDestroy(FieldVisit $visit)
    {
        abort_unless(Auth::user()?->canAccess('field_manage'), 403);
        abort_unless($this->userCanAccessVisit(Auth::user(), $visit), 403);
        $visit->delete();

        return redirect()->route('memberpanel.field.visits.index')->with('success', 'Removido.');
    }

    protected function userCanAccessVisit(?User $user, FieldVisit $visit): bool
    {
        if (! $user) {
            return false;
        }
        if ($user->isAdmin() || $user->canAccess('field_manage')) {
            return true;
        }
        if ($user->canAccess('field_view')) {
            if ($user->isYouthLeader() && $user->church_id) {
                return (int) $visit->church_id === (int) $user->church_id;
            }

            return true;
        }

        return false;
    }
}
