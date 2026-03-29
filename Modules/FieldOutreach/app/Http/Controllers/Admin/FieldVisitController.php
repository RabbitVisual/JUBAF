<?php

namespace Modules\FieldOutreach\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Church\Models\Church;
use Modules\FieldOutreach\Models\FieldVisit;

class FieldVisitController extends Controller
{
    public function index(Request $request)
    {
        $q = FieldVisit::query()->with(['church', 'creator'])->orderByDesc('visited_at');
        if ($request->filled('church_id')) {
            $q->where('church_id', $request->church_id);
        }
        $visits = $q->paginate(20)->withQueryString();
        $churches = Church::query()->orderBy('name')->get(['id', 'name']);

        return view('fieldoutreach::admin.visits.index', compact('visits', 'churches'));
    }

    public function create()
    {
        abort_unless(Auth::user()?->canAccess('field_manage'), 403);
        $churches = Church::query()->where('is_active', true)->orderBy('name')->get();
        $users = User::query()->orderBy('name')->get(['id', 'name']);

        return view('fieldoutreach::admin.visits.create', compact('churches', 'users'));
    }

    public function store(Request $request)
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

        return redirect()->route('admin.field.visits.index')->with('success', 'Visita registada.');
    }

    public function show(FieldVisit $visit)
    {
        $visit->load(['church', 'attendees', 'creator']);

        return view('fieldoutreach::admin.visits.show', compact('visit'));
    }

    public function edit(FieldVisit $visit)
    {
        abort_unless(Auth::user()?->canAccess('field_manage'), 403);
        $churches = Church::query()->where('is_active', true)->orderBy('name')->get();
        $users = User::query()->orderBy('name')->get(['id', 'name']);
        $visit->load('attendees');

        return view('fieldoutreach::admin.visits.edit', compact('visit', 'churches', 'users'));
    }

    public function update(Request $request, FieldVisit $visit)
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
        $attendees = $data['attendee_ids'] ?? [];
        unset($data['attendee_ids']);
        $visit->update($data);
        $visit->attendees()->sync($attendees);

        return redirect()->route('admin.field.visits.show', $visit)->with('success', 'Atualizado.');
    }

    public function destroy(FieldVisit $visit)
    {
        abort_unless(Auth::user()?->canAccess('field_manage'), 403);
        $visit->delete();

        return redirect()->route('admin.field.visits.index')->with('success', 'Removido.');
    }
}
