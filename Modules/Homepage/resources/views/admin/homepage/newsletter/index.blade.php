@extends('layouts.app')

@php
    $newsletterStats = $newsletterStats ?? ['total' => 0, 'active' => 0];
    $isDiretoria = request()->routeIs('diretoria.*');
@endphp

@section('title', 'Newsletter da homepage')

@section('content')
<div class="{{ $isDiretoria ? 'mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in' : 'container-fluid px-4' }}">
    @if ($isDiretoria)
        @include('paineldiretoria::partials.homepage-newsletter-subnav', ['active' => 'lista'])

        <header class="overflow-hidden rounded-3xl border border-indigo-100/90 bg-gradient-to-br from-indigo-50/90 via-white to-white p-6 shadow-sm dark:border-indigo-900/25 dark:from-indigo-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Homepage · Audiências</p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Assinantes da newsletter</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                        Lista de e-mails inscritos no bloco público da newsletter. Apenas assinantes <strong class="font-semibold text-gray-800 dark:text-slate-200">ativos e confirmados</strong> recebem campanhas enviadas pelo painel.
                    </p>
                    <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                        <a href="{{ route('diretoria.dashboard') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <a href="{{ route('diretoria.homepage.index') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Homepage</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <span class="font-medium text-gray-800 dark:text-slate-300">Newsletter</span>
                    </nav>
                </div>
                <div class="shrink-0">
                    <a href="{{ homepage_panel_route('newsletter.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300/50 dark:focus:ring-indigo-900/50">
                        <x-icon name="paper-plane" class="h-5 w-5" style="duotone" />
                        Nova campanha
                    </a>
                </div>
            </div>
        </header>

        <div class="flex gap-4 rounded-2xl border border-sky-200/80 bg-sky-50/90 p-4 dark:border-sky-900/40 dark:bg-sky-950/30 md:items-center md:p-5">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-200/80 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200">
                <x-icon name="circle-info" class="h-5 w-5" style="duotone" />
            </span>
            <p class="min-w-0 text-sm leading-relaxed text-sky-950/90 dark:text-sky-100/90">
                <span class="font-semibold text-sky-900 dark:text-sky-100">Antes de enviar</span>
                — confira se há assinantes ativos na métrica abaixo. O envio em massa usa a fila de e-mail do servidor; falhas pontuais aparecem na mensagem após o envio.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-indigo-500/10 blur-2xl"></div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Total na base</p>
                <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $newsletterStats['total'] }}</p>
                <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Registos (inclui inativos)</p>
            </div>
            <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10 blur-2xl"></div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Recebem campanhas</p>
                <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $newsletterStats['active'] }}</p>
                <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Ativos e confirmados</p>
            </div>
            <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:col-span-2 lg:col-span-1">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-violet-500/10 blur-2xl"></div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Página atual</p>
                <p class="mt-2 text-sm font-medium text-gray-700 dark:text-slate-300">
                    @if ($subscribers->currentPage() > 1)
                        Lista · página {{ $subscribers->currentPage() }} de {{ $subscribers->lastPage() }}
                    @else
                        Primeira página da lista
                    @endif
                </p>
                <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">{{ $subscribers->perPage() }} por página</p>
            </div>
        </div>
    @else
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Assinantes da Newsletter</h1>
            <a href="{{ homepage_panel_route('newsletter.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700">
                <x-icon name="envelope" style="duotone" class="h-4 w-4" />
                Enviar e-mail para todos
            </a>
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-200" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
        <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Lista de assinantes</h2>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">E-mail, nome e estado da inscrição</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50/90 dark:bg-slate-900/60">
                    <tr>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">E-mail</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">Nome</th>
                        <th scope="col" class="hidden px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 md:table-cell sm:px-6">Inscrição</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">Estado</th>
                        <th scope="col" class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:divide-slate-700 dark:bg-slate-800/40">
                    @forelse ($subscribers as $subscriber)
                        <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-slate-700/30">
                            <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-900 dark:text-white sm:px-6">
                                {{ $subscriber->email }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-slate-400 sm:px-6">
                                {{ $subscriber->name ?? '—' }}
                            </td>
                            <td class="hidden whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-slate-400 md:table-cell sm:px-6">
                                {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                @if ($subscriber->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">Ativo</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-300">Inativo</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right sm:px-6">
                                <form action="{{ homepage_panel_route('newsletter.destroy', ['id' => $subscriber->id]) }}" method="POST" class="inline-flex" onsubmit="return confirm('Tem certeza que deseja remover este assinante?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-600 dark:text-slate-400 dark:hover:bg-red-950/40 dark:hover:text-red-400" title="Remover assinante">
                                        <x-icon name="trash" class="h-5 w-5" style="duotone" />
                                        <span class="sr-only">Excluir</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex max-w-md flex-col items-center">
                                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/40">
                                        <x-icon name="envelope" class="h-8 w-8 text-indigo-400" style="duotone" />
                                    </span>
                                    <p class="mt-4 text-base font-semibold text-gray-900 dark:text-white">Nenhum assinante ainda</p>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">Quando visitantes se inscreverem no site, aparecem aqui. Envie uma campanha quando tiver audiência.</p>
                                    <a href="{{ homepage_panel_route('newsletter.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-indigo-700">
                                        <x-icon name="paper-plane" class="h-4 w-4" style="duotone" />
                                        Criar campanha
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($subscribers->hasPages())
            <div class="border-t border-gray-100 px-5 py-4 dark:border-slate-700 sm:px-6">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
