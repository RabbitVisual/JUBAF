@extends('painellider::layouts.lideres')

@section('title', 'Métricas da juventude')

@section('lideres_content')
    <x-ui.lideres::page-shell class="space-y-6 md:space-y-8 px-4 sm:px-6">
        <x-ui.lideres::hero
            variant="surface"
            eyebrow="Painel do líder"
            title="Juventude nas tuas congregações"
            description="Contagem de contas com o perfil «jovens» associadas às igrejas onde serves." />

        <div class="max-w-3xl rounded-2xl border border-emerald-200/80 bg-gradient-to-br from-emerald-50 to-white p-8 shadow-sm dark:border-emerald-900/40 dark:from-emerald-950/30 dark:to-slate-900">
            <p class="text-sm font-medium text-emerald-900/80 dark:text-emerald-200/90">Total de jovens</p>
            <p class="mt-2 text-4xl font-black tabular-nums text-emerald-700 dark:text-emerald-400">{{ $youthCount }}</p>
            @if(count($churchIds) === 0)
                <p class="mt-4 text-sm text-amber-800 dark:text-amber-200/90">Sem igreja associada ao teu perfil. Contacta a secretaria JUBAF para vincular a tua congregação.</p>
            @endif
        </div>
    </x-ui.lideres::page-shell>
@endsection
