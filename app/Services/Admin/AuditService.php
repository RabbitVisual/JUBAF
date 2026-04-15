<?php

namespace App\Services\Admin;

use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class AuditService
{
    /**
     * Get audit logs with filters
     */
    public function getAuditLogs(array $filters = [], int $perPage = 20)
    {
        $query = AuditLog::with('user');

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get audit statistics
     */
    public function getAuditStats(): array
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('audit_logs')) {
            return [
                'today' => 0,
                'week' => 0,
                'month' => 0,
                'total' => 0,
                'by_action' => collect(),
                'by_module' => collect(),
                'by_user' => collect(),
            ];
        }

        $today = now()->startOfDay();
        $week = now()->subWeek();
        $month = now()->subMonth();

        try {
            return [
                'today' => AuditLog::where('created_at', '>=', $today)->count(),
                'week' => AuditLog::where('created_at', '>=', $week)->count(),
                'month' => AuditLog::where('created_at', '>=', $month)->count(),
                'total' => AuditLog::count(),
                'by_action' => AuditLog::select('action', DB::raw('count(*) as total'))
                    ->groupBy('action')
                    ->orderBy('total', 'desc')
                    ->limit(10)
                    ->get(),
                'by_module' => AuditLog::select('module', DB::raw('count(*) as total'))
                    ->whereNotNull('module')
                    ->groupBy('module')
                    ->orderBy('total', 'desc')
                    ->get(),
                'by_user' => AuditLog::select('user_id', DB::raw('count(*) as total'))
                    ->whereNotNull('user_id')
                    ->groupBy('user_id')
                    ->orderBy('total', 'desc')
                    ->limit(10)
                    ->with('user')
                    ->get(),
            ];
        } catch (\Exception $e) {
            return [
                'today' => 0,
                'week' => 0,
                'month' => 0,
                'total' => 0,
                'by_action' => collect(),
                'by_module' => collect(),
                'by_user' => collect(),
            ];
        }
    }

    /**
     * Clean old audit logs
     */
    public function cleanOldLogs(int $days = 90): int
    {
        if ($days === 0) {
            $count = AuditLog::count();
            AuditLog::truncate();
            return (int) $count;
        }

        $date = now()->subDays($days);
        return (int) AuditLog::where('created_at', '<', $date)->delete();
    }
}
