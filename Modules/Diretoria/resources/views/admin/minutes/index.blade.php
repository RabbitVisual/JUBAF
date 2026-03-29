@extends('admin::components.layouts.master')

@section('title', 'Diretoria — Atas (PDF)')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Repositório de atas</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Documentos oficiais em PDF, com etiquetas para reuniões e assembleias.</p>
            </div>
            @if(auth()->user()->canAccess('governance_manage'))
                <a href="{{ route('admin.diretoria.minutes.create') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
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
            <a href="{{ route('admin.diretoria.minutes.index') }}"
                class="rounded-full px-3 py-1.5 text-xs font-semibold {{ !$tagFilter ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-slate-800 dark:text-gray-300' }}">
                Todas
            </a>
            @foreach($tagLabels as $slug => $label)
                <a href="{{ route('admin.diretoria.minutes.index', ['tag' => $slug]) }}"
                    class="rounded-full px-3 py-1.5 text-xs font-semibold {{ $tagFilter === $slug ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-slate-800 dark:text-gray-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Mobile-first: cards --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($minutes as $m)
                <article class="flex flex-col rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-3 flex items-start justify-between gap-2">
                        <span class="inline-flex rounded-lg bg-indigo-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">
                            {{ $m->tag_label }}
                        </span>
                        <time class="text-xs font-medium text-gray-500 dark:text-gray-400" datetime="{{ $m->meeting_date->format('Y-m-d') }}">
                            {{ $m->meeting_date->format('d/m/Y') }}
                        </time>
                    </div>
                    <h2 class="text-base font-bold leading-snug text-gray-900 dark:text-white">{{ $m->title }}</h2>
                    @if($m->notes)
                        <p class="mt-2 line-clamp-2 text-xs text-gray-600 dark:text-gray-400">{{ $m->notes }}</p>
                    @endif
                    <div class="mt-4 flex flex-wrap gap-2 border-t border-gray-100 pt-4 dark:border-slate-800">
                        <a href="{{ route('admin.diretoria.minutes.download', $m) }}"
                            class="inline-flex flex-1 min-w-[7rem] items-center justify-center gap-1 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-100 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                            <x-icon name="download" class="h-3.5 w-3.5" />
                            PDF
                        </a>
                        @if(auth()->user()->canAccess('governance_manage'))
                            <a href="{{ route('admin.diretoria.minutes.edit', $m) }}"
                                class="inline-flex flex-1 min-w-[7rem] items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                                Editar
                            </a>
                            <form action="{{ route('admin.diretoria.minutes.destroy', $m) }}" method="post" class="contents"
                                onsubmit="return confirm('Remover esta ata e o ficheiro PDF?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex w-full min-w-[7rem] items-center justify-center rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-50 dark:border-red-900/50 dark:text-red-300 dark:hover:bg-red-900/20 sm:w-auto">
                                    Excluir
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50/50 px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-900/30">
                    <x-icon name="folder-open" class="mx-auto mb-3 h-10 w-10 text-gray-400" />
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nenhuma ata neste filtro.</p>
                    @if(auth()->user()->canAccess('governance_manage'))
                        <a href="{{ route('admin.diretoria.minutes.create') }}" class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">Carregar o primeiro PDF</a>
                    @endif
                </div>
            @endforelse
        </div>

        <div class="overflow-x-auto pb-2">
            {{ $minutes->links() }}
        </div>
    </div>
@endsection
