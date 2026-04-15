@extends('painellider::components.layouts.app')

@section('title', 'Métricas da juventude')

@section('content')
<div class="mx-auto max-w-3xl space-y-8 px-4 pb-10 sm:px-6">
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-800 dark:text-emerald-400">Painel do líder</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Juventude nas tuas congregações</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Contagem de contas com o perfil «jovens» associadas às igrejas onde serves.</p>
    </div>

    <div class="rounded-2xl border border-emerald-200/80 bg-gradient-to-br from-emerald-50 to-white p-8 shadow-sm dark:border-emerald-900/40 dark:from-emerald-950/30 dark:to-slate-900">
        <p class="text-sm font-medium text-emerald-900/80 dark:text-emerald-200/90">Total de jovens</p>
        <p class="mt-2 text-4xl font-black tabular-nums text-emerald-700 dark:text-emerald-400">{{ $youthCount }}</p>
        @if(count($churchIds) === 0)
            <p class="mt-4 text-sm text-amber-800 dark:text-amber-200/90">Sem igreja associada ao teu perfil. Contacta a secretaria JUBAF para vincular a tua congregação.</p>
        @endif
    </div>
</div>
@endsection
