@extends($layout)

@section('title', 'Ficha — '.$member->name)

@section('content')
@php
    use Modules\Talentos\App\Models\TalentAssignment;
@endphp
<div class="mx-auto max-w-4xl space-y-8 pb-10">
    @include('talentos::paineldiretoria.partials.subnav', ['active' => 'directory'])

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <a href="{{ route('diretoria.talentos.directory.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-violet-700 hover:underline dark:text-violet-400">
                <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
                Voltar ao diretório
            </a>
            <h1 class="mt-3 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-600/25">
                    <x-module-icon module="Talentos" class="h-7 w-7" />
                </span>
                {{ $member->name }}
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $member->email }} @if($member->church) · {{ $member->church->name }} @endif</p>
        </div>
        @can('create', \Modules\Talentos\App\Models\TalentAssignment::class)
            <a href="{{ route('diretoria.talentos.assignments.create', ['user_id' => $member->id]) }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/25 transition hover:bg-violet-700">
                <x-icon name="envelope" class="h-4 w-4" style="solid" />
                Convidar para função
            </a>
        @endcan
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Perfil</h2>
        @if($profile->bio)
            <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-gray-700 dark:text-gray-300">{{ $profile->bio }}</p>
        @else
            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Sem apresentação registada.</p>
        @endif
        @if($profile->availability_text)
            <p class="mt-4 text-sm"><span class="font-semibold text-gray-600 dark:text-gray-400">Disponibilidade:</span> {{ $profile->availability_text }}</p>
        @endif
        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            Visível no diretório interno:
            @if($profile->is_searchable)
                <span class="font-semibold text-emerald-700 dark:text-emerald-400">sim</span>
            @else
                <span class="font-semibold text-gray-800 dark:text-gray-200">não</span>
            @endif
        </p>
        <div class="mt-6">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Competências</p>
            <div class="mt-2 flex flex-wrap gap-2">
                @forelse($profile->skills as $skill)
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-violet-100 px-2.5 py-1 text-xs font-semibold text-violet-900 dark:bg-violet-900/40 dark:text-violet-200">
                        {{ $skill->name }}
                        @if(\Modules\Talentos\App\Models\TalentSkill::levelLabel($skill->pivot->level ?? null))
                            <span class="rounded-md bg-white/70 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-violet-800 dark:bg-violet-950/50 dark:text-violet-200">{{ \Modules\Talentos\App\Models\TalentSkill::levelLabel($skill->pivot->level) }}</span>
                        @endif
                    </span>
                @empty
                    <span class="text-sm text-gray-500">—</span>
                @endforelse
            </div>
        </div>
        <div class="mt-6">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Áreas</p>
            <div class="mt-2 flex flex-wrap gap-2">
                @forelse($profile->areas as $area)
                    <span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-800 dark:bg-slate-700 dark:text-slate-200">{{ $area->name }}</span>
                @empty
                    <span class="text-sm text-gray-500">—</span>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Atribuições recentes</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Convites e confirmações ligados a eventos ou missões gerais.</p>
        <ul class="mt-4 divide-y divide-gray-100 dark:divide-slate-700">
            @forelse($member->talentAssignments as $a)
                <li class="flex flex-col gap-2 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $a->role_label }}</p>
                        @if($a->calendarEvent)
                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ $a->calendarEvent->title }} · {{ $a->calendarEvent->starts_at?->format('d/m/Y H:i') }}</p>
                        @else
                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Sem evento ligado</p>
                        @endif
                    </div>
                    @php
                        $stLabel = match ($a->status) {
                            TalentAssignment::STATUS_INVITED => ['Convidado', 'bg-amber-100 text-amber-950 ring-amber-200 dark:bg-amber-900/40 dark:text-amber-100 dark:ring-amber-800/50'],
                            TalentAssignment::STATUS_CONFIRMED => ['Confirmado', 'bg-emerald-100 text-emerald-950 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-100 dark:ring-emerald-800/50'],
                            TalentAssignment::STATUS_DECLINED => ['Declinou', 'bg-slate-200 text-slate-900 ring-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:ring-slate-600'],
                            default => [strtoupper($a->status), 'bg-gray-100 text-gray-800 ring-gray-200 dark:bg-slate-700 dark:text-gray-200 dark:ring-slate-600'],
                        };
                    @endphp
                    <span class="inline-flex w-fit shrink-0 rounded-lg px-2.5 py-1 text-xs font-bold ring-1 {{ $stLabel[1] }}">{{ $stLabel[0] }}</span>
                </li>
            @empty
                <li class="py-12 text-center text-sm text-gray-500 dark:text-gray-400">Sem atribuições registadas para este membro.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
