<?php

namespace Modules\CoordinationCouncil\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CoordinationCouncil\Models\CouncilAttendance;
use Modules\CoordinationCouncil\Models\CouncilMeeting;
use Modules\CoordinationCouncil\Models\CouncilMember;

class CouncilMeetingController extends Controller
{
    public function index()
    {
        $meetings = CouncilMeeting::query()->orderByDesc('scheduled_at')->paginate(15);

        return view('coordinationcouncil::admin.meetings.index', compact('meetings'));
    }

    public function create()
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $members = CouncilMember::query()->where('is_active', true)->orderBy('full_name')->get();

        return view('coordinationcouncil::admin.meetings.create', compact('members'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $data = $request->validate([
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'meeting_type' => 'required|in:ordinary,extraordinary',
            'quorum_required' => 'required|integer|min:1',
            'minutes_notes' => 'nullable|string',
        ]);
        $data['created_by'] = Auth::id();
        $meeting = CouncilMeeting::create($data);

        return redirect()->route('admin.council.meetings.show', $meeting)->with('success', 'Reunião criada.');
    }

    public function show(CouncilMeeting $meeting)
    {
        $meeting->load(['attendances.member']);
        $members = CouncilMember::query()->where('is_active', true)->orderBy('full_name')->get();

        return view('coordinationcouncil::admin.meetings.show', compact('meeting', 'members'));
    }

    public function edit(CouncilMeeting $meeting)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);

        return view('coordinationcouncil::admin.meetings.edit', compact('meeting'));
    }

    public function update(Request $request, CouncilMeeting $meeting)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $data = $request->validate([
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'meeting_type' => 'required|in:ordinary,extraordinary',
            'quorum_required' => 'required|integer|min:1',
            'quorum_actual' => 'nullable|integer|min:0',
            'minutes_notes' => 'nullable|string',
        ]);
        $meeting->update($data);

        return redirect()->route('admin.council.meetings.show', $meeting)->with('success', 'Guardado.');
    }

    public function destroy(CouncilMeeting $meeting)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $meeting->delete();

        return redirect()->route('admin.council.meetings.index')->with('success', 'Removido.');
    }

    public function saveAttendance(Request $request, CouncilMeeting $meeting)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $rows = $request->input('attendance', []);
        foreach ($rows as $memberId => $row) {
            CouncilAttendance::query()->updateOrCreate(
                [
                    'council_meeting_id' => $meeting->id,
                    'council_member_id' => (int) $memberId,
                ],
                [
                    'status' => $row['status'] ?? 'present',
                    'justification' => $row['justification'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Presenças atualizadas.');
    }
}
