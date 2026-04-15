@extends('layouts.app')

@section('title', 'Banco de talentos — inscrição')

@section('content')
    @php
        use Modules\Talentos\App\Models\TalentAssignment;
    @endphp
    <div class="space-y-8 max-w-3xl mx-auto pb-4">
        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-900/20 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <div
            class="relative overflow-hidden rounded-3xl border border-violet-200/80 dark:border-violet-900/40 bg-gradient-to-br from-violet-600 via-violet-700 to-indigo-800 p-6 sm:p-8 text-white shadow-lg shadow-violet-900/20">
            <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10 blur-2xl" aria-hidden="true"></div>
            <div class="relative flex flex-col sm:flex-row sm:items-start gap-4">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/20">
                    <x-module-icon module="Talentos" class="h-9 w-9 text-white" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold uppercase tracking-widest text-violet-200">JUBAF · Voluntariado</p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl">Inscrição no banco de talentos</h1>
                    <p class="mt-3 text-sm leading-relaxed text-violet-100">
                        Este é o sítio onde você se regista para a JUBAF saber em que pode ajudar. Quando a diretoria
                        precisar de pessoas para um culto, retiro, som, receção ou outra missão,
                        poderá consultar o diretório interno e enviar-lhe um <strong
                            class="font-semibold text-white">convite</strong> — especialmente se estiver integrado(a) num
                        evento do calendário.
                    </p>
                    @if ($enrollmentComplete)
                        <p
                            class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white/15 px-3 py-1.5 text-xs font-semibold text-white ring-1 ring-white/20">
                            <x-icon name="circle-check" class="h-4 w-4" style="solid" />
                            Inscrição ativa — obrigado por servir connosco
                        </p>
                    @elseif($enrollmentStarted)
                        <p class="mt-4 text-xs text-violet-200">Complete competências ou uma breve apresentação para
                            facilitar o contacto da equipe.</p>
                    @else
                        <p class="mt-4 text-xs text-violet-200">Ainda não tem ficha guardada — preencha abaixo para se
                            inscrever.</p>
                    @endif
                    <ol class="mt-5 grid gap-2 text-xs text-violet-100/95 sm:grid-cols-3" aria-label="Passos da inscrição">
                        <li class="flex items-start gap-2 rounded-lg bg-white/10 px-2.5 py-2 ring-1 ring-white/15">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-white/20 text-[11px] font-bold">1</span>
                            <span><span class="font-semibold text-white">Perfil</span> — apresentação e disponibilidade</span>
                        </li>
                        <li class="flex items-start gap-2 rounded-lg bg-white/10 px-2.5 py-2 ring-1 ring-white/15">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-white/20 text-[11px] font-bold">2</span>
                            <span><span class="font-semibold text-white">Competências</span> — o que pode oferecer</span>
                        </li>
                        <li class="flex items-start gap-2 rounded-lg bg-white/10 px-2.5 py-2 ring-1 ring-white/15">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-white/20 text-[11px] font-bold">3</span>
                            <span><span class="font-semibold text-white">Visibilidade</span> — integrar o diretório interno</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        @if ($assignments->isNotEmpty())
            <section
                class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 sm:p-6 shadow-sm"
                aria-labelledby="talent-convocacoes-heading">
                <h2 id="talent-convocacoes-heading"
                    class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="envelope-open-text" class="h-5 w-5 text-violet-600 dark:text-violet-400"
                        style="duotone" />
                    Convocações e convites
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Quando a diretoria o(a) convidar para uma função, responda abaixo enquanto o estado for <span class="font-semibold text-gray-800 dark:text-gray-200">Aguarda resposta</span>: use <span class="font-semibold text-gray-800 dark:text-gray-200">Confirmar disponibilidade</span> ou <span class="font-semibold text-gray-800 dark:text-gray-200">Não posso nesta data</span>. Em caso de dúvida, fale com a sua igreja ou com a diretoria.</p>
                <ul class="mt-4 space-y-3">
                    @foreach ($assignments as $a)
                        <li
                            class="rounded-xl border border-gray-100 dark:border-slate-700 bg-gray-50/80 dark:bg-slate-900/50 px-4 py-3">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $a->role_label }}</p>
                                    @if ($a->calendarEvent)
                                        <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $a->calendarEvent->title }}
                                            <span class="text-gray-400">·</span>
                                            {{ $a->calendarEvent->starts_at?->format('d/m/Y H:i') }}
                                        </p>
                                    @else
                                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Convite geral (sem evento
                                            ligado)</p>
                                    @endif
                                    @if (filled($a->notes))
                                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">{{ $a->notes }}</p>
                                    @endif
                                </div>
                                <span
                                    class="inline-flex shrink-0 items-center rounded-lg px-2.5 py-1 text-xs font-bold uppercase tracking-wide
                                @if ($a->status === TalentAssignment::STATUS_INVITED) bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-200
                                @elseif($a->status === TalentAssignment::STATUS_CONFIRMED) bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-200
                                @else bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 @endif">
                                    @if ($a->status === TalentAssignment::STATUS_INVITED)
                                        Aguarda resposta
                                    @elseif($a->status === TalentAssignment::STATUS_CONFIRMED)
                                        Confirmado
                                    @else
                                        Declinado
                                    @endif
                                </span>
                            </div>
                            @include('talentos::painel.partials.assignment-invite-actions', [
                                'a' => $a,
                                'routePrefix' => $routePrefix,
                                'panel' => 'jovens',
                            ])
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        <section aria-labelledby="talent-form-heading">
            <h2 id="talent-form-heading" class="sr-only">Dados da inscrição</h2>
            @include('talentos::painel.partials.inscription-form', [
                'profile' => $profile,
                'skills' => $skills,
                'areas' => $areas,
                'routePrefix' => $routePrefix,
                'panel' => 'jovens',
            ])
        </section>
    </div>
@endsection
