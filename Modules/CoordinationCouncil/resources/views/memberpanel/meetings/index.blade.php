@extends('memberpanel::components.layouts.master')

@section('page-title', 'Reuniões do conselho')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Reuniões',
        'subtitle' => 'Conselho de coordenação.',
        'badge' => 'Conselho',
    ])
        @slot('actions')
            @if (auth()->user()->canAccess('council_manage'))
                <a href="{{ route('memberpanel.council.meetings.create') }}"
                    class="inline-flex items-center px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-lg shadow-indigo-500/20">
                    <x-icon name="plus" style="duotone" class="w-4 h-4 mr-2" />
                    Nova reunião
                </a>
            @endif
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.council.meetings.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">
                    Admin técnico
                </a>
            @endif
        @endslot

        <a href="{{ route('memberpanel.council.dashboard') }}"
            class="inline-flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline mb-6">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Visão geral
        </a>

        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm">
            <ul class="divide-y divide-gray-100 dark:divide-slate-800">
                @forelse($meetings as $mtg)
                    <li>
                        <a href="{{ route('memberpanel.council.meetings.show', $mtg) }}"
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 px-5 py-4 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $mtg->scheduled_at?->translatedFormat('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-500">Quórum: {{ $mtg->quorum_actual ?? '—' }} / {{ $mtg->quorum_required }} ·
                                {{ $mtg->meeting_type === 'extraordinary' ? 'Extraordinária' : 'Ordinária' }}</p>
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-16 text-center text-gray-500 text-sm">Sem reuniões.</li>
                @endforelse
            </ul>
        </div>
        <div class="mt-6">{{ $meetings->links() }}</div>
    @endcomponent
@endsection
