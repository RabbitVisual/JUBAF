@extends('paineljovens::layouts.jovens')

@section('title', 'Banco de talentos — inscrição')


@section('jovens_content')
    @php
        use Modules\Talentos\App\Models\TalentAssignment;
    @endphp
    <x-ui.jovens::page-shell class="max-w-3xl space-y-8">
        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-900/20 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <header
            class="relative overflow-hidden rounded-[2rem] border border-gray-200/90 dark:border-gray-800 bg-gradient-to-br from-blue-700 via-blue-800 to-gray-900 p-6 text-white shadow-xl sm:p-8">
            <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-start">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 shadow-lg ring-1 ring-white/25">
                    <x-module-icon module="Talentos" class="h-9 w-9 text-white" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold uppercase tracking-widest text-blue-200/90">JUBAF · Voluntariado</p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl">Inscrição no banco de talentos</h1>
                    <p class="mt-3 text-sm leading-relaxed text-blue-100/95">
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
                        <p class="mt-4 text-xs text-blue-200">Complete competências ou uma breve apresentação para
                            facilitar o contacto da equipe.</p>
                    @else
                        <p class="mt-4 text-xs text-blue-200">Ainda não tem ficha guardada — preencha abaixo para se
                            inscrever.</p>
                    @endif
                    <ol class="mt-5 grid gap-2 text-xs text-blue-100/95 sm:grid-cols-3" aria-label="Passos da inscrição">
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
        </header>

        @isset($verifiedSkills)
            @if ($verifiedSkills->isNotEmpty())
                <section class="rounded-2xl border border-emerald-200/80 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm dark:border-emerald-900/40 dark:from-emerald-950/30 dark:to-gray-900 sm:p-6"
                    aria-labelledby="talent-portfolio-heading">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 id="talent-portfolio-heading" class="text-lg font-bold text-gray-900 dark:text-white">Portfólio verificado</h2>
                        <x-ui.jovens::status-pill label="Validado pelo líder" variant="success" />
                    </div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Competências confirmadas pela equipa de juventude / diretoria.</p>
                    <ul class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        @foreach ($verifiedSkills as $sk)
                            <li
                                class="flex items-center justify-between gap-2 rounded-xl border border-emerald-100 bg-white/90 px-4 py-3 dark:border-emerald-900/40 dark:bg-gray-900/80">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $sk->name }}</span>
                                @if (\Modules\Talentos\App\Models\TalentSkill::levelLabel($sk->pivot->level ?? null))
                                    <span
                                        class="shrink-0 rounded-lg bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase text-emerald-900 dark:bg-emerald-900/50 dark:text-emerald-100">{{ \Modules\Talentos\App\Models\TalentSkill::levelLabel($sk->pivot->level) }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        @endisset

        @if ($assignments->isNotEmpty())
            <section
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6"
                aria-labelledby="talent-convocacoes-heading">
                <h2 id="talent-convocacoes-heading"
                    class="flex items-center gap-2 text-lg font-bold text-gray-900 dark:text-white">
                    <x-icon name="envelope-open-text" class="h-5 w-5 text-blue-600 dark:text-blue-400"
                        style="duotone" />
                    Convocações e convites
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Quando a diretoria o(a) convidar para uma função, responda abaixo enquanto o estado for <span class="font-semibold text-gray-800 dark:text-gray-200">Aguarda resposta</span>: use <span class="font-semibold text-gray-800 dark:text-gray-200">Confirmar disponibilidade</span> ou <span class="font-semibold text-gray-800 dark:text-gray-200">Não posso nesta data</span>. Em caso de dúvida, fale com a sua igreja ou com a diretoria.</p>
                <ul class="mt-4 space-y-3">
                    @foreach ($assignments as $a)
                        <li
                            class="rounded-xl border border-gray-100 bg-gray-50/80 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/50">
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
                                @else bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
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
    </x-ui.jovens::page-shell>
@endsection
