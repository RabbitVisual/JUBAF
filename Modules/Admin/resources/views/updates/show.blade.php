@extends('layouts.app')

@section('title', 'Detalhes da Atualização')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    <header class="overflow-hidden rounded-3xl border border-indigo-100/90 bg-gradient-to-br from-indigo-50/90 via-white to-white p-6 shadow-sm dark:border-indigo-900/25 dark:from-indigo-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Pacote · Detalhes</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Conteúdo e estado</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    {{ \Illuminate\Support\Str::limit($update['original_name'] ?? 'Pacote', 80) }}
                </p>
                <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Admin</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <a href="{{ route('admin.updates.index') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Atualizações</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <span class="font-medium text-gray-800 dark:text-slate-300">Visualizar</span>
                </nav>
            </div>
            <div class="flex shrink-0 flex-wrap gap-2">
                <a href="{{ route('admin.updates.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Voltar
                </a>
                @if ($update['status'] === 'uploaded' || $update['status'] === 'failed')
                    <form action="{{ route('admin.updates.apply', $update['id']) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja aplicar esta atualização? O sistema pode ficar indisponível por alguns segundos.');">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-500/20 transition hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 active:scale-95 dark:focus:ring-emerald-800 group">
                            <x-icon name="bolt" class="h-5 w-5 transition-transform group-hover:scale-110" />
                            Aplicar agora
                        </button>
                    </form>
                @endif
                <form action="{{ route('admin.updates.destroy', $update['id']) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir este pacote de atualização?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-bold text-rose-600 transition hover:bg-rose-100 focus:ring-4 focus:ring-rose-100 active:scale-95 dark:border-rose-900/30 dark:bg-rose-900/20 dark:text-rose-400 dark:hover:bg-rose-900/40">
                        <x-icon name="trash" class="h-5 w-5" />
                    </button>
                </form>
            </div>
        </div>
    </header>

    @if (isset($update['error']))
        <div class="animate-shake rounded-2xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-950/30">
            <div class="flex items-start gap-3">
                <x-icon name="circle-exclamation" class="mt-0.5 h-6 w-6 shrink-0 text-rose-500" />
                <div>
                    <h3 class="mb-1 text-sm font-bold text-rose-800 dark:text-rose-400">Erro na aplicação</h3>
                    <p class="text-sm text-rose-700 dark:text-rose-300">{{ $update['error'] }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="space-y-8 lg:col-span-2">
            <div class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                    <div>
                        <h2 class="flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                            <x-icon name="file-zipper" style="duotone" class="h-5 w-5 text-indigo-500" />
                            Conteúdo do pacote
                        </h2>
                        <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Ficheiros listados no arquivo</p>
                    </div>
                    <span class="text-xs font-medium text-slate-500">
                        {{ isset($files) ? count($files) : 0 }} ficheiros
                    </span>
                </div>

                <div class="max-h-[500px] overflow-y-auto p-0">
                    @if (isset($files) && count($files) > 0)
                        <table class="w-full text-left text-sm">
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                @foreach ($files as $file)
                                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                        <td class="px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <x-icon name="file-code" class="h-4 w-4 shrink-0 text-slate-400" />
                                                <span class="break-all font-mono text-xs text-slate-600 dark:text-slate-400">{{ $file }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="px-6 py-16 text-center">
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">
                                <x-icon name="folder-open" class="h-7 w-7 text-slate-400" style="duotone" />
                            </span>
                            <p class="mt-4 text-sm font-medium text-slate-600 dark:text-slate-400">Nenhum ficheiro listado ou erro ao ler o pacote.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6 lg:col-span-1">
            @php
                $statusColors = [
                    'uploaded' => 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 border-blue-100 dark:border-blue-900/30',
                    'applying' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-900/20 dark:text-yellow-400 border-yellow-100 dark:border-yellow-900/30',
                    'applied' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/30',
                    'failed' => 'bg-rose-50 text-rose-600 dark:bg-rose-900/20 dark:text-rose-400 border-rose-100 dark:border-rose-900/30',
                    'rolled_back' => 'border-gray-200 bg-gray-50 text-gray-600 dark:border-gray-700 dark:bg-gray-900/20 dark:text-gray-400',
                ];
                $statusIcons = [
                    'uploaded' => 'cloud-arrow-up',
                    'applying' => 'spinner',
                    'applied' => 'check-circle',
                    'failed' => 'circle-exclamation',
                    'rolled_back' => 'rotate-left',
                ];
                $statusLabels = [
                    'uploaded' => 'Pronto para aplicar',
                    'applying' => 'A instalar…',
                    'applied' => 'Instalado com sucesso',
                    'failed' => 'Falha na instalação',
                    'rolled_back' => 'Revertido',
                ];
                $statusKey = $update['status'] ?? 'uploaded';
            @endphp

            <div class="relative overflow-hidden rounded-3xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <h3 class="mb-4 text-xs font-bold uppercase tracking-wider text-slate-500">Estado actual</h3>
                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border {{ $statusColors[$statusKey] ?? $statusColors['uploaded'] }}">
                        <x-icon name="{{ $statusIcons[$statusKey] ?? 'cloud-arrow-up' }}" class="h-6 w-6 {{ $statusKey === 'applying' ? 'animate-spin' : '' }}" />
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 dark:text-white">{{ $statusLabels[$statusKey] ?? $statusKey }}</p>
                        <p class="text-xs text-slate-400">
                            {{ isset($update['updated_at']) ? \Carbon\Carbon::parse($update['updated_at'])->diffForHumans() : '' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4 border-t border-gray-100 pt-4 dark:border-slate-700">
                    <div>
                        <p class="mb-1 text-xs text-slate-500">Arquivo</p>
                        <p class="truncate text-sm font-bold text-gray-900 dark:text-white" title="{{ $update['original_name'] }}">{{ $update['original_name'] }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="mb-1 text-xs text-slate-500">Tamanho</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($update['size'] / 1024, 2) }} KB</p>
                        </div>
                        <div>
                            <p class="mb-1 text-xs text-slate-500">Data</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($update['created_at'])->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if (isset($update['options']['create_backup']) && $update['options']['create_backup'])
                <div class="rounded-3xl border border-indigo-100 bg-indigo-50/90 p-6 dark:border-indigo-900/30 dark:bg-indigo-950/25">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="rounded-lg bg-white p-2 text-indigo-600 shadow-sm dark:bg-indigo-900/40 dark:text-indigo-400">
                            <x-icon name="shield-check" class="h-5 w-5" />
                        </div>
                        <h3 class="font-bold text-indigo-900 dark:text-indigo-200">Backup de segurança</h3>
                    </div>

                    @if (isset($backup) && is_array($backup) && ! empty($backup['file_name']))
                        <div class="mb-4 rounded-xl border border-indigo-100 bg-white p-4 dark:border-indigo-900/30 dark:bg-slate-800">
                            <div class="flex items-center gap-3">
                                <x-icon name="file-zipper" class="h-5 w-5 shrink-0 text-indigo-500" />
                                <div class="min-w-0 overflow-hidden">
                                    <p class="truncate text-xs font-bold text-indigo-900 dark:text-indigo-300">{{ $backup['file_name'] }}</p>
                                    <p class="text-[10px] text-slate-500">Backup automático pré-instalação</p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.updates.downloadBackup', $update['id']) }}" class="block w-full rounded-lg border border-indigo-200 bg-white py-2.5 text-center text-xs font-bold uppercase tracking-wide text-indigo-600 shadow-sm transition hover:bg-indigo-50 dark:border-indigo-900/40 dark:bg-slate-900 dark:text-indigo-400 dark:hover:bg-indigo-950/40">
                            Baixar backup
                        </a>
                    @else
                        <p class="text-sm text-indigo-800 dark:text-indigo-300">Backup configurado para ser criado antes da instalação.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
