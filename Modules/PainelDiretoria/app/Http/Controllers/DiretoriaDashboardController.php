<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use App\Models\Devotional;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Igrejas\App\Models\Church;
use Modules\Talentos\App\Models\TalentAssignment;
use Modules\Talentos\App\Models\TalentProfile;

class DiretoriaDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load(['roles', 'church']);

        $quickStats = [
            'users_total' => User::query()->count(),
            'board_members' => BoardMember::query()->count(),
            'churches_total' => null,
            'finance_month_balance' => null,
            'calendar_upcoming' => null,
            'talent_profiles' => null,
            'talent_assignments' => null,
        ];

        if (class_exists(Church::class)
            && module_enabled('Igrejas')
            && $user->can('viewAny', Church::class)) {
            $quickStats['churches_total'] = Church::query()->count();
        }

        if (class_exists(Devotional::class)) {
            $quickStats['devotionals_published'] = Devotional::query()->where('status', Devotional::STATUS_PUBLISHED)->count();
        } else {
            $quickStats['devotionals_published'] = null;
        }

        if (class_exists(FinTransaction::class)
            && module_enabled('Financeiro')
            && $user->can('financeiro.dashboard.view')) {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $in = (float) FinTransaction::query()
                ->where('direction', 'in')
                ->whereBetween('occurred_on', [$start->toDateString(), $end->toDateString()])
                ->sum('amount');
            $out = (float) FinTransaction::query()
                ->where('direction', 'out')
                ->whereBetween('occurred_on', [$start->toDateString(), $end->toDateString()])
                ->sum('amount');
            $quickStats['finance_month_balance'] = $in - $out;
        }

        if (class_exists(CalendarEvent::class)
            && module_enabled('Calendario')
            && $user->can('viewAny', CalendarEvent::class)) {
            $quickStats['calendar_upcoming'] = CalendarEvent::query()
                ->where('starts_at', '>=', now()->startOfDay())
                ->where('starts_at', '<=', now()->addDays(60))
                ->count();
        }

        if (module_enabled('Talentos')) {
            if (class_exists(TalentProfile::class) && $user->can('talentos.directory.view')) {
                $quickStats['talent_profiles'] = TalentProfile::query()->count();
            }
            if (class_exists(TalentAssignment::class) && $user->can('talentos.assignments.view')) {
                $quickStats['talent_assignments'] = TalentAssignment::query()->count();
            }
        }

        return view('paineldiretoria::dashboard', compact('user', 'quickStats'));
    }
}
