<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SystemOpsController extends Controller
{
    public function index(Request $request): View
    {
        $queueConnection = (string) config('queue.default', 'sync');
        $queueConfig = config('queue.connections.'.$queueConnection) ?? [];
        $queueDriver = is_array($queueConfig) ? ($queueConfig['driver'] ?? 'unknown') : 'unknown';

        $jobsPending = null;
        if ($queueDriver === 'database' && Schema::hasTable('jobs')) {
            $jobsPending = (int) DB::table('jobs')->count();
        }

        $failedJobs = collect();
        if (Schema::hasTable('failed_jobs')) {
            $failedJobs = DB::table('failed_jobs')
                ->orderByDesc('failed_at')
                ->limit(12)
                ->get();
        }

        $backups = $this->listBackupsSummary();

        $logTail = $this->readLogTail((int) $request->query('log_lines', 120));

        return view('admin::ops.index', [
            'queueConnection' => $queueConnection,
            'queueDriver' => $queueDriver,
            'jobsPending' => $jobsPending,
            'failedJobs' => $failedJobs,
            'backups' => $backups,
            'logTail' => $logTail,
            'phpVersion' => PHP_VERSION,
            'laravelVersion' => app()->version(),
        ]);
    }

    /**
     * @return list<array{name: string, size: int, created_at: string}>
     */
    private function listBackupsSummary(): array
    {
        $backupPath = storage_path('app/backups');
        if (! File::exists($backupPath)) {
            return [];
        }

        $files = File::files($backupPath);
        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
            ];
        }

        usort($backups, fn ($a, $b) => strtotime($b['created_at']) <=> strtotime($a['created_at']));

        return $backups;
    }

    /**
     * @return list<string>
     */
    private function readLogTail(int $maxLines): array
    {
        $maxLines = max(20, min(400, $maxLines));
        $path = storage_path('logs/laravel.log');
        if (! File::exists($path)) {
            return ['(ficheiro de log não encontrado: storage/logs/laravel.log)'];
        }

        $maxBytes = 128 * 1024;
        $size = File::size($path);
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            return ['(não foi possível ler o ficheiro de log)'];
        }

        if ($size > $maxBytes) {
            fseek($handle, -$maxBytes, SEEK_END);
            fread($handle, 512);
        }

        $chunk = stream_get_contents($handle);
        fclose($handle);

        if ($chunk === false || $chunk === '') {
            return ['(log vazio)'];
        }

        $lines = preg_split("/\r\n|\n|\r/", $chunk) ?: [];
        $lines = array_values(array_filter($lines, fn ($l) => $l !== ''));

        return array_slice($lines, -$maxLines);
    }
}
