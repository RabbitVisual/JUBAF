@extends('painellider::layouts.lideres')

@section('title', 'Banco de talentos — inscrição')

@section('lideres_content')
@php
    use Modules\Talentos\App\Models\TalentAssignment;
@endphp
<x-ui.lideres::page-shell class="mx-auto max-w-3xl space-y-8 pb-4">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <x-ui.lideres::hero
        variant="gradient"
        eyebrow="JUBAF · Liderança e serviço"
        title="Inscrição no banco de talentos"
        description="Como líder, a sua inscrição ajuda a diretoria regional JUBAF a coordenar equipas e a convocar voluntários alinhados com a congregação. Preencha as competências em que pode colaborar; poderá receber convites com função definida (por exemplo ligados a eventos no calendário).">
        <x-slot name="actions">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/20">
                <x-module-icon module="Talentos" class="h-9 w-9 text-white" />
            </span>
        </x-slot>
    </x-ui.lideres::hero>

    <div class="rounded-2xl border border-slate-200 bg-slate-50/90 p-5 dark:border-slate-700 dark:bg-slate-900/60">
        @if($enrollmentComplete)
            <p class="inline-flex items-center gap-2 rounded-lg bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100">
                <x-icon name="circle-check" class="h-4 w-4" style="solid" />
                Inscrição ativa no banco de talentos
            </p>
        @elseif($enrollmentStarted)
            <p class="text-sm text-slate-600 dark:text-slate-400">Complete apresentação ou áreas de serviço para facilitar convocações futuras.</p>
        @else
            <p class="text-sm text-slate-600 dark:text-slate-400">Ainda sem ficha — registe-se para integrar o banco de talentos da JUBAF.</p>
        @endif
        <ol class="mt-4 grid gap-2 text-xs text-slate-700 dark:text-slate-300 sm:grid-cols-3" aria-label="Passos da inscrição">
            <li class="flex items-start gap-2 rounded-lg border border-slate-200 bg-white px-2.5 py-2 dark:border-slate-600 dark:bg-slate-800">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-[11px] font-bold text-emerald-900 dark:bg-emerald-900/50 dark:text-emerald-100">1</span>
                <span><span class="font-semibold text-slate-900 dark:text-white">Perfil</span> — apresentação e disponibilidade</span>
            </li>
            <li class="flex items-start gap-2 rounded-lg border border-slate-200 bg-white px-2.5 py-2 dark:border-slate-600 dark:bg-slate-800">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-[11px] font-bold text-emerald-900 dark:bg-emerald-900/50 dark:text-emerald-100">2</span>
                <span><span class="font-semibold text-slate-900 dark:text-white">Competências</span> — níveis e áreas de serviço</span>
            </li>
            <li class="flex items-start gap-2 rounded-lg border border-slate-200 bg-white px-2.5 py-2 dark:border-slate-600 dark:bg-slate-800">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-[11px] font-bold text-emerald-900 dark:bg-emerald-900/50 dark:text-emerald-100">3</span>
                <span><span class="font-semibold text-slate-900 dark:text-white">Visibilidade</span> — aparecer no diretório da diretoria</span>
            </li>
        </ol>
    </div>

    @if($assignments->isNotEmpty())
        <section class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 sm:p-6 shadow-sm" aria-labelledby="talent-convocacoes-lider-heading">
            <h2 id="talent-convocacoes-lider-heading" class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <x-icon name="envelope-open-text" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" style="duotone" />
                Convocações da diretoria
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Responda aos convites pendentes com <span class="font-semibold text-gray-800 dark:text-gray-200">Confirmar disponibilidade</span> ou <span class="font-semibold text-gray-800 dark:text-gray-200">Não posso nesta data</span>. Em conflito de agenda, comunique à diretoria ou à sua igreja.</p>
            <ul class="mt-4 space-y-3">
                @foreach($assignments as $a)
                    <li class="rounded-xl border border-gray-100 dark:border-slate-700 bg-gray-50/80 dark:bg-slate-900/50 px-4 py-3">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $a->role_label }}</p>
                                @if($a->calendarEvent)
                                    <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $a->calendarEvent->title }}
                                        <span class="text-gray-400">·</span>
                                        {{ $a->calendarEvent->starts_at?->format('d/m/Y H:i') }}
                                    </p>
                                @else
                                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Convite sem evento específico no calendário</p>
                                @endif
                                @if(filled($a->notes))
                                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">{{ $a->notes }}</p>
                                @endif
                            </div>
                            <span class="inline-flex shrink-0 items-center rounded-lg px-2.5 py-1 text-xs font-bold uppercase tracking-wide
                                @if($a->status === TalentAssignment::STATUS_INVITED) bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-200
                                @elseif($a->status === TalentAssignment::STATUS_CONFIRMED) bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-200
                                @else bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 @endif">
                                @if($a->status === TalentAssignment::STATUS_INVITED) Pendente
                                @elseif($a->status === TalentAssignment::STATUS_CONFIRMED) Confirmado
                                @else Declinado @endif
                            </span>
                        </div>
                        @include('talentos::painel.partials.assignment-invite-actions', [
                            'a' => $a,
                            'routePrefix' => $routePrefix,
                            'panel' => 'lider',
                        ])
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    <section aria-labelledby="talent-form-lider-heading">
        <h2 id="talent-form-lider-heading" class="sr-only">Dados da inscrição</h2>
        @include('talentos::painel.partials.inscription-form', [
            'profile' => $profile,
            'skills' => $skills,
            'areas' => $areas,
            'routePrefix' => $routePrefix,
            'panel' => 'lider',
        ])
    </section>
</x-ui.lideres::page-shell>
@endsection
