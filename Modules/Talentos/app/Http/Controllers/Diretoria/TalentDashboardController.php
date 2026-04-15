<?php

namespace Modules\Talentos\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Talentos\App\Models\TalentAssignment;
use Modules\Talentos\App\Models\TalentProfile;

class TalentDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless(
            $user && ($user->can('talentos.directory.view') || $user->can('talentos.assignments.view')),
            403
        );

        $canDirectory = $user->can('talentos.directory.view');
        $canAssignments = $user->can('talentos.assignments.view');

        $profileTotal = null;
        $searchableTotal = null;
        $assignmentTotal = null;
        $assignmentInvitedPending = null;
        $recentProfiles = collect();
        $recentAssignments = collect();

        if ($canDirectory) {
            $profileTotal = TalentProfile::query()->count();
            $searchableTotal = TalentProfile::query()->where('is_searchable', true)->count();
            $recentProfiles = TalentProfile::query()
                ->with(['user.church'])
                ->orderByDesc('updated_at')
                ->limit(6)
                ->get();
        }

        if ($canAssignments) {
            $assignmentTotal = TalentAssignment::query()->count();
            $assignmentInvitedPending = TalentAssignment::query()
                ->where('status', TalentAssignment::STATUS_INVITED)
                ->count();
            $recentAssignments = TalentAssignment::query()
                ->with(['user', 'calendarEvent'])
                ->orderByDesc('id')
                ->limit(6)
                ->get();
        }

        return view('talentos::paineldiretoria.dashboard', [
            'layout' => 'layouts.app',
            'routePrefix' => 'diretoria.talentos',
            'canDirectory' => $canDirectory,
            'canAssignments' => $canAssignments,
            'profileTotal' => $profileTotal,
            'searchableTotal' => $searchableTotal,
            'assignmentTotal' => $assignmentTotal,
            'assignmentInvitedPending' => $assignmentInvitedPending,
            'recentProfiles' => $recentProfiles,
            'recentAssignments' => $recentAssignments,
        ]);
    }
}
