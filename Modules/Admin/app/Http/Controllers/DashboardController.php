<?php

namespace Modules\Admin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

// Core Models (Using aliases if Model not found to avoid crash, but using direct paths where known)

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // --- 1. Modules Overview ---
        $modules = Module::all();
        $modulesData = [];

        foreach ($modules as $module) {
            $modulesData[] = [
                'name' => $module->getName(),
                'alias' => $module->get('alias', $module->getLowerName()),
                'enabled' => $module->isEnabled(),
                'priority' => $module->get('priority', 0),
                'description' => $module->get('description', ''),
            ];
        }

        // Sort by priority
        usort($modulesData, function ($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });

        // --- 2. Core Statistics (Aggregated) ---
        $stats = [
            'total_users' => DB::table('users')->count(),
            'active_users' => DB::table('users')->where('is_active', true)->count(),
            'total_modules' => count($modulesData),
            'enabled_modules' => count(array_filter($modulesData, fn ($m) => $m['enabled'])),
        ];

        // --- 3. Treasury Stats (if module enabled) ---
        if (Module::has('Treasury') && Module::isEnabled('Treasury')) {
            $stats['treasury_balance'] = DB::table('financial_entries')->sum('amount');
            $stats['treasury_income_month'] = DB::table('financial_entries')
                ->where('type', 'income')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount');
            $stats['treasury_expense_month'] = DB::table('financial_entries')
                ->where('type', 'expense')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount');

            // Recent entries
            $recentEntries = DB::table('financial_entries')
                ->latest()
                ->take(5)
                ->get();
        } else {
            $stats['treasury_balance'] = 0;
            $recentEntries = collect([]);
        }

        // --- 4. Events Stats (if module enabled) ---
        if (Module::has('Events') && Module::isEnabled('Events')) {
            $stats['upcoming_events'] = DB::table('events')
                ->where('start_date', '>=', Carbon::now())
                ->count();
            $stats['recent_registrations'] = DB::table('event_registrations')
                ->whereMonth('created_at', Carbon::now()->month)
                ->count();

            $upcomingEvents = DB::table('events')
                ->where('start_date', '>=', Carbon::now())
                ->orderBy('start_date', 'asc')
                ->take(5)
                ->get();
        } else {
            $stats['upcoming_events'] = 0;
            $upcomingEvents = collect([]);
        }

        $directorateWidgets = [
            'draft_minutes' => 0,
            'published_minutes' => 0,
            'council_meetings_month' => 0,
            'field_visits_month' => 0,
        ];
        if (Schema::hasTable('governance_minutes')) {
            $directorateWidgets['draft_minutes'] = (int) DB::table('governance_minutes')->where('status', 'draft')->count();
            $directorateWidgets['published_minutes'] = (int) DB::table('governance_minutes')->where('status', 'published')->count();
        }
        if (Schema::hasTable('council_meetings')) {
            $directorateWidgets['council_meetings_month'] = (int) DB::table('council_meetings')
                ->whereMonth('scheduled_at', Carbon::now()->month)
                ->whereYear('scheduled_at', Carbon::now()->year)
                ->count();
        }
        if (Schema::hasTable('field_visits')) {
            $directorateWidgets['field_visits_month'] = (int) DB::table('field_visits')
                ->whereMonth('visited_at', Carbon::now()->month)
                ->whereYear('visited_at', Carbon::now()->year)
                ->count();
        }

        // --- 7. Charts Data ---
        // Growth Chart (Last 6 months users)
        $growthChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = DB::table('users')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $growthChart['labels'][] = $date->format('M/Y');
            $growthChart['data'][] = $count;
        }

        // Financial Chart (Last 6 months)
        $financialChart = ['labels' => [], 'income' => [], 'expense' => []];
        if (Module::has('Treasury') && Module::isEnabled('Treasury')) {
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $financialChart['labels'][] = $date->format('M');

                $income = DB::table('financial_entries')
                    ->where('type', 'income')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('amount');

                $expense = DB::table('financial_entries')
                    ->where('type', 'expense')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('amount'); // Expense stored as positive usually, math handled in chart

                $financialChart['income'][] = $income;
                $financialChart['expense'][] = $expense;
            }
        }

        return view('admin::dashboard', compact(
            'modulesData',
            'stats',
            'recentEntries',
            'upcomingEvents',
            'growthChart',
            'financialChart',
            'directorateWidgets'
        ));
    }
}
