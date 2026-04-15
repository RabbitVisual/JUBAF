@extends(request()->routeIs('diretoria.*') ? 'paineldiretoria::components.layouts.app' : 'admin::layouts.admin')

@php
    $contactStats = $contactStats ?? ['total' => 0, 'unread' => 0];
    $isDiretoria = request()->routeIs('diretoria.*');
@endphp

@section('title', 'Mensagens de contato')

@section('content')
<div class="{{ $isDiretoria ? 'mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in' : 'container-fluid px-4' }}">
    @if ($isDiretoria)
        @include('paineldiretoria::partials.homepage-contacts-subnav', ['active' => 'lista'])

        <header class="overflow-hidden rounded-3xl border border-blue-100/90 bg-gradient-to-br from-blue-50/90 via-white to-white p-6 shadow-sm dark:border-blue-900/25 dark:from-blue-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">Homepage · Formulário público</p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Mensagens de contato</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                        Pedidos enviados pelo bloco de contato do site. Mensagens <strong class="font-semibold text-gray-800 dark:text-slate-200">novas</strong> ainda não foram abertas neste painel; ao abrir o detalhe, marcam-se como lidas automaticamente.
                    </p>
                    <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                        <a href="{{ route('diretoria.dashboard') }}" class="transition hover:text-blue-600 dark:hover:text-blue-400">Diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <a href="{{ route('diretoria.homepage.index') }}" class="transition hover:text-blue-600 dark:hover:text-blue-400">Homepage</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <span class="font-medium text-gray-800 dark:text-slate-300">Contato</span>
                    </nav>
                </div>
            </div>
        </header>

        <div class="flex gap-4 rounded-2xl border border-sky-200/80 bg-sky-50/90 p-4 dark:border-sky-900/40 dark:bg-sky-950/30 md:items-center md:p-5">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-200/80 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200">
                <x-icon name="circle-info" class="h-5 w-5" style="duotone" />
            </span>
            <p class="min-w-0 text-sm leading-relaxed text-sky-950/90 dark:text-sky-100/90">
                <span class="font-semibold text-sky-900 dark:text-sky-100">Resposta rápida</span>
                — use <strong class="font-semibold">Ver</strong> para ler o texto completo e o botão de e-mail na página de detalhe para responder ao visitante.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10 blur-2xl"></div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Total recebidas</p>
                <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $contactStats['total'] }}</p>
                <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Histórico completo</p>
            </div>
            <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-500/10 blur-2xl"></div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Por abrir</p>
                <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $contactStats['unread'] }}</p>
                <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Ainda não vistas</p>
            </div>
            <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:col-span-2 lg:col-span-1">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10 blur-2xl"></div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Lista</p>
                <p class="mt-2 text-sm font-medium text-gray-700 dark:text-slate-300">
                    @if ($messages->currentPage() > 1)
                        Página {{ $messages->currentPage() }} de {{ $messages->lastPage() }}
                    @else
                        Primeira página
                    @endif
                </p>
                <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">{{ $messages->perPage() }} por página</p>
            </div>
        </div>
    @else
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mensagens de contato</h1>
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-200" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
        <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Caixa de entrada</h2>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Ordenado da mais recente para a mais antiga</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50/90 dark:bg-slate-900/60">
                    <tr>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">Estado</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">Remetente</th>
                        <th scope="col" class="hidden px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 md:table-cell sm:px-6">Assunto</th>
                        <th scope="col" class="hidden px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 lg:table-cell sm:px-6">Prévia</th>
                        <th scope="col" class="hidden px-5 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:table-cell sm:px-6">Data</th>
                        <th scope="col" class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400 sm:px-6">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:divide-slate-700 dark:bg-slate-800/40">
                    @forelse ($messages as $message)
                        <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-slate-700/30 {{ $message->read_at ? '' : 'bg-blue-50/70 dark:bg-blue-950/20' }}">
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                @if ($message->read_at)
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-300">Lida</span>
                                @else
                                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800 dark:bg-blue-950/50 dark:text-blue-300">Nova</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $message->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-slate-400">{{ $message->email }}</div>
                                <div class="mt-1 text-xs text-gray-400 dark:text-slate-500 sm:hidden">{{ $message->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="hidden whitespace-nowrap px-5 py-4 text-sm text-gray-700 dark:text-slate-300 md:table-cell sm:px-6">
                                {{ $message->subject ?: '—' }}
                            </td>
                            <td class="hidden max-w-xs px-5 py-4 text-sm text-gray-600 dark:text-slate-400 lg:table-cell sm:px-6">
                                <span class="line-clamp-2">{{ Str::limit($message->message, 120) }}</span>
                            </td>
                            <td class="hidden whitespace-nowrap px-5 py-4 text-sm text-gray-500 dark:text-slate-400 sm:table-cell sm:px-6">
                                {{ $message->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right sm:px-6">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ homepage_panel_route('contacts.show', ['id' => $message->id]) }}" class="rounded-lg p-2 text-slate-500 transition hover:bg-blue-50 hover:text-blue-600 dark:text-slate-400 dark:hover:bg-blue-950/40 dark:hover:text-blue-400" title="Ver mensagem">
                                        <x-icon name="eye" class="h-5 w-5" style="duotone" />
                                        <span class="sr-only">Ver</span>
                                    </a>
                                    <form action="{{ homepage_panel_route('contacts.destroy', ['id' => $message->id]) }}" method="POST" class="inline-flex" onsubmit="return confirm('Tem certeza que deseja excluir esta mensagem?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-600 dark:text-slate-400 dark:hover:bg-red-950/40 dark:hover:text-red-400" title="Excluir">
                                            <x-icon name="trash" class="h-5 w-5" style="duotone" />
                                            <span class="sr-only">Excluir</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto flex max-w-md flex-col items-center">
                                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/40">
                                        <x-icon name="inbox" class="h-8 w-8 text-blue-400" style="duotone" />
                                    </span>
                                    <p class="mt-4 text-base font-semibold text-gray-900 dark:text-white">Nenhuma mensagem ainda</p>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">Quando alguém enviar o formulário de contato, o registo aparece aqui.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($messages->hasPages())
            <div class="border-t border-gray-100 px-5 py-4 dark:border-slate-700 sm:px-6">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
