@extends('memberpanel::components.layouts.master')

@section('page-title', 'Assembleias')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Assembleias',
        'subtitle' => 'Convocações, pautas e estado das atas.',
        'badge' => 'Governança',
    ])
        @slot('actions')
            @if (auth()->user()->canAccess('governance_manage'))
                <a href="{{ route('memberpanel.governance.assemblies.create') }}"
                    class="inline-flex items-center px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-lg shadow-violet-500/20">
                    <x-icon name="plus" style="duotone" class="w-4 h-4 mr-2" />
                    Nova assembleia
                </a>
            @endif
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.governance.assemblies.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">
                    Admin técnico
                </a>
            @endif
        @endslot

        <div
            class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-800 mb-6">
            <div class="absolute inset-0 opacity-15 pointer-events-none">
                <div class="absolute -top-20 -right-10 w-72 h-72 bg-violet-400 rounded-full blur-[90px]"></div>
            </div>
            <div class="relative px-6 py-6 md:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <p class="text-gray-600 dark:text-slate-400 text-sm max-w-xl">Lista cronológica das assembleias. Quem gere a governança edita convocação, pauta e ata aqui no painel.</p>
                <a href="{{ route('memberpanel.governance.dashboard') }}"
                    class="text-sm font-bold text-violet-600 dark:text-violet-400 hover:underline shrink-0">← Visão geral</a>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <ul class="divide-y divide-gray-100 dark:divide-slate-800">
                @forelse($assemblies as $a)
                    <li>
                        <a href="{{ route('memberpanel.governance.assemblies.show', $a) }}"
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-5 py-4 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $a->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $a->scheduled_at?->translatedFormat('d M Y, H:i') }}
                                    · {{ $a->type === 'extraordinaria' ? 'Extraordinária' : 'Ordinária' }}</p>
                            </div>
                            @if ($a->minute)
                                <span
                                    class="text-xs font-bold px-3 py-1 rounded-full shrink-0
                                    @class([
                                        'bg-gray-100 text-gray-700 dark:bg-slate-800 dark:text-gray-300' => $a->minute->status === 'draft',
                                        'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200' => $a->minute->status === 'approved',
                                        'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' => $a->minute->status === 'published',
                                    ])">{{ $a->minute->status }}</span>
                            @endif
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-16 text-center text-gray-500 text-sm">Nenhuma assembleia registada.</li>
                @endforelse
            </ul>
        </div>
        <div class="mt-6">{{ $assemblies->links() }}</div>
    @endcomponent
@endsection
