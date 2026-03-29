@extends('memberpanel::components.layouts.master')

@section('page-title', 'Comunicados')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Comunicados',
        'subtitle' => 'Documentos oficiais da diretoria.',
        'badge' => 'Governança',
    ])
        @slot('actions')
            @if (auth()->user()->canAccess('governance_manage'))
                <a href="{{ route('memberpanel.governance.communications.create') }}"
                    class="inline-flex items-center px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-lg shadow-violet-500/20">
                    <x-icon name="plus" style="duotone" class="w-4 h-4 mr-2" />
                    Novo comunicado
                </a>
            @endif
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.governance.communications.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">
                    Admin técnico
                </a>
            @endif
        @endslot

        <a href="{{ route('memberpanel.governance.dashboard') }}"
            class="inline-flex items-center text-sm font-semibold text-violet-600 dark:text-violet-400 hover:underline mb-6">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Visão geral
        </a>

        <div class="space-y-4">
            @forelse($items as $row)
                <a href="{{ route('memberpanel.governance.communications.show', $row) }}"
                    class="block bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 hover:border-violet-200 dark:hover:border-violet-800 transition shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div>
                            <h2 class="font-bold text-gray-900 dark:text-white text-lg">{{ $row->title }}</h2>
                            @if ($row->summary)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">{{ $row->summary }}</p>
                            @endif
                        </div>
                        <div class="text-xs font-bold shrink-0">
                            @if ($row->is_published && $row->published_at)
                                <span class="text-emerald-600 dark:text-emerald-400">Publicado</span>
                                <span class="block text-gray-500 font-normal mt-1">{{ $row->published_at->format('d/m/Y') }}</span>
                            @else
                                <span class="text-amber-600 dark:text-amber-400">Rascunho</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-center text-gray-500 text-sm py-16 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800">Nenhum
                    comunicado.</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $items->links() }}</div>
    @endcomponent
@endsection
