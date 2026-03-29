@extends('memberpanel::components.layouts.master')

@section('title', 'Atas da Diretoria')
@section('page-title', 'Atas PDF — Diretoria')

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <nav class="mb-2 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-slate-400" aria-label="Breadcrumb">
                    <a href="{{ route('memberpanel.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Painel</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0" />
                    <a href="{{ route('memberpanel.governance.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Governança</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0" />
                    <span class="font-medium text-gray-900 dark:text-white">Atas</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Repositório de atas</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">PDFs oficiais (reuniões, assembleias, conselho). Otimizado para telemóvel.</p>
            </div>
            @if(auth()->user()->canAccess('governance_manage'))
                <a href="{{ route('memberpanel.governance.diretoria.minutes.create') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    <x-icon name="upload" class="h-4 w-4" />
                    Nova ata
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('memberpanel.governance.diretoria.minutes.index') }}"
                class="rounded-full px-3 py-1.5 text-xs font-semibold {{ !$tagFilter ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-slate-800 dark:text-gray-300' }}">
                Todas
            </a>
            @foreach($tagLabels as $slug => $label)
                <a href="{{ route('memberpanel.governance.diretoria.minutes.index', ['tag' => $slug]) }}"
                    class="rounded-full px-3 py-1.5 text-xs font-semibold {{ $tagFilter === $slug ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-slate-800 dark:text-gray-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Mobile: cards --}}
        <div class="grid gap-4 md:hidden">
            @forelse($minutes as $m)
                <article class="flex flex-col rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-2 flex items-start justify-between gap-2">
                        <span class="inline-flex rounded-lg bg-indigo-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">
                            {{ $m->tag_label }}
                        </span>
                        <time class="text-xs font-medium text-gray-500 dark:text-gray-400" datetime="{{ $m->meeting_date->format('Y-m-d') }}">
                            {{ $m->meeting_date->format('d/m/Y') }}
                        </time>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">{{ $m->title }}</h2>
                    <div class="mt-4 flex flex-wrap gap-2 border-t border-gray-100 pt-4 dark:border-slate-800">
                        <a href="{{ route('memberpanel.governance.diretoria.minutes.download', $m) }}"
                            class="inline-flex flex-1 min-w-[6rem] items-center justify-center gap-1 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-800 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-200">
                            <x-icon name="download" class="h-3.5 w-3.5" />
                            PDF
                        </a>
                        @if(auth()->user()->canAccess('governance_manage'))
                            <a href="{{ route('memberpanel.governance.diretoria.minutes.edit', $m) }}"
                                class="inline-flex flex-1 min-w-[6rem] items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white">
                                Editar
                            </a>
                            <form action="{{ route('memberpanel.governance.diretoria.minutes.destroy', $m) }}" method="post" class="contents"
                                onsubmit="return confirm('Remover esta ata e o ficheiro PDF?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 dark:border-red-900/50 dark:text-red-300">
                                    Excluir
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50/50 px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-900/30">
                    <p class="text-sm text-gray-600 dark:text-slate-400">Nenhuma ata neste filtro.</p>
                </div>
            @endforelse
        </div>

        {{-- Desktop: tabela --}}
        <div class="hidden overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-slate-800">
                    <thead class="bg-gray-50 dark:bg-slate-800/80">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">Data</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">Tipo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">Título</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($minutes as $m)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                                <td class="whitespace-nowrap px-4 py-3 text-gray-700 dark:text-slate-300">{{ $m->meeting_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3"><span class="rounded-md bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">{{ $m->tag_label }}</span></td>
                                <td class="max-w-xs truncate px-4 py-3 font-medium text-gray-900 dark:text-white" title="{{ $m->title }}">{{ $m->title }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">
                                    <a href="{{ route('memberpanel.governance.diretoria.minutes.download', $m) }}" class="mr-2 font-semibold text-indigo-600 hover:underline dark:text-indigo-400">PDF</a>
                                    @if(auth()->user()->canAccess('governance_manage'))
                                        <a href="{{ route('memberpanel.governance.diretoria.minutes.edit', $m) }}" class="mr-2 font-semibold text-gray-700 hover:underline dark:text-slate-300">Editar</a>
                                        <form action="{{ route('memberpanel.governance.diretoria.minutes.destroy', $m) }}" method="post" class="inline" onsubmit="return confirm('Remover esta ata?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-semibold text-red-600 hover:underline dark:text-red-400">Excluir</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-gray-500 dark:text-slate-400">Nenhuma ata.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="overflow-x-auto pb-2">
            {{ $minutes->links() }}
        </div>
    </div>
@endsection
