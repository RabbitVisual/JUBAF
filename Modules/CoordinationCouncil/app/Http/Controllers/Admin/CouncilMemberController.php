<?php

namespace Modules\CoordinationCouncil\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CoordinationCouncil\Models\CouncilMember;

class CouncilMemberController extends Controller
{
    public function index()
    {
        $members = CouncilMember::query()->orderBy('full_name')->paginate(20);

        return view('coordinationcouncil::admin.members.index', compact('members'));
    }

    public function show(CouncilMember $member)
    {
        $member->loadCount('attendances');

        return view('coordinationcouncil::admin.members.show', compact('member'));
    }

    public function create()
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);

        return view('coordinationcouncil::admin.members.create');
    }

    public function store(Request $request)
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

        return redirect()->route('admin.council.members.index')->with('success', 'Membro registado.');
    }

    public function edit(CouncilMember $member)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);

        return view('coordinationcouncil::admin.members.edit', compact('member'));
    }

    public function update(Request $request, CouncilMember $member)
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

        return redirect()->route('admin.council.members.index')->with('success', 'Atualizado.');
    }

    public function destroy(CouncilMember $member)
    {
        abort_unless(Auth::user()?->canAccess('council_manage'), 403);
        $member->delete();

        return redirect()->route('admin.council.members.index')->with('success', 'Removido.');
    }
}
