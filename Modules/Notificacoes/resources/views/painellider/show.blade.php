@extends('layouts.app')

@section('title', 'Notificação')

@section('content')
<div class="max-w-3xl space-y-8 animate-fade-in pb-8">
    <div class="rounded-[2rem] border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="px-8 py-8 md:px-10 md:py-10 border-b border-slate-100 dark:border-slate-800 bg-gradient-to-br from-emerald-500/10 to-emerald-600/5 dark:from-emerald-950/30 dark:to-slate-900">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shrink-0">
                    <x-icon name="{{ ($notification->data['icon'] ?? null) ?: 'bell' }}" class="w-7 h-7" />
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:text-emerald-400 mb-2">JUBAF — Painel de líderes</p>
                    <h1 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white leading-tight">
                        {{ $notification->title ?? ($notification->data['title'] ?? 'Notificação') }}
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                        {{ $notification->created_at->translatedFormat('d \d\e F \d\e Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="px-8 py-8 md:px-10 md:py-10 prose prose-slate dark:prose-invert max-w-none">
            <p class="text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">{{ $notification->message ?? ($notification->data['message'] ?? 'Sem conteúdo adicional.') }}</p>
            @if(!empty($notification->action_url))
                <p class="mt-8">
                    <a href="{{ $notification->action_url }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-600/20">
                        <x-icon name="arrow-up-right-from-square" class="w-4 h-4" />
                        Abrir link
                    </a>
                </p>
            @endif
        </div>
    </div>

    <a href="{{ route('lideres.notificacoes.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
        <x-icon name="arrow-left" class="w-4 h-4" />
        Voltar à lista
    </a>
</div>
@endsection
