@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', 'Bíblia — indisponível')

@section('jovens_content')
    <div class="flex min-h-[60vh] items-center justify-center py-8">
        <div class="w-full max-w-md">
            <div class="overflow-hidden rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xl">
                <div class="bg-gradient-to-br from-gray-800 to-gray-950 px-8 py-10 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-25 bg-[radial-gradient(circle_at_30%_20%,rgba(45,212,191,0.4),transparent_50%)]"></div>
                    <div class="relative">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 border border-white/10">
                            <x-icon name="book-bible" class="w-8 h-8 text-blue-300" />
                        </div>
                        <h1 class="text-xl font-bold text-white tracking-tight">Bíblia digital</h1>
                        <p class="mt-1 text-[11px] font-semibold uppercase tracking-widest text-gray-400">Conteúdo por configurar</p>
                    </div>
                </div>
                <div class="px-8 py-10 text-center">
                    <div class="mx-auto mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400">
                        <x-icon name="triangle-exclamation" class="w-6 h-6" />
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Ainda não há traduções importadas</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Quando a equipa carregar versões (NVI, ACF, etc.), o leitor ficará disponível aqui no teu painel — sem precisares sair do Unijovem.
                    </p>
                    <div class="mt-8 flex flex-col gap-3">
                        <a href="{{ route('jovens.dashboard') }}"
                           class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white px-4 py-3.5 text-sm font-semibold shadow-md shadow-blue-600/20 transition-colors">
                            <x-icon name="grid-2-plus" class="w-4 h-4" />
                            Voltar ao painel de jovens
                        </a>
                        @if(Route::has('jovens.bible.plans.index'))
                            <a href="{{ route('jovens.bible.plans.index') }}"
                               class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <x-icon name="calendar-days" class="w-4 h-4 text-blue-600" />
                                Ver planos de leitura
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
