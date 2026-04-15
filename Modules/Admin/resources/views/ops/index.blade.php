@extends('admin::layouts.admin')

@section('title', 'Operações — sistema')

@section('content')
<div class="space-y-5 pb-10 font-mono text-xs text-slate-800 dark:text-slate-200">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-200 dark:border-slate-700 pb-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <x-icon name="gauge-high" class="w-5 h-5 text-rose-500" style="duotone" />
                Painel técnico (Ops)
            </h1>
            <p class="text-[11px] text-slate-500 mt-1">Filas, falhas, backups e tail do log — apenas super-admin.</p>
        </div>
        <div class="flex flex-wrap gap-2 text-[10px]">
            <span class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-600">PHP {{ $phpVersion }}</span>
            <span class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-600">Laravel {{ $laravelVersion }}</span>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-3">
        <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-3">
            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-1">Queue</p>
            <p class="font-semibold">{{ $queueConnection }} / {{ $queueDriver }}</p>
            @if ($jobsPending !== null)
                <p class="mt-2 text-slate-600 dark:text-slate-400">Jobs pendentes (tabela <code class="text-[10px]">jobs</code>): <strong>{{ $jobsPending }}</strong></p>
            @else
                <p class="mt-2 text-slate-500">Contagem de fila só disponível com driver <code class="text-[10px]">database</code> e tabela <code class="text-[10px]">jobs</code>.</p>
            @endif
            <p class="mt-2 text-[10px] text-slate-500">Garanta que <code>php artisan queue:work</code> (ou supervisor) está em execução em produção.</p>
        </div>
        <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-3 md:col-span-2">
            <p class="text-[10px] uppercase tracking-wider text-slate-500 mb-1">Backups</p>
            @if (count($backups) > 0)
                <p class="text-[11px]">Último: <strong>{{ $backups[0]['name'] }}</strong> — {{ $backups[0]['created_at'] }} — {{ number_format($backups[0]['size'] / 1024, 1) }} KB</p>
            @else
                <p class="text-[11px] text-slate-500">Nenhum ficheiro em <code class="text-[10px]">storage/app/backups</code>.</p>
            @endif
            <a href="{{ route('admin.backup.index') }}" class="inline-flex mt-2 text-[11px] font-semibold text-cyan-600 dark:text-cyan-400 hover:underline">Abrir gestão de backups →</a>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 overflow-hidden">
        <div class="px-3 py-2 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
            <span class="text-[10px] uppercase tracking-wider text-slate-500">Jobs falhados (recentes)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-[11px]">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-500">
                    <tr>
                        <th class="px-2 py-1.5 font-medium">ID</th>
                        <th class="px-2 py-1.5 font-medium">Fila</th>
                        <th class="px-2 py-1.5 font-medium">Quando</th>
                        <th class="px-2 py-1.5 font-medium">Excepção (início)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($failedJobs as $fj)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-2 py-1.5 tabular-nums">{{ $fj->id }}</td>
                            <td class="px-2 py-1.5">{{ $fj->queue ?? '—' }}</td>
                            <td class="px-2 py-1.5 whitespace-nowrap">{{ $fj->failed_at ?? '—' }}</td>
                            <td class="px-2 py-1.5 max-w-md truncate text-rose-700 dark:text-rose-300" title="{{ $fj->exception ?? '' }}">{{ \Illuminate\Support\Str::limit((string) ($fj->exception ?? ''), 120) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-2 py-3 text-slate-500">Nenhum registo em <code class="text-[10px]">failed_jobs</code> ou tabela inexistente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-950 text-slate-100 overflow-hidden">
        <div class="px-3 py-2 border-b border-slate-800 flex items-center justify-between">
            <span class="text-[10px] uppercase tracking-wider text-slate-400">Tail — storage/logs/laravel.log</span>
            <a href="{{ route('admin.ops.index', ['log_lines' => 200]) }}" class="text-[10px] text-cyan-400 hover:underline">200 linhas</a>
        </div>
        <pre class="p-3 max-h-[28rem] overflow-auto text-[10px] leading-relaxed whitespace-pre-wrap break-all">@foreach ($logTail as $line){{ $line }}
@endforeach</pre>
    </div>
</div>
@endsection
