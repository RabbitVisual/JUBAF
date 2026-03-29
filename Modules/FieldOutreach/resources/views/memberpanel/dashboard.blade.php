@extends('memberpanel::components.layouts.master')

@section('page-title', 'Campo — JUBAF na estrada')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Campo',
        'subtitle' => 'Visitas e acompanhamento nas igrejas.',
        'badge' => 'Institucional',
    ])
        @slot('actions')
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.field.visits.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">
                    Admin técnico
                </a>
            @endif
        @endslot

        <div
            class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-800">
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div class="absolute -top-20 right-0 w-96 h-96 bg-teal-400 rounded-full blur-[100px]"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-emerald-400 rounded-full blur-[90px]"></div>
            </div>
            <div class="relative px-8 py-10 flex flex-col md:flex-row md:items-center justify-between gap-8 z-10">
                <div class="flex-1">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 dark:bg-teal-900/30 border border-teal-100 dark:border-teal-800 mb-4">
                        <x-icon name="route" style="duotone" class="w-3 h-3 text-teal-600 dark:text-teal-400" />
                        <span
                            class="text-[10px] font-black uppercase tracking-widest text-teal-600 dark:text-teal-400">Campo</span>
                    </div>
                    <p class="text-gray-500 dark:text-slate-300 font-medium max-w-xl text-lg leading-relaxed">
                        {{ $visitCount }} visita(s) registadas.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3 shrink-0">
                    <a href="{{ route('memberpanel.field.visits.index') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-xl text-sm font-bold border border-gray-200 dark:border-slate-700">
                        Ver visitas
                    </a>
                    @if ($canManage)
                        <a href="{{ route('memberpanel.field.visits.create') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-teal-500/20">
                            <x-icon name="plus" style="duotone" class="w-4 h-4 mr-2" />
                            Nova visita
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endcomponent
@endsection
