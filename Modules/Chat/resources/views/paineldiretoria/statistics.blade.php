@extends('layouts.app')

@section('title', 'Chat — Estatísticas')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('chat::paineldiretoria.partials.subnav', ['active' => 'statistics'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Indicadores</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                    <x-icon name="chart-line" class="h-5 w-5" style="duotone" />
                </span>
                Estatísticas do chat
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Resumo do atendimento público e séries recentes (30 dias onde aplicável).</p>
        </div>
        <a href="{{ route('diretoria.chat.index') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
            <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
            Voltar às sessões
        </a>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @foreach([
            ['label' => 'Sessões totais', 'value' => $stats['total'] ?? 0, 'icon' => 'comments'],
            ['label' => 'Aguardando', 'value' => $stats['waiting'] ?? 0, 'icon' => 'clock'],
            ['label' => 'Em atendimento', 'value' => $stats['active'] ?? 0, 'icon' => 'message-dots'],
            ['label' => 'Mensagens hoje', 'value' => $stats['messages_today'] ?? 0, 'icon' => 'paper-plane'],
        ] as $card)
            <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                        <x-icon name="{{ $card['icon'] }}" class="h-5 w-5" style="duotone" />
                    </span>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                        <p class="text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $card['value'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="mb-4 text-sm font-bold text-gray-900 dark:text-white">Sessões por dia</h2>
            <ul class="max-h-80 space-y-0 overflow-y-auto text-sm">
                @forelse($sessionsPerDay as $row)
                    <li class="flex justify-between gap-4 border-b border-gray-100 py-2.5 last:border-0 dark:border-slate-700">
                        <span class="text-gray-600 dark:text-gray-400">{{ $row->date }}</span>
                        <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ $row->count }}</span>
                    </li>
                @empty
                    <li class="py-8 text-center text-gray-500 dark:text-gray-400">Sem dados no período.</li>
                @endforelse
            </ul>
        </div>
        <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="mb-4 text-sm font-bold text-gray-900 dark:text-white">Mensagens por tipo de remetente</h2>
            <ul class="space-y-0 text-sm">
                @forelse($messagesByType as $row)
                    <li class="flex justify-between gap-4 border-b border-gray-100 py-2.5 last:border-0 dark:border-slate-700">
                        <span class="font-medium text-gray-600 dark:text-gray-400">{{ $row->sender_type }}</span>
                        <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ $row->count }}</span>
                    </li>
                @empty
                    <li class="py-8 text-center text-gray-500 dark:text-gray-400">Sem dados.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
