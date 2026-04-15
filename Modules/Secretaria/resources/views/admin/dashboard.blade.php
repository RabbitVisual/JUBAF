@extends('layouts.app')

@section('title', 'Secretaria JUBAF')

@section('content')
@php $sb = $secretariaBase; @endphp
<div class="space-y-8 max-w-7xl">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <x-module-icon module="Secretaria" class="h-9 w-9 shrink-0" />
                Secretaria
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Atas, reuniões, convocatórias e arquivo institucional.</p>
            <p class="text-xs text-violet-600 dark:text-violet-400 mt-2 font-medium">Super-admin — gestão global. A diretoria continua a operar em <span class="font-mono">/diretoria/secretaria</span>.</p>
        </div>
    </div>

    @if(session('success'))<div class="p-4 rounded-lg bg-emerald-50 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200 text-sm">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="p-4 rounded-lg bg-red-50 text-red-800 dark:bg-red-900/30 dark:text-red-200 text-sm">{{ session('error') }}</div>@endif

    @if((auth()->user()?->hasRole('super-admin') || user_is_diretoria_executive()) && ($pendingMinutes > 0 || $pendingConvocations > 0))
        <div class="rounded-xl border border-amber-200 dark:border-amber-900/40 bg-amber-50/90 dark:bg-amber-950/30 p-4">
            <p class="font-semibold text-amber-900 dark:text-amber-200">Fila executiva</p>
            <ul class="mt-2 text-sm text-amber-900/90 dark:text-amber-100/90 list-disc list-inside">
                @if($pendingMinutes > 0)<li>{{ $pendingMinutes }} ata(s) aguardam assinaturas.</li>@endif
                @if($pendingConvocations > 0)<li>{{ $pendingConvocations }} convocatória(s) pendentes.</li>@endif
            </ul>
            <a href="{{ route($sb.'.atas.index', ['status' => 'pending_signatures']) }}" class="mt-2 inline-block text-sm font-medium text-amber-800 dark:text-amber-300 underline">Ver atas</a>
        </div>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route($sb.'.reunioes.index') }}" class="rounded-xl border border-gray-200 dark:border-slate-700 p-4 hover:border-indigo-300 dark:hover:border-indigo-600 bg-white dark:bg-slate-800 text-sm font-medium text-gray-900 dark:text-white">Reuniões</a>
        <a href="{{ route($sb.'.atas.index') }}" class="rounded-xl border border-gray-200 dark:border-slate-700 p-4 hover:border-indigo-300 dark:hover:border-indigo-600 bg-white dark:bg-slate-800 text-sm font-medium text-gray-900 dark:text-white">Atas</a>
        <a href="{{ route($sb.'.convocatorias.index') }}" class="rounded-xl border border-gray-200 dark:border-slate-700 p-4 hover:border-indigo-300 dark:hover:border-indigo-600 bg-white dark:bg-slate-800 text-sm font-medium text-gray-900 dark:text-white">Convocatórias</a>
        <a href="{{ route($sb.'.arquivo.index') }}" class="rounded-xl border border-gray-200 dark:border-slate-700 p-4 hover:border-indigo-300 dark:hover:border-indigo-600 bg-white dark:bg-slate-800 text-sm font-medium text-gray-900 dark:text-white">Arquivo</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5">
            <h2 class="font-semibold text-gray-900 dark:text-white mb-3">Próximas reuniões</h2>
            <ul class="space-y-2 text-sm">
                @forelse($upcomingMeetings as $m)
                    <li class="flex justify-between gap-2"><span>{{ $m->title ?: $m->type }}</span><span class="text-gray-500">{{ $m->starts_at->format('d/m/Y H:i') }}</span></li>
                @empty
                    <li class="text-gray-500">Nenhuma registada.</li>
                @endforelse
            </ul>
        </div>
        <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5">
            <h2 class="font-semibold text-gray-900 dark:text-white mb-3">Convocatórias publicadas</h2>
            <ul class="space-y-2 text-sm">
                @forelse($extraordinarySoon as $c)
                    <li class="flex justify-between gap-2"><span>{{ $c->title }}</span><span class="text-gray-500">{{ $c->assembly_at->format('d/m/Y') }}</span></li>
                @empty
                    <li class="text-gray-500">Nenhuma.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
