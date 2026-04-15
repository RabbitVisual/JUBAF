@extends('layouts.app')

@section('title', 'Avisos JUBAF')

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="text-violet-600 dark:text-violet-400">Avisos</span>
@endsection

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-3 mb-2">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-lg">
                    <x-module-icon module="Avisos" class="h-6 w-6 text-white" />
                </span>
                Avisos e comunicados
            </h1>
            <p class="text-sm md:text-base text-slate-600 dark:text-slate-400">
                Comunicados oficiais da JUBAF e da direção para ti e para a tua igreja.
            </p>
        </div>
        <form method="get" action="{{ route('jovens.avisos.index') }}" class="flex w-full sm:w-auto gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar…"
                   class="flex-1 min-w-0 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-violet-500" />
            <button type="submit" class="shrink-0 rounded-xl bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">Buscar</button>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-sm">
        <ul class="divide-y divide-slate-200 dark:divide-slate-800">
            @forelse($avisos as $aviso)
                <li>
                    <a href="{{ route('jovens.avisos.show', $aviso) }}" class="flex flex-col gap-3 p-5 md:p-6 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">{{ $aviso->tipo_texto }}</span>
                            <span class="text-xs text-slate-500">{{ $aviso->created_at->diffForHumans() }}</span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $aviso->titulo }}</h2>
                        @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
                        @if($aviso->descricao)
                            <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2">{{ strip_tags($aviso->descricao) }}</p>
                        @endif
                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-violet-600 dark:text-violet-400">Abrir <x-icon name="arrow-right" class="w-4 h-4" /></span>
                    </a>
                </li>
            @empty
                <li class="px-6 py-16 text-center">
                    <x-icon name="bell-slash" class="mx-auto h-12 w-12 text-slate-400 mb-3" />
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Sem avisos por agora</p>
                    <p class="mt-1 text-sm text-slate-500">Quando houver novidades, aparecem aqui.</p>
                </li>
            @endforelse
        </ul>
        @if($avisos->hasPages())
            <div class="border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 px-6 py-4">
                {{ $avisos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
