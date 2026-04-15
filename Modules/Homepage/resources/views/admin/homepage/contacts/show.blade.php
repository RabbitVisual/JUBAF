@extends(request()->routeIs('diretoria.*') ? 'paineldiretoria::components.layouts.app' : 'admin::layouts.admin')

@php
    $isDiretoria = request()->routeIs('diretoria.*');
@endphp

@section('title', 'Mensagem de contato')

@section('content')
<div class="{{ $isDiretoria ? 'mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in' : 'container-fluid px-4' }}">
    @if ($isDiretoria)
        @include('paineldiretoria::partials.homepage-contacts-subnav', ['active' => 'show'])

        <header class="overflow-hidden rounded-3xl border border-blue-100/90 bg-gradient-to-br from-blue-50/90 via-white to-white p-6 shadow-sm dark:border-blue-900/25 dark:from-blue-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">Mensagem #{{ $message->id }}</p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">{{ $message->name }}</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-slate-400">{{ $message->email }}</p>
                    <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                        <a href="{{ route('diretoria.dashboard') }}" class="transition hover:text-blue-600 dark:hover:text-blue-400">Diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <a href="{{ route('diretoria.homepage.contacts.index') }}" class="transition hover:text-blue-600 dark:hover:text-blue-400">Mensagens</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <span class="font-medium text-gray-800 dark:text-slate-300">Detalhe</span>
                    </nav>
                </div>
                <div class="shrink-0">
                    <a href="{{ homepage_panel_route('contacts.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                        <x-icon name="arrow-left" class="h-4 w-4" style="solid" />
                        Voltar à lista
                    </a>
                </div>
            </div>
        </header>
    @else
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalhes da mensagem</h1>
            <a href="{{ homepage_panel_route('contacts.index') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700">
                Voltar
            </a>
        </div>
    @endif

    <div class="space-y-6">
        <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6">
            <div class="grid gap-6 border-b border-gray-100 pb-6 dark:border-slate-700 md:grid-cols-2">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Remetente</h2>
                    <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">{{ $message->name }}</p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">
                        <a href="mailto:{{ $message->email }}" class="text-blue-600 hover:underline dark:text-blue-400">{{ $message->email }}</a>
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-slate-500">
                        <span class="font-medium text-gray-700 dark:text-slate-300">Telefone:</span>
                        {{ $message->phone ?: 'Não informado' }}
                    </p>
                </div>
                <div class="md:text-right">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Recebida em</h2>
                    <p class="mt-2 text-lg font-medium text-gray-900 dark:text-white">{{ $message->created_at->format('d/m/Y \à\s H:i') }}</p>
                    <h2 class="mt-4 text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Estado</h2>
                    <div class="mt-2">
                        @if ($message->read_at)
                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-800 dark:bg-slate-700 dark:text-slate-200">Lida em {{ $message->read_at->format('d/m/Y H:i') }}</span>
                        @else
                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-950/50 dark:text-blue-300">Nova</span>
                        @endif
                    </div>
                </div>
            </div>

            @if ($message->subject)
                <div class="pt-6">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Assunto</h2>
                    <p class="mt-2 text-lg font-medium text-gray-900 dark:text-white">{{ $message->subject }}</p>
                </div>
            @endif
        </section>

        <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Mensagem</h2>
            <div class="mt-4 rounded-xl border border-gray-100 bg-gray-50/90 p-5 text-base leading-relaxed text-gray-800 dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-200 sm:p-6">
                <div class="whitespace-pre-wrap">{{ $message->message }}</div>
            </div>
        </section>

        <div class="flex flex-col-reverse gap-3 rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:px-6 sm:py-5">
            <a
                href="{{ homepage_panel_route('contacts.index') }}"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-900 dark:text-gray-200 dark:hover:bg-slate-700 sm:w-auto sm:border-0 sm:bg-transparent sm:shadow-none dark:sm:bg-transparent"
            >
                <x-icon name="xmark" class="h-5 w-5 shrink-0" style="solid" />
                Fechar
            </a>
            <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center sm:justify-end sm:gap-3">
                <a
                    href="mailto:{{ $message->email }}{{ $message->subject ? '?subject='.rawurlencode('Re: '.$message->subject) : '' }}"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300/50 sm:w-auto dark:focus:ring-blue-900/50"
                >
                    <x-icon name="envelope" class="h-5 w-5 shrink-0" style="duotone" />
                    Responder por e-mail
                </a>
                <form action="{{ homepage_panel_route('contacts.destroy', ['id' => $message->id]) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Tem certeza que deseja excluir esta mensagem?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-bold text-red-700 transition hover:bg-red-100 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-300 dark:hover:bg-red-950/60 sm:w-auto">
                        <x-icon name="trash" class="h-5 w-5 shrink-0" style="duotone" />
                        Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
