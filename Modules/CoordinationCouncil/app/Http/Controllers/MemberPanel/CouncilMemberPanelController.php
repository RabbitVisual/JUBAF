<?php

namespace Modules\CoordinationCouncil\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CoordinationCouncil\Models\CouncilAttendance;
use Modules\CoordinationCouncil\Models\CouncilMeeting;
use Modules\CoordinationCouncil\Models\CouncilMember;

class CouncilMemberPanelController extends Controller
{
    public function dashboard()
    {
        abort_unless(Auth::user()?->canAccess('council_view'), 403);
        $canManage = Auth::user()?->canAccess('council_manage') ?? false;
        $memberCount = CouncilMember::query()->count();
        $meetingCount = CouncilMeeting::query()->count();

        return view('coordinationcouncil::memberpanel.dashboard', compact('canManage', 'memberCount', 'meetingCount'));
    }

    public function membersIndex()
    {
        abort_unless(Auth::user()?->canAccess('council_view'), 403);
        $members = CouncilMember::query()->orderBy('full_name')->paginate(20);

        return view('coordinationcouncil::memberpanel.members.index', compact('members'));
    }

    public function membersShow(CouncilMember $member)
    {
        abort_unless(Auth::user()?->canAccess('council_view'), 403);
        $member->loadCount('attendances');
        $canManage = Auth::user()?->canAccess('council_manage') ?? false;

        return view('coordinationcouncil::memberpanel.members.show', compact('member', 'canManage'));
    }

    public function membersCreate()
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);

        return view('coordinationcouncil::memberpanel.members.create');
    }

    public function membersStore(Request $request)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:40',
            'kind' => 'required|in:effective,supplement',
            'term_started_at' => 'nullable|date',
            'term_ended_at' => 'nullable|date|after_or_equal:term_started_at',
            'mandate_third' => 'nullable|integer|min:1|max:3',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        CouncilMember::create($data);

        return redirect()->route('memberpanel.council.members.index')->with('success', 'Membro registado.');
    }

    public function membersEdit(CouncilMember $member)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);

        return view('coordinationcouncil::memberpanel.members.edit', compact('member'));
    }

    public function membersUpdate(Request $request, CouncilMember $member)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:40',
            'kind' => 'required|in:effective,supplement',
            'term_started_at' => 'nullable|date',
            'term_ended_at' => 'nullable|date|after_or_equal:term_started_at',
            'mandate_third' => 'nullable|integer|min:1|max:3',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $member->update($data);

        return redirect()->route('memberpanel.council.members.index')->with('success', 'Atualizado.');
    }

    public function membersDestroy(CouncilMember $member)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $member->delete();

        return redirect()->route('memberpanel.council.members.index')->with('success', 'Removido.');
    }

    public function meetingsIndex()
    {
        abort_unless(Auth::user()?->canAccess('council_view'), 403);
        $meetings = CouncilMeeting::query()->orderByDesc('scheduled_at')->paginate(15);

        return view('coordinationcouncil::memberpanel.meetings.index', compact('meetings'));
    }

    public function meetingsCreate()
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $members = CouncilMember::query()->where('is_active', true)->orderBy('full_name')->get();

        return view('coordinationcouncil::memberpanel.meetings.create', compact('members'));
    }

    public function meetingsStore(Request $request)
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

        return redirect()->route('memberpanel.council.meetings.show', $meeting)->with('success', 'Reunião criada.');
    }

    public function meetingsShow(CouncilMeeting $meeting)
    {
        abort_unless(Auth::user()?->canAccess('council_view'), 403);
        $meeting->load(['attendances.member']);
        $members = CouncilMember::query()->where('is_active', true)->orderBy('full_name')->get();
        $canManage = Auth::user()?->canAccess('council_manage') ?? false;

        return view('coordinationcouncil::memberpanel.meetings.show', compact('meeting', 'members', 'canManage'));
    }

    public function meetingsEdit(CouncilMeeting $meeting)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);

        return view('coordinationcouncil::memberpanel.meetings.edit', compact('meeting'));
    }

    public function meetingsUpdate(Request $request, CouncilMeeting $meeting)
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

        return redirect()->route('memberpanel.council.meetings.show', $meeting)->with('success', 'Guardado.');
    }

    public function meetingsDestroy(CouncilMeeting $meeting)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $meeting->delete();

        return redirect()->route('memberpanel.council.meetings.index')->with('success', 'Removido.');
    }

    public function meetingsSaveAttendance(Request $request, CouncilMeeting $meeting)
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
