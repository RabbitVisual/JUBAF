@extends('painellider::components.layouts.app')

@section('title', 'Validar competências')

@section('content')
<div class="mx-auto max-w-5xl space-y-8 px-4 pb-10 sm:px-6">
    <div class="border-b border-gray-200 pb-6 dark:border-slate-700">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-800 dark:text-emerald-400">Juventude local</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Validação de talentos</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Confirme as competências declaradas pelos jovens da sua congregação. Isto ajuda a diretoria regional a convocar equipas com segurança.</p>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse($profiles as $profile)
            <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="font-bold text-gray-900 dark:text-white">{{ $profile->user?->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $profile->user?->church?->name ?? '—' }}</p>
                    </div>
                </div>
                <ul class="mt-4 divide-y divide-gray-100 dark:divide-slate-700">
                    @foreach($profile->skills as $skill)
                        @if(empty($skill->pivot->validated_at))
                            <li class="flex flex-col gap-3 py-3 sm:flex-row sm:items-center sm:justify-between">
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $skill->name }}</span>
                                <form method="post" action="{{ route('lideres.talentos.validation.store') }}" class="shrink-0">
                                    @csrf
                                    <input type="hidden" name="talent_profile_id" value="{{ $profile->id }}">
                                    <input type="hidden" name="talent_skill_id" value="{{ $skill->id }}">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                                        Validar
                                    </button>
                                </form>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50/80 px-6 py-14 text-center dark:border-slate-600 dark:bg-slate-900/40">
                <p class="font-semibold text-gray-900 dark:text-white">Nenhuma competência pendente</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Quando os jovens registarem talentos, aparecerão aqui para validação.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
