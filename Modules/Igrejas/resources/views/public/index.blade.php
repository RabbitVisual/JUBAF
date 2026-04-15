@extends('homepage::layouts.homepage')

@section('title')
Congregações — {{ \App\Support\SiteBranding::siteName() }}
@endsection

@section('content')
@include('homepage::layouts.navbar-homepage')

@php
    $siteName = \App\Support\SiteBranding::siteName();
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-cyan-50/40 dark:from-slate-900 dark:via-slate-900 dark:to-cyan-950/25 py-12 md:py-16">
    <div class="container mx-auto max-w-4xl px-4">
        <div class="mb-10 text-center">
            <div class="inline-flex items-center gap-2 rounded-full bg-cyan-100 px-4 py-2 text-sm font-semibold text-cyan-900 dark:bg-cyan-900/40 dark:text-cyan-100">
                <x-module-icon module="Igrejas" class="h-5 w-5" />
                Rede JUBAF
            </div>
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white md:text-4xl">Congregações na JUBAF</h1>
            <p class="mx-auto mt-3 max-w-2xl text-base text-gray-600 dark:text-gray-300">
                Igrejas locais com trabalho de juventude arroladas à ASBAF. Contactos institucionais para atividades, eventos e apoio pastoral — dados públicos limitados ao necessário.
            </p>
        </div>

        <ul class="space-y-4">
            @forelse($churches as $c)
                <li class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm transition hover:border-cyan-200 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-cyan-800">
                    <div class="p-5 md:p-6">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $c->name }}</h2>
                                @if($c->city)
                                    <p class="mt-2 inline-flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                                        <x-icon name="location-dot" class="h-4 w-4 text-cyan-600 dark:text-cyan-400" style="duotone" />
                                        {{ $c->city }}
                                    </p>
                                @endif
                                @if($c->phone || $c->email)
                                    <p class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-600 dark:text-gray-400">
                                        @if($c->phone)
                                            <a href="tel:{{ preg_replace('/\s+/', '', $c->phone) }}" class="inline-flex items-center gap-1 font-semibold text-cyan-700 hover:underline dark:text-cyan-400">
                                                <x-icon name="phone" class="h-4 w-4" style="duotone" />
                                                {{ $c->phone }}
                                            </a>
                                        @endif
                                        @if($c->email)
                                            <a href="mailto:{{ $c->email }}" class="inline-flex items-center gap-1 font-semibold text-cyan-700 hover:underline break-all dark:text-cyan-400">
                                                <x-icon name="envelope" class="h-4 w-4" style="duotone" />
                                                {{ $c->email }}
                                            </a>
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="rounded-2xl border border-dashed border-gray-300 bg-white/60 px-6 py-16 text-center dark:border-slate-600 dark:bg-slate-800/40">
                    <x-icon name="church" class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" style="duotone" />
                    <p class="mt-4 font-semibold text-gray-900 dark:text-white">Sem congregações públicas</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Volte mais tarde ou contacte a diretoria {{ $siteName }}.</p>
                </li>
            @endforelse
        </ul>

        @if($churches->hasPages())
            <div class="mt-10">{{ $churches->links() }}</div>
        @endif

        <p class="mt-12 text-center text-xs text-gray-500 dark:text-gray-500">
            Cadastro completo e gestão de vínculos: painéis autorizados da JUBAF.
        </p>
    </div>
</div>

@include('homepage::layouts.footer-homepage')
@endsection
