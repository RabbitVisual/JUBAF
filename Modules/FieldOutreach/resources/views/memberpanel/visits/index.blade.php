@extends('memberpanel::components.layouts.master')

@section('page-title', 'Visitas de campo')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Visitas de campo',
        'subtitle' => auth()->user()->isYouthLeader() &&
        auth()->user()->canAccess('field_view') &&
        !auth()->user()->canAccess('field_manage')
            ? 'Apenas visitas à sua igreja local.'
            : 'Acompanhamento JUBAF nas igrejas.',
        'badge' => 'Campo',
    ])
        @slot('actions')
            @if (auth()->user()->canAccess('field_manage'))
                <a href="{{ route('memberpanel.field.visits.create') }}"
                    class="inline-flex items-center px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold shadow-lg shadow-teal-500/20">
                    <x-icon name="plus" style="duotone" class="w-4 h-4 mr-2" />
                    Nova visita
                </a>
            @endif
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.field.visits.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">
                    Admin técnico
                </a>
            @endif
        @endslot

        <a href="{{ route('memberpanel.field.dashboard') }}"
            class="inline-flex items-center text-sm font-semibold text-teal-600 dark:text-teal-400 hover:underline mb-6">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Visão geral
        </a>

        <div
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-5 md:p-6 shadow-sm mb-6">
            <form method="get" class="flex flex-wrap gap-4 items-end">
                <div class="w-full sm:w-auto sm:min-w-[220px]">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Filtrar por igreja</label>
                    <select name="church_id"
                        class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-sm py-2.5"
                        onchange="this.form.submit()">
                        <option value="">Todas</option>
                        @foreach ($churches as $c)
                            <option value="{{ $c->id }}" @selected(request('church_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm">
            <ul class="divide-y divide-gray-100 dark:divide-slate-800">
                @forelse($visits as $v)
                    <li>
                        <a href="{{ route('memberpanel.field.visits.show', $v) }}"
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 px-5 py-4 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $v->church?->name ?? 'Igreja' }}</p>
                                <p class="text-xs text-gray-500">{{ $v->visited_at?->format('d/m/Y') }} · {{ $v->creator?->name }}</p>
                            </div>
                            <x-icon name="chevron-right" class="w-4 h-4 text-gray-400 shrink-0 hidden sm:block" />
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-16 text-center text-gray-500 text-sm">Nenhuma visita encontrada.</li>
                @endforelse
            </ul>
        </div>
        <div class="mt-6">{{ $visits->links() }}</div>
    @endcomponent
@endsection
