@extends('layouts.app')

@section('title', 'Avisos JUBAF')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 md:p-8 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
            <div class="flex items-start gap-3">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-600 text-white shadow-md">
                    <x-module-icon module="Avisos" class="h-6 w-6 text-white" />
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Avisos JUBAF</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Comunicados oficiais e atualizações para supervisão pastoral.</p>
                </div>
            </div>
            <form method="get" action="{{ route('pastor.avisos.index') }}" class="flex w-full sm:w-auto gap-2">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar…"
                       class="min-w-0 flex-1 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-2 text-sm focus:ring-2 focus:ring-sky-500" />
                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">Buscar</button>
            </form>
        </div>

        <ul class="divide-y divide-slate-200 dark:divide-slate-800 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
            @forelse($avisos as $aviso)
                <li>
                    <a href="{{ route('pastor.avisos.show', $aviso) }}" class="block p-4 md:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-xs font-semibold text-sky-700 dark:text-sky-300">{{ $aviso->tipo_texto }}</span>
                            <span class="text-xs text-slate-500">{{ $aviso->created_at->diffForHumans() }}</span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $aviso->titulo }}</h2>
                        @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
                        @if($aviso->descricao)
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 line-clamp-2">{{ strip_tags($aviso->descricao) }}</p>
                        @endif
                    </a>
                </li>
            @empty
                <li class="px-6 py-12 text-center text-slate-500 text-sm">Sem avisos no momento.</li>
            @endforelse
        </ul>

        @if($avisos->hasPages())
            <div class="mt-6">
                {{ $avisos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
