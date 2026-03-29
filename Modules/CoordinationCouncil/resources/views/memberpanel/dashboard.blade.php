@extends('memberpanel::components.layouts.master')

@section('page-title', 'Conselho de coordenação')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Conselho de coordenação',
        'subtitle' => 'Membros, reuniões e registo de presenças no painel.',
        'badge' => 'Institucional',
    ])
        @slot('actions')
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.council.members.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">
                    Admin técnico
                </a>
            @endif
        @endslot

        <div
            class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-800">
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div class="absolute -top-24 -left-20 w-96 h-96 bg-indigo-400 rounded-full blur-[100px]"></div>
                <div class="absolute bottom-0 right-0 w-80 h-80 bg-teal-400 rounded-full blur-[100px]"></div>
            </div>
            <div class="relative px-8 py-10 flex flex-col md:flex-row md:items-center justify-between gap-8 z-10">
                <div class="flex-1">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 mb-4">
                        <x-icon name="people-group" style="duotone" class="w-3 h-3 text-indigo-600 dark:text-indigo-400" />
                        <span
                            class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Conselho</span>
                    </div>
                    <p class="text-gray-500 dark:text-slate-300 font-medium max-w-xl text-lg leading-relaxed">
                        {{ $memberCount }} membro(s) · {{ $meetingCount }} reunião(ões).
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('memberpanel.council.members.index') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 rounded-xl text-sm font-bold border border-gray-200 dark:border-slate-700">
                        Membros
                    </a>
                    <a href="{{ route('memberpanel.council.meetings.index') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-white dark:bg-slate-800 border border-indigo-200 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 rounded-xl text-sm font-bold">
                        Reuniões
                    </a>
                    @if ($canManage)
                        <a href="{{ route('memberpanel.council.members.create') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/20">
                            <x-icon name="plus" style="duotone" class="w-4 h-4 mr-2" />
                            Novo membro
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endcomponent
@endsection
