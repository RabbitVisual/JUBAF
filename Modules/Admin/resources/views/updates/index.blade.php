@extends('layouts.app')

@section('title', 'Atualizações do Sistema')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    <header class="overflow-hidden rounded-3xl border border-indigo-100/90 bg-gradient-to-br from-indigo-50/90 via-white to-white p-6 shadow-sm dark:border-indigo-900/25 dark:from-indigo-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Sistema · Manutenção</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Central de atualizações</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Envie pacotes <strong class="font-semibold text-gray-800 dark:text-slate-200">.zip</strong>, acompanhe o histórico e restaure backups quando necessário. Ideal para manter o JUBAF alinhado com builds internos.
                </p>
                <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Admin</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <span class="font-medium text-gray-800 dark:text-slate-300">Atualizações</span>
                </nav>
            </div>
            <div class="shrink-0">
                <a href="{{ route('admin.updates.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300/50 dark:focus:ring-indigo-900/50">
                    <x-icon name="cloud-arrow-up" class="h-5 w-5" style="duotone" />
                    Enviar atualização
                </a>
            </div>
        </div>
    </header>

    <div class="flex gap-4 rounded-2xl border border-sky-200/80 bg-sky-50/90 p-4 dark:border-sky-900/40 dark:bg-sky-950/30 md:items-center md:p-5">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-200/80 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200">
            <x-icon name="circle-info" class="h-5 w-5" style="duotone" />
        </span>
        <p class="min-w-0 text-sm leading-relaxed text-sky-950/90 dark:text-sky-100/90">
            <span class="font-semibold text-sky-900 dark:text-sky-100">Antes de aplicar</span>
            — confirme o conteúdo do pacote e mantenha <strong class="font-semibold">backup activado</strong> no upload. O ambiente pode ficar brevemente indisponível durante a instalação.
        </p>
    </div>

    @php
        $appVersion = config('app.version', '1.0.0');
        $lastSentAt = $lastUpdate && isset($lastUpdate['created_at'])
            ? \Carbon\Carbon::parse($lastUpdate['created_at'])->format('d/m/Y H:i')
            : null;
        $lastPkgName = $lastUpdate['original_name'] ?? null;
        $laravelVer = $systemInfo['laravel_version'] ?? app()->version();
        $phpVer = $systemInfo['php_version'] ?? PHP_VERSION;
    @endphp
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10 blur-2xl"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Versão da aplicação</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $appVersion }}</p>
            <p class="mt-2 line-clamp-2 text-xs text-gray-500 dark:text-slate-400" title="{{ $lastPkgName }}">
                @if ($lastPkgName)
                    Último pacote: <span class="font-medium text-gray-700 dark:text-slate-300">{{ \Illuminate\Support\Str::limit($lastPkgName, 42) }}</span>
                @else
                    Ainda não há pacotes no histórico.
                @endif
            </p>
        </div>
        <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10 blur-2xl"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Último envio</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $lastSentAt ?? '—' }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Registo mais recente no histórico</p>
        </div>
        <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:col-span-2 lg:col-span-1">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-violet-500/10 blur-2xl"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Ambiente</p>
            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">Laravel {{ $laravelVer }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">PHP {{ $phpVer }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-200" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
        <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Histórico de atualizações</h2>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Pacotes enviados, datas de aplicação e estado</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50/90 dark:bg-slate-900/60">
                    <tr>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">Versão / ficheiro</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">Data</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">Estado</th>
                        <th scope="col" class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">Acções</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:divide-slate-700 dark:bg-slate-800/40">
                    @forelse($updates as $update)
                        <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-slate-700/30">
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                                        <x-icon name="file-zipper" class="h-5 w-5" style="duotone" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $update['original_name'] }}</div>
                                        <div class="text-xs text-gray-500 dark:text-slate-400">{{ number_format($update['size'] / 1024, 2) }} KB</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-slate-400 sm:px-6">
                                {{ \Carbon\Carbon::parse($update['created_at'])->format('d/m/Y H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                @php
                                    $statusColors = [
                                        'applied' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300',
                                        'failed' => 'bg-rose-100 text-rose-800 dark:bg-rose-950/50 dark:text-rose-300',
                                        'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300',
                                        'rolled_back' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                                        'uploaded' => 'bg-blue-100 text-blue-800 dark:bg-blue-950/50 dark:text-blue-300',
                                    ];
                                    $statusKey = $update['status'] ?? 'pending';
                                @endphp
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusColors[$statusKey] ?? $statusColors['pending'] }}">
                                    {{ strtoupper($update['status'] ?? '—') }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right sm:px-6">
                                <a href="{{ route('admin.updates.show', $update['id']) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Detalhes
                                    <x-icon name="arrow-right" class="h-4 w-4" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="mx-auto flex max-w-md flex-col items-center">
                                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/40">
                                        <x-icon name="box-open" class="h-8 w-8 text-indigo-400" style="duotone" />
                                    </span>
                                    <p class="mt-4 text-base font-semibold text-gray-900 dark:text-white">Nenhum histórico ainda</p>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">Envie um pacote .zip para começar o registo de actualizações neste ambiente.</p>
                                    <a href="{{ route('admin.updates.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-indigo-700">
                                        <x-icon name="cloud-arrow-up" class="h-4 w-4" style="duotone" />
                                        Enviar primeira actualização
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
