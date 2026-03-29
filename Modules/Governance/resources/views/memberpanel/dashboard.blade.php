@extends('memberpanel::components.layouts.master')

@section('page-title', 'Governança')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Governança',
        'subtitle' => 'Assembleias, atas e comunicados oficiais — tudo no painel do membro.',
        'badge' => 'Institucional',
    ])
        @slot('actions')
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.governance.assemblies.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                    Admin técnico
                </a>
            @endif
        @endslot

        @if (session('success'))
            <div
                class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm border border-emerald-100 dark:border-emerald-900/40">
                {{ session('success') }}</div>
        @endif

        <div
            class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl shadow-xl dark:shadow-2xl border border-gray-100 dark:border-slate-800">
            <div class="absolute inset-0 opacity-20 dark:opacity-40 pointer-events-none">
                <div class="absolute -top-24 -left-20 w-96 h-96 bg-violet-400 dark:bg-violet-600 rounded-full blur-[100px]"></div>
                <div class="absolute top-1/2 -right-20 w-80 h-80 bg-indigo-400 dark:bg-indigo-600 rounded-full blur-[100px]"></div>
            </div>
            <div class="relative px-8 py-10 flex flex-col md:flex-row md:items-center justify-between gap-8 z-10">
                <div class="flex-1">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-50 dark:bg-violet-900/30 border border-violet-100 dark:border-violet-800 mb-4">
                        <x-icon name="gavel" style="duotone" class="w-3 h-3 text-violet-600 dark:text-violet-400" />
                        <span
                            class="text-[10px] font-black uppercase tracking-widest text-violet-600 dark:text-violet-400">Diretoria</span>
                    </div>
                    <p class="text-gray-500 dark:text-slate-300 font-medium max-w-xl text-lg leading-relaxed">
                        {{ $assemblyCount }} assembleia(s) · {{ $communicationCount }} comunicado(s) registados.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('memberpanel.governance.assemblies.index') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-900 dark:text-white border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold transition-all">
                        <x-icon name="users" style="duotone" class="w-4 h-4 mr-2" />
                        Assembleias
                    </a>
                    <a href="{{ route('memberpanel.governance.communications.index') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 text-violet-600 dark:text-violet-400 border border-violet-200 dark:border-violet-800 rounded-xl text-sm font-bold transition-all">
                        <x-icon name="newspaper" style="duotone" class="w-4 h-4 mr-2" />
                        Comunicados
                    </a>
                    @if ($canManage)
                        <a href="{{ route('memberpanel.governance.assemblies.create') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-violet-500/20">
                            <x-icon name="plus" style="duotone" class="w-4 h-4 mr-2" />
                            Nova assembleia
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('memberpanel.governance.assemblies.index') }}"
                class="group bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm hover:border-violet-200 dark:hover:border-violet-800 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Assembleias</p>
                        <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $assemblyCount }}</p>
                    </div>
                    <x-icon name="calendar-check" style="duotone"
                        class="w-10 h-10 text-violet-500 opacity-80 group-hover:scale-105 transition" />
                </div>
            </a>
            <a href="{{ route('memberpanel.governance.communications.index') }}"
                class="group bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm hover:border-indigo-200 dark:hover:border-indigo-800 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Comunicados</p>
                        <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $communicationCount }}</p>
                    </div>
                    <x-icon name="file-lines" style="duotone"
                        class="w-10 h-10 text-indigo-500 opacity-80 group-hover:scale-105 transition" />
                </div>
            </a>
        </div>
    @endcomponent
@endsection
