@extends('painellider::layouts.lideres')

@section('title', 'Avisos JUBAF')

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="text-emerald-600 dark:text-emerald-400">Avisos</span>
@endsection

@section('lideres_content')
<x-ui.lideres::page-shell class="animate-fade-in space-y-6 pb-8 md:space-y-8">
    <x-ui.lideres::hero
        variant="gradient"
        eyebrow="Comunicação JUBAF"
        title="Avisos e comunicados"
        description="Comunicados oficiais da JUBAF para a tua liderança e congregação.">
        <x-slot name="actions">
            <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center self-start rounded-xl bg-white/15 shadow-lg sm:self-center">
                    <x-module-icon module="Avisos" class="h-6 w-6 text-white" />
                </span>
                <form method="get" action="{{ route('lideres.avisos.index') }}" class="flex min-w-0 flex-1 gap-2 sm:max-w-md">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar…"
                           class="min-w-0 flex-1 rounded-xl border border-white/25 bg-white/10 px-4 py-2 text-sm text-white placeholder-emerald-100/80 backdrop-blur focus:ring-2 focus:ring-white/40" />
                    <button type="submit" class="shrink-0 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-emerald-900 shadow-sm hover:bg-emerald-50">Buscar</button>
                </form>
            </div>
        </x-slot>
    </x-ui.lideres::hero>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <ul class="divide-y divide-slate-200 dark:divide-slate-800">
            @forelse($avisos as $aviso)
                <li>
                    <a href="{{ route('lideres.avisos.show', $aviso) }}" class="flex flex-col gap-3 p-5 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50 md:p-6">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">{{ $aviso->tipo_texto }}</span>
                            <span class="text-xs text-slate-500">{{ $aviso->created_at->diffForHumans() }}</span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $aviso->titulo }}</h2>
                        @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
                        @if($aviso->descricao)
                            <p class="line-clamp-2 text-sm text-slate-600 dark:text-slate-400">{{ strip_tags($aviso->descricao) }}</p>
                        @endif
                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-600 dark:text-emerald-400">Abrir <x-icon name="arrow-right" class="h-4 w-4" /></span>
                    </a>
                </li>
            @empty
                <li class="px-6 py-16 text-center">
                    <x-icon name="bell-slash" class="mx-auto mb-3 h-12 w-12 text-slate-400" />
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Sem avisos por agora</p>
                    <p class="mt-1 text-sm text-slate-500">Quando houver novidades, aparecem aqui.</p>
                </li>
            @endforelse
        </ul>
        @if($avisos->hasPages())
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-900/50">
                {{ $avisos->links() }}
            </div>
        @endif
    </div>
</x-ui.lideres::page-shell>
@endsection
