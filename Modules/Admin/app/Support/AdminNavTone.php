<?php

namespace Modules\Admin\App\Support;

/**
 * Tailwind classes for admin sidebar rows (aligned with legacy superadmin sidebar).
 */
final class AdminNavTone
{
    public static function row(string $tone, bool $active): string
    {
        return match ($tone) {
            'emerald' => $active
                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
            'emerald_sub' => $active
                ? 'text-emerald-800 dark:text-emerald-300 bg-emerald-50/80 dark:bg-emerald-950/40'
                : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-700',
            'indigo' => $active
                ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
            'amber' => $active
                ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
            'amber_bible' => $active
                ? 'bg-amber-100 text-amber-900 dark:bg-amber-900/30 dark:text-amber-300'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
            'violet' => $active
                ? 'bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-300'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
            'blue' => $active
                ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
            'orange' => $active
                ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
            'orange_sub' => $active
                ? 'text-orange-700 dark:text-orange-400 font-medium'
                : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white',
            'red' => $active
                ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
            'slate' => $active
                ? 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
            'slate_sub' => $active
                ? 'text-slate-700 dark:text-slate-400 font-medium'
                : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white',
            'cyan' => $active
                ? 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
            default => $active
                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700',
        };
    }

    public static function iconWrap(string $tone, bool $active): string
    {
        return match ($tone) {
            'emerald', 'indigo', 'amber', 'blue', 'orange', 'red', 'slate', 'cyan' => $active
                ? match ($tone) {
                    'emerald' => 'bg-emerald-500 dark:bg-emerald-600',
                    'indigo' => 'bg-indigo-500 dark:bg-indigo-600',
                    'amber' => 'bg-amber-500 dark:bg-amber-600',
                    'blue' => 'bg-blue-500 dark:bg-blue-600',
                    'orange' => 'bg-orange-500 dark:bg-orange-600',
                    'red' => 'bg-red-500 dark:bg-red-600',
                    'slate' => 'bg-slate-500 dark:bg-slate-600',
                    'cyan' => 'bg-cyan-500 dark:bg-cyan-600',
                    default => 'bg-emerald-500 dark:bg-emerald-600',
                }
                : 'bg-gray-100 dark:bg-slate-700',
            'amber_bible' => $active
                ? 'bg-amber-600 dark:bg-amber-700'
                : 'bg-gray-100 dark:bg-slate-700',
            'violet' => $active
                ? 'bg-violet-600 dark:bg-violet-700'
                : 'bg-gray-100 dark:bg-slate-700',
            default => $active
                ? 'bg-emerald-500 dark:bg-emerald-600'
                : 'bg-gray-100 dark:bg-slate-700',
        };
    }

    public static function iconText(string $tone, bool $active): string
    {
        return $active ? 'text-white' : 'text-gray-600 dark:text-gray-400';
    }
}
