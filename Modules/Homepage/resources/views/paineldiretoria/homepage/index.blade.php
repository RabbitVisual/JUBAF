@extends('paineldiretoria::components.layouts.app')

@section('title', 'Gerenciar Homepage')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
        @include('paineldiretoria::partials.section-nav', ['active' => 'homepage'])

        <header class="overflow-hidden rounded-3xl border border-blue-100/90 bg-gradient-to-br from-blue-50/90 via-white to-white p-6 shadow-sm dark:border-blue-900/25 dark:from-blue-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">Site público</p>
                    <h1 class="mt-2 flex items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 shadow-lg md:h-12 md:w-12">
                            <x-module-icon module="homepage" class="h-6 w-6 text-white md:h-7 md:w-7" style="duotone" />
                        </span>
                        Editor da homepage
                    </h1>
                    <p class="mt-3 max-w-3xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                        Ative blocos, edite textos e ligações ao site. Use as abas abaixo para percorrer cada área — as alterações em formulários são guardadas por separado com o botão <strong class="font-semibold text-gray-800 dark:text-slate-200">Salvar</strong> de cada separador.
                    </p>
                    <nav aria-label="breadcrumb" class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500">
                        <a href="{{ route('diretoria.dashboard') }}" class="transition hover:text-blue-600 dark:hover:text-blue-400">Diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <span class="font-medium text-gray-800 dark:text-slate-300">Homepage</span>
                    </nav>
                </div>
                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center sm:justify-end">
                    @if (auth()->user()?->hasRole('super-admin'))
                        <a href="{{ route('admin.config.index') }}#branding"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-800 transition hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-200 dark:hover:bg-blue-950/60">
                            <x-icon name="image" class="h-5 w-5" style="duotone" />
                            Logos e nome do site
                        </a>
                        <a href="{{ route('admin.config.index') }}#bible_homepage"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                            <x-icon name="gear" class="h-5 w-5" style="duotone" />
                            Config. avançadas
                        </a>
                    @endif
                    <a href="{{ route('homepage') }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300/50 dark:focus:ring-blue-900/50">
                        <x-icon name="arrow-up-right-from-square" class="h-5 w-5" style="duotone" />
                        Ver site público
                    </a>
                </div>
            </div>
        </header>

        <div class="flex gap-4 rounded-2xl border border-sky-200/80 bg-sky-50/90 p-4 dark:border-sky-900/40 dark:bg-sky-950/30 md:items-center md:p-5">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-200/80 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200">
                <x-icon name="layer-group" class="h-5 w-5" style="duotone" />
            </span>
            <p class="min-w-0 text-sm leading-relaxed text-sky-950/90 dark:text-sky-100/90">
                <span class="font-semibold text-sky-900 dark:text-sky-100">Organização</span>
                — interruptores em <strong class="font-semibold">Seções</strong> e <strong class="font-semibold">Menu</strong> aplicam-se de imediato (pedido ao servidor). Textos nos outros separadores precisam de <strong class="font-semibold">guardar</strong> no fim do formulário.
            </p>
        </div>

        <!-- Abas principais -->
        <div class="overflow-x-auto rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80">
            <div class="flex min-w-max flex-nowrap gap-1 sm:min-w-0 sm:flex-wrap">
                <button type="button" onclick="showTab('sections')" id="tab-sections"
                    class="tab-button inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200 whitespace-nowrap border-2 border-blue-600 bg-white text-blue-700 shadow-md shadow-blue-600/15 dark:bg-slate-800 dark:text-blue-300">
                    <x-icon name="layer-group" class="h-4 w-4 shrink-0" style="duotone" />
                    Seções
                </button>
                <button type="button" onclick="showTab('carousel')" id="tab-carousel"
                    class="tab-button inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200 whitespace-nowrap border-2 border-transparent text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700">
                    <x-icon name="images" class="h-4 w-4 shrink-0" style="duotone" />
                    Carousel
                </button>
                <button type="button" onclick="showTab('content')" id="tab-content"
                    class="tab-button inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200 whitespace-nowrap border-2 border-transparent text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700">
                    <x-icon name="pen-to-square" class="h-4 w-4 shrink-0" style="duotone" />
                    Conteúdo JUBAF
                </button>
                <button type="button" onclick="showTab('contact')" id="tab-contact"
                    class="tab-button inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200 whitespace-nowrap border-2 border-transparent text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700">
                    <x-icon name="address-book" class="h-4 w-4 shrink-0" style="duotone" />
                    Contato
                </button>
                <button type="button" onclick="showTab('navbar')" id="tab-navbar"
                    class="tab-button inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200 whitespace-nowrap border-2 border-transparent text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700">
                    <x-icon name="bars" class="h-4 w-4 shrink-0" style="duotone" />
                    Menu navbar
                </button>
                <button type="button" onclick="showTab('bible')" id="tab-bible"
                    class="tab-button inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200 whitespace-nowrap border-2 border-transparent text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700">
                    <x-icon name="book-bible" class="h-4 w-4 shrink-0" style="duotone" />
                    Bíblia
                </button>
                <button type="button" onclick="showTab('footer')" id="tab-footer"
                    class="tab-button inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200 whitespace-nowrap border-2 border-transparent text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700">
                    <x-icon name="shoe-prints" class="h-4 w-4 shrink-0" style="duotone" />
                    Footer
                </button>
            </div>
        </div>

        <!-- Tab Content: Sections -->
        <div id="tab-content-sections" class="tab-content">
            <div
                class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div
                    class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Gerenciar Seções da
                            Homepage</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ative ou desative seções da página inicial
                        </p>
                    </div>
                </div>
                <div class="p-6">
                    @php
                        $sections = [
                            'carousel' => [
                                'name' => 'Carousel',
                                'icon' => 'images',
                                'description' => 'Exibe slides rotativos no topo da página',
                                'special' => true,
                            ],
                            'hero' => [
                                'name' => 'Hero Section',
                                'icon' => 'billboard',
                                'description' => 'Seção principal com título e descrição',
                            ],
                            'servicos' => [
                                'name' => 'Atividades',
                                'icon' => 'briefcase',
                                'description' => 'Cartões editáveis (JSON) e links de módulos',
                            ],
                            'sobre' => [
                                'name' => 'Sobre a JUBAF',
                                'icon' => 'circle-info',
                                'description' => 'Missão, visão, valores e textos',
                            ],
                            'servicos_publicos' => [
                                'name' => 'Portais rápidos',
                                'icon' => 'building-columns',
                                'description' => 'Blog, agricultor, demandas, morador…',
                            ],
                            'contato' => [
                                'name' => 'Contato',
                                'icon' => 'address-card',
                                'description' => 'Bloco na home + página /contato (formulário e newsletter)',
                            ],
                            'public_diretoria' => [
                                'name' => 'Página Diretoria (/diretoria)',
                                'icon' => 'users',
                                'description' => 'Permite o público aceder à lista da diretoria',
                            ],
                            'public_radio' => [
                                'name' => 'Página Rádio (/radio)',
                                'icon' => 'tower-broadcast',
                                'description' => 'Transmissão e player configuráveis',
                            ],
                            'public_devotionals' => [
                                'name' => 'Devocionais (/devocionais)',
                                'icon' => 'book-open',
                                'description' => 'Lista e detalhe de devocionais publicados',
                            ],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($sections as $key => $section)
                            @php
                                // Garantir valor booleano correto
                                if ($key === 'carousel') {
                                    $enabled = (bool) $carouselEnabled;
                                } else {
                                    $enabled = isset($configs[$key . '_enabled'])
                                        ? (bool) $configs[$key . '_enabled']
                                        : true;
                                }
                            @endphp
                            <div
                                class="flex items-center justify-between p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-700 transition-colors bg-gray-50/50 dark:bg-slate-800/50">
                                <div class="flex items-center gap-4 flex-1">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <x-icon name="{{ $section['icon'] }}"
                                            class="w-6 h-6 text-blue-600 dark:text-blue-400" style="duotone" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white">{{ $section['name'] }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $section['description'] }}</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer ml-4">
                                    <input type="checkbox" class="sr-only peer section-toggle"
                                        data-section="{{ $key }}" {{ $enabled ? 'checked' : '' }} value="1">
                                    <div
                                        class="relative h-6 w-11 shrink-0 rounded-full bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:bg-blue-600 after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:shadow-sm after:transition-transform after:content-[''] peer-checked:after:translate-x-5 dark:after:border-gray-600">
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content: Carousel -->
        <div id="tab-content-carousel" class="tab-content hidden">
            <div
                class="mb-6 overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Gerenciar Carousel
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Gerencie os slides do carousel da
                                homepage</p>
                        </div>
                        <a href="{{ route('diretoria.carousel.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300/50 dark:focus:ring-indigo-900/50">
                            <x-icon name="images" class="w-5 h-5" />
                            <span>Gerenciar Slides</span>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if ($carouselSlides->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($carouselSlides->take(6) as $slide)
                                <div
                                    class="relative rounded-2xl overflow-hidden border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-shadow">
                                    @if ($slide->image && $slide->show_image)
                                        <img src="{{ asset('storage/' . $slide->image) }}"
                                            alt="{{ $slide->title ?? 'Slide' }}" class="w-full h-40 object-cover">
                                    @else
                                        <div
                                            class="w-full h-40 bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                                            <x-icon name="image" class="w-12 h-12 text-white/50" />
                                        </div>
                                    @endif
                                    <div class="p-4 bg-white dark:bg-slate-800">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                            {!! $slide->title ?? 'Sem título' !!}
                                        </div>
                                        <div class="flex items-center justify-between mt-3">
                                            <span
                                                class="text-xs px-2.5 py-1 rounded-full font-medium {{ $slide->is_active ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                {{ $slide->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400 font-mono">#{{ $slide->order }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($carouselSlides->count() > 6)
                            <div class="mt-6 text-center">
                                <a href="{{ route('diretoria.carousel.index') }}"
                                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                    Ver todos os {{ $carouselSlides->count() }} slides
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-20 h-20 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-icon name="images" class="w-10 h-10 text-gray-400 dark:text-gray-500"
                                    style="duotone" />
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nenhum slide cadastrado
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 max-w-sm mx-auto">O carousel aparecerá
                                vazio. Adicione banners para destacar informações importantes.</p>
                            <a href="{{ route('diretoria.carousel.create') }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-4 focus:ring-indigo-300/50 dark:focus:ring-indigo-900/50">
                                <x-icon name="plus" class="w-5 h-5" />
                                <span>Criar Primeiro Slide</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tab Content: Content -->
        <div id="tab-content-content" class="tab-content hidden">
            <form action="{{ route('diretoria.homepage.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div
                    class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Hero (início)</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Badge, título e texto principal quando o
                            carrossel estiver desligado ou vazio</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="hero_badge"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Badge (linha
                                acima do título)</label>
                            <input type="text" id="hero_badge" name="hero_badge"
                                value="{{ old('hero_badge', $configs['hero_badge'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                placeholder="JUBAF · Tema 2026: SOMOS UM">
                        </div>
                        <div>
                            <label for="hero_title"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Título
                                principal</label>
                            <input type="text" id="hero_title" name="hero_title"
                                value="{{ old('hero_title', $configs['hero_title'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all font-medium"
                                placeholder="Juventude Batista Feirense">
                        </div>
                        <div>
                            <label for="hero_subtitle"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Subtítulo</label>
                            <textarea id="hero_subtitle" name="hero_subtitle" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all">{{ old('hero_subtitle', $configs['hero_subtitle'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Página pública — Diretoria
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Texto de abertura em <code
                                class="text-xs bg-gray-200 dark:bg-slate-700 px-1 rounded">/diretoria</code>. Ative a
                            página e o link no menu nas abas «Seções» e «Menu Navbar».</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="diretoria_intro"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Texto
                                introdutório (opcional)</label>
                            <textarea id="diretoria_intro" name="diretoria_intro" rows="4" maxlength="2000"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                placeholder="Substitui o texto padrão sobre o Estatuto…">{{ old('diretoria_intro', $configs['diretoria_intro'] ?? '') }}</textarea>
                        </div>
                        <div class="flex flex-wrap gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="public_diretoria_enabled" value="1"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ old('public_diretoria_enabled', $configs['public_diretoria_enabled'] ?? true) ? 'checked' : '' }} />
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Página /diretoria
                                    acessível ao público</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="navbar_diretoria_enabled" value="1"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ old('navbar_diretoria_enabled', $configs['navbar_diretoria_enabled'] ?? true) ? 'checked' : '' }} />
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mostrar «Diretoria» no
                                    menu (se a página estiver ativa)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Rádio 3:16 e Devocionais
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">URLs, textos e interruptores (também
                            disponíveis nas abas Seções e Menu Navbar).</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">URL do
                                    player (iframe embed)</label>
                                <input type="url" name="radio_embed_url"
                                    value="{{ old('radio_embed_url', $configs['radio_embed_url'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white"
                                    placeholder="https://..." />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Site
                                    oficial da rádio (opcional)</label>
                                <input type="url" name="radio_official_url"
                                    value="{{ old('radio_official_url', $configs['radio_official_url'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white"
                                    placeholder="https://..." />
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Título da
                                    página /radio</label>
                                <input type="text" name="radio_page_title"
                                    value="{{ old('radio_page_title', $configs['radio_page_title'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Subtítulo
                                    /radio</label>
                                <input type="text" name="radio_page_lead"
                                    value="{{ old('radio_page_lead', $configs['radio_page_lead'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white" />
                            </div>
                        </div>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="radio_player_enabled" value="1"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                {{ old('radio_player_enabled', $configs['radio_player_enabled'] ?? true) ? 'checked' : '' }} />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mostrar iframe do player
                                (quando houver URL)</span>
                        </label>
                        <div class="border-t border-gray-100 dark:border-slate-700 pt-5 space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Título da
                                    listagem de devocionais</label>
                                <input type="text" name="devotionals_page_title"
                                    value="{{ old('devotionals_page_title', $configs['devotionals_page_title'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Texto de
                                    abertura (devocionais)</label>
                                <textarea name="devotionals_page_lead" rows="3"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">{{ old('devotionals_page_lead', $configs['devotionals_page_lead'] ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-6 pt-2">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="public_radio_enabled" value="1"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ old('public_radio_enabled', $configs['public_radio_enabled'] ?? true) ? 'checked' : '' }} />
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Página /radio
                                    pública</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="navbar_radio_enabled" value="1"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ old('navbar_radio_enabled', $configs['navbar_radio_enabled'] ?? true) ? 'checked' : '' }} />
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Link Rádio no
                                    menu</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="public_devotionals_enabled" value="1"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ old('public_devotionals_enabled', $configs['public_devotionals_enabled'] ?? true) ? 'checked' : '' }} />
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Página /devocionais
                                    pública</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="navbar_devotionals_enabled" value="1"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ old('navbar_devotionals_enabled', $configs['navbar_devotionals_enabled'] ?? true) ? 'checked' : '' }} />
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Link Devocionais no
                                    menu</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Secção de atividades</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Cabeçalho + cartões em JSON (título,
                            description, bullets[], link, link_text, accent, icon)</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="servicos_section_title"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Título da
                                secção</label>
                            <input type="text" id="servicos_section_title" name="servicos_section_title"
                                value="{{ old('servicos_section_title', $configs['servicos_section_title'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all">
                        </div>
                        <div>
                            <label for="servicos_section_subtitle"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Subtítulo</label>
                            <textarea id="servicos_section_subtitle" name="servicos_section_subtitle" rows="2"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all">{{ old('servicos_section_subtitle', $configs['servicos_section_subtitle'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label for="servicos_cards_json"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Cartões
                                (JSON)</label>
                            <textarea id="servicos_cards_json" name="servicos_cards_json" rows="18"
                                class="w-full px-4 py-3 border-2 rounded-xl bg-slate-900 text-green-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 border-slate-600 @error('servicos_cards_json') border-red-500 @enderror">{{ old('servicos_cards_json', $servicosCardsJson ?? '') }}</textarea>
                            @error('servicos_cards_json')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Sobre</h3>
                    </div>
                    <div class="p-6 grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Badge</label>
                            <input type="text" name="sobre_badge"
                                value="{{ old('sobre_badge', $configs['sobre_badge'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Título</label>
                            <input type="text" name="sobre_title"
                                value="{{ old('sobre_title', $configs['sobre_title'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Missão (card
                                azul)</label>
                            <textarea name="sobre_mission" rows="2"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">{{ old('sobre_mission', $configs['sobre_mission'] ?? '') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Visão</label>
                            <textarea name="sobre_vision" rows="2"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">{{ old('sobre_vision', $configs['sobre_vision'] ?? '') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Valores</label>
                            <textarea name="sobre_values" rows="2"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">{{ old('sobre_values', $configs['sobre_values'] ?? '') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Parágrafo
                                1</label>
                            <textarea name="sobre_p1" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">{{ old('sobre_p1', $configs['sobre_p1'] ?? '') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Parágrafo
                                2</label>
                            <textarea name="sobre_p2" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">{{ old('sobre_p2', $configs['sobre_p2'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Estatística 1
                                — valor</label>
                            <input type="text" name="sobre_stat1_value"
                                value="{{ old('sobre_stat1_value', $configs['sobre_stat1_value'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Estatística 1
                                — rótulo</label>
                            <input type="text" name="sobre_stat1_label"
                                value="{{ old('sobre_stat1_label', $configs['sobre_stat1_label'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Estatística 2
                                — valor</label>
                            <input type="text" name="sobre_stat2_value"
                                value="{{ old('sobre_stat2_value', $configs['sobre_stat2_value'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Estatística 2
                                — rótulo</label>
                            <input type="text" name="sobre_stat2_label"
                                value="{{ old('sobre_stat2_label', $configs['sobre_stat2_label'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300/50 dark:focus:ring-blue-900/50">
                        <x-icon name="floppy-disk" class="w-5 h-5" />
                        <span>Salvar conteúdo</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab Content: Contact -->
        <div id="tab-content-contact" class="tab-content hidden">
            <form action="{{ route('diretoria.homepage.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div
                    class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Informações de Contato
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure as informações de contato
                            exibidas na homepage</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="telefone"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Telefone
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-icon name="phone" class="w-5 h-5 text-gray-400" />
                                </div>
                                <input type="text" id="telefone" name="telefone"
                                    value="{{ old('telefone', $configs['telefone'] ?? '') }}"
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                    placeholder="(75) 0000-0000">
                            </div>
                        </div>
                        <div>
                            <label for="email"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                E-mail
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-icon name="envelope" class="w-5 h-5 text-gray-400" />
                                </div>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $configs['email'] ?? '') }}"
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                    placeholder="contato@jubaf.org.br">
                            </div>
                        </div>
                        <div>
                            <label for="endereco"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Endereço
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-icon name="map-location-dot" class="w-5 h-5 text-gray-400" />
                                </div>
                                <input type="text" id="endereco" name="endereco"
                                    value="{{ old('endereco', $configs['endereco'] ?? '') }}"
                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                    placeholder="Feira de Santana — BA">
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div
                        class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Página /contato &
                                newsletter</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Textos públicos, formulário e
                                inscrições regionais</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if (Route::has('diretoria.homepage.contacts.index'))
                                <a href="{{ homepage_panel_route('contacts.index') }}"
                                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-xl bg-slate-100 text-slate-800 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">
                                    <x-icon name="inbox" class="w-4 h-4" style="duotone" />
                                    Mensagens
                                </a>
                            @endif
                            @if (Route::has('diretoria.homepage.newsletter.index'))
                                <a href="{{ homepage_panel_route('newsletter.index') }}"
                                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-xl bg-indigo-100 text-indigo-900 hover:bg-indigo-200 dark:bg-indigo-900/40 dark:text-indigo-100 dark:hover:bg-indigo-900/60">
                                    <x-icon name="envelope" class="w-4 h-4" style="duotone" />
                                    Newsletter
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <label
                            class="flex items-center justify-between gap-4 p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Página pública /contato
                                ativa</span>
                            <input type="hidden" name="contato_page_enabled" value="0">
                            <input type="checkbox" name="contato_page_enabled" value="1"
                                class="w-5 h-5 rounded text-blue-600"
                                {{ old('contato_page_enabled', $configs['contato_page_enabled'] ?? true) ? 'checked' : '' }}>
                        </label>
                        <label
                            class="flex items-center justify-between gap-4 p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Formulário de mensagem na
                                página de contato</span>
                            <input type="hidden" name="contato_form_enabled" value="0">
                            <input type="checkbox" name="contato_form_enabled" value="1"
                                class="w-5 h-5 rounded text-blue-600"
                                {{ old('contato_form_enabled', $configs['contato_form_enabled'] ?? true) ? 'checked' : '' }}>
                        </label>
                        <label
                            class="flex items-center justify-between gap-4 p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Newsletter visível em
                                /contato</span>
                            <input type="hidden" name="newsletter_public_enabled" value="0">
                            <input type="checkbox" name="newsletter_public_enabled" value="1"
                                class="w-5 h-5 rounded text-blue-600"
                                {{ old('newsletter_public_enabled', $configs['newsletter_public_enabled'] ?? true) ? 'checked' : '' }}>
                        </label>
                        <div>
                            <label for="contato_page_title"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Título da página
                                de contato</label>
                            <input type="text" id="contato_page_title" name="contato_page_title"
                                value="{{ old('contato_page_title', $configs['contato_page_title'] ?? '') }}"
                                maxlength="255"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="contato_page_lead"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Texto
                                introdutório (regional)</label>
                            <textarea id="contato_page_lead" name="contato_page_lead" rows="3" maxlength="800"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">{{ old('contato_page_lead', $configs['contato_page_lead'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label for="contato_home_cta"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Texto na secção
                                Contato da homepage (convite)</label>
                            <textarea id="contato_home_cta" name="contato_home_cta" rows="2" maxlength="500"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">{{ old('contato_home_cta', $configs['contato_home_cta'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label for="newsletter_box_title"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Título do bloco
                                newsletter</label>
                            <input type="text" id="newsletter_box_title" name="newsletter_box_title"
                                value="{{ old('newsletter_box_title', $configs['newsletter_box_title'] ?? '') }}"
                                maxlength="255"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="newsletter_box_lead"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Subtítulo do
                                bloco newsletter</label>
                            <textarea id="newsletter_box_lead" name="newsletter_box_lead" rows="2" maxlength="500"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">{{ old('newsletter_box_lead', $configs['newsletter_box_lead'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300/50 dark:focus:ring-blue-900/50">
                        <x-icon name="floppy-disk" class="w-5 h-5" />
                        <span>Salvar Alterações</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab Content: Navbar -->
        <div id="tab-content-navbar" class="tab-content hidden">
            <div
                class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Gerenciar Links do Navbar</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ative ou desative links exibidos na barra de
                        navegação</p>
                </div>
                <div class="p-6">
                    @php
                        $navbarLinks = [
                            'navbar_inicio' => [
                                'name' => 'Início',
                                'icon' => 'house',
                                'description' => 'Link para a seção Início',
                            ],
                            'navbar_servicos' => [
                                'name' => 'Serviços',
                                'icon' => 'briefcase',
                                'description' => 'Link para a seção Serviços',
                            ],
                            'navbar_sobre' => [
                                'name' => 'Sobre',
                                'icon' => 'circle-info',
                                'description' => 'Link para a seção Sobre',
                            ],
                            'navbar_diretoria' => [
                                'name' => 'Diretoria',
                                'icon' => 'users',
                                'description' => 'Link para a página /diretoria (requer página ativa)',
                            ],
                            'navbar_radio' => [
                                'name' => 'Rádio 3:16',
                                'icon' => 'tower-broadcast',
                                'description' => 'Link para /radio (requer página ativa)',
                            ],
                            'navbar_devotionals' => [
                                'name' => 'Devocionais',
                                'icon' => 'book-open',
                                'description' => 'Link para /devocionais',
                            ],
                            'navbar_consulta' => [
                                'name' => 'Consultar Demanda',
                                'icon' => 'magnifying-glass',
                                'description' => 'Link para consulta de demandas',
                            ],
                            'navbar_contato' => [
                                'name' => 'Contato',
                                'icon' => 'address-book',
                                'description' => 'Link para a página de contato (/contato)',
                            ],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($navbarLinks as $key => $link)
                            @php
                                $enabled = isset($configs[$key . '_enabled'])
                                    ? (bool) $configs[$key . '_enabled']
                                    : true;
                            @endphp
                            <div
                                class="flex items-center justify-between p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-700 transition-colors bg-gray-50/50 dark:bg-slate-800/50">
                                <div class="flex items-center gap-4 flex-1">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <x-icon name="{{ $link['icon'] }}"
                                            class="w-6 h-6 text-blue-600 dark:text-blue-400" style="duotone" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white">{{ $link['name'] }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $link['description'] }}</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer ml-4">
                                    <input type="checkbox" class="sr-only peer section-toggle"
                                        data-section="{{ $key }}" {{ $enabled ? 'checked' : '' }}
                                        value="1">
                                    <div
                                        class="relative h-6 w-11 shrink-0 rounded-full bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:bg-blue-600 after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:shadow-sm after:transition-transform after:content-[''] peer-checked:after:translate-x-5 dark:after:border-gray-600">
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content: Bíblia na homepage -->
        <div id="tab-content-bible" class="tab-content hidden">
            <form action="{{ route('diretoria.homepage.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div
                    class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Bíblia na homepage</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Versículo do dia (estável por dia civil) e
                            link no menu. Requer o módulo Bíblia ativo e dados importados. As mesmas opções existem em
                            <strong>Configurações do sistema</strong> (grupo Bíblia — homepage).
                        </p>
                    </div>
                    <div class="p-6 space-y-6">
                        <label
                            class="flex items-center justify-between gap-4 p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Mostrar bloco «Versículo do
                                dia»</span>
                            <input type="hidden" name="bible_daily_enabled" value="0">
                            <input type="checkbox" name="bible_daily_enabled" value="1"
                                class="w-5 h-5 rounded text-blue-600"
                                {{ old('bible_daily_enabled', $configs['bible_daily_enabled'] ?? false) ? 'checked' : '' }}>
                        </label>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="bible_daily_version_id"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Versão da
                                    Bíblia</label>
                                <select id="bible_daily_version_id" name="bible_daily_version_id"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                                    <option value="0">Padrão do módulo</option>
                                    @foreach ($bibleVersions as $v)
                                        <option value="{{ $v->id }}" @selected((int) old('bible_daily_version_id', $configs['bible_daily_version_id'] ?? 0) === (int) $v->id)>
                                            {{ $v->name }} ({{ $v->abbreviation }})</option>
                                    @endforeach
                                </select>
                                @if ($bibleVersions->isEmpty())
                                    <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">Nenhuma versão ativa. Ative
                                        o módulo Bíblia e importe uma versão no super-admin.</p>
                                @endif
                            </div>
                            <div>
                                <label for="bible_daily_position"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Posição na
                                    página</label>
                                <select id="bible_daily_position" name="bible_daily_position"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                                    @foreach (['after_hero' => 'Depois do herói / carrossel', 'before_servicos' => 'Antes de Serviços', 'before_contato' => 'Antes de Contato'] as $val => $label)
                                        <option value="{{ $val }}" @selected(old('bible_daily_position', $configs['bible_daily_position'] ?? 'before_servicos') === $val)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="bible_daily_title"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Título da
                                secção</label>
                            <input type="text" id="bible_daily_title" name="bible_daily_title"
                                value="{{ old('bible_daily_title', $configs['bible_daily_title'] ?? 'Versículo do dia') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="bible_daily_subtitle"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Subtítulo
                                (opcional)</label>
                            <input type="text" id="bible_daily_subtitle" name="bible_daily_subtitle"
                                value="{{ old('bible_daily_subtitle', $configs['bible_daily_subtitle'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <label class="flex items-center gap-3 text-sm text-gray-800 dark:text-gray-200">
                                <input type="hidden" name="bible_daily_show_reference" value="0">
                                <input type="checkbox" name="bible_daily_show_reference" value="1"
                                    class="rounded text-blue-600"
                                    {{ old('bible_daily_show_reference', $configs['bible_daily_show_reference'] ?? true) ? 'checked' : '' }}>
                                Mostrar referência (livro cap. vers.)
                            </label>
                            <label class="flex items-center gap-3 text-sm text-gray-800 dark:text-gray-200">
                                <input type="hidden" name="bible_daily_show_version_label" value="0">
                                <input type="checkbox" name="bible_daily_show_version_label" value="1"
                                    class="rounded text-blue-600"
                                    {{ old('bible_daily_show_version_label', $configs['bible_daily_show_version_label'] ?? true) ? 'checked' : '' }}>
                                Mostrar nome/sigla da versão
                            </label>
                            <label class="flex items-center gap-3 text-sm text-gray-800 dark:text-gray-200">
                                <input type="hidden" name="bible_daily_link_enabled" value="0">
                                <input type="checkbox" name="bible_daily_link_enabled" value="1"
                                    class="rounded text-blue-600"
                                    {{ old('bible_daily_link_enabled', $configs['bible_daily_link_enabled'] ?? true) ? 'checked' : '' }}>
                                Botão «Abrir na Bíblia»
                            </label>
                        </div>

                        <div>
                            <label for="bible_daily_override_reference"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Referência fixa
                                (opcional)</label>
                            <input type="text" id="bible_daily_override_reference"
                                name="bible_daily_override_reference"
                                value="{{ old('bible_daily_override_reference', $configs['bible_daily_override_reference'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white"
                                placeholder="Ex.: João 3:16 — deixe vazio para versículo automático por dia">
                        </div>
                        <div>
                            <label for="bible_daily_salt"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Salt de variedade
                                (opcional, avançado)</label>
                            <input type="text" id="bible_daily_salt" name="bible_daily_salt"
                                value="{{ old('bible_daily_salt', $configs['bible_daily_salt'] ?? '') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white"
                                placeholder="Texto curto para alterar a escolha diária sem mudar a data">
                        </div>

                        <div class="border-t border-gray-200 dark:border-slate-700 pt-6 space-y-4">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Menu público</h4>
                            <label
                                class="flex items-center justify-between gap-4 p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 cursor-pointer">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Link «Bíblia» na barra de
                                    navegação</span>
                                <input type="hidden" name="bible_navbar_enabled" value="0">
                                <input type="checkbox" name="bible_navbar_enabled" value="1"
                                    class="w-5 h-5 rounded text-blue-600"
                                    {{ old('bible_navbar_enabled', $configs['bible_navbar_enabled'] ?? false) ? 'checked' : '' }}>
                            </label>
                            <div>
                                <label for="bible_navbar_label"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rótulo do
                                    link</label>
                                <input type="text" id="bible_navbar_label" name="bible_navbar_label"
                                    value="{{ old('bible_navbar_label', $configs['bible_navbar_label'] ?? 'Bíblia') }}"
                                    class="w-full max-w-md px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300/50 dark:focus:ring-blue-900/50">
                        <x-icon name="floppy-disk" class="w-5 h-5" />
                        <span>Salvar secção Bíblia</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab Content: Footer -->
        <div id="tab-content-footer" class="tab-content hidden">
            <form action="{{ route('diretoria.homepage.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div
                    class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Configurações do Footer
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure as informações exibidas no
                            rodapé da homepage</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="footer_descricao"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Descrição do Footer
                            </label>
                            <textarea id="footer_descricao" name="footer_descricao" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                placeholder="Texto curto sobre a JUBAF e o tema SOMOS UM...">{{ old('footer_descricao', $configs['footer_descricao'] ?? '') }}</textarea>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="footer_org_line"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Linha institucional (rodapé)
                                </label>
                                <input type="text" id="footer_org_line" name="footer_org_line"
                                    value="{{ old('footer_org_line', $configs['footer_org_line'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                    placeholder="JUBAF — Juventude Batista Feirense">
                            </div>
                            <div>
                                <label for="footer_external_link_label"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Rótulo do link externo
                                </label>
                                <input type="text" id="footer_external_link_label" name="footer_external_link_label"
                                    value="{{ old('footer_external_link_label', $configs['footer_external_link_label'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                    placeholder="Site institucional">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="footer_facebook_url"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    URL do Facebook
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-icon name="facebook" class="w-5 h-5 text-gray-400" style="brands" />
                                    </div>
                                    <input type="url" id="footer_facebook_url" name="footer_facebook_url"
                                        value="{{ $configs['footer_facebook_url'] ?? '' }}"
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                        placeholder="https://www.facebook.com/...">
                                </div>
                            </div>
                            <div>
                                <label for="footer_instagram_url"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    URL do Instagram
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-icon name="instagram" class="w-5 h-5 text-gray-400" style="brands" />
                                    </div>
                                    <input type="url" id="footer_instagram_url" name="footer_instagram_url"
                                        value="{{ $configs['footer_instagram_url'] ?? '' }}"
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                        placeholder="https://www.instagram.com/...">
                                </div>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="footer_whatsapp"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    WhatsApp (apenas números)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-icon name="whatsapp" class="w-5 h-5 text-gray-400" style="brands" />
                                    </div>
                                    <input type="text" id="footer_whatsapp" name="footer_whatsapp"
                                        value="{{ $configs['footer_whatsapp'] ?? '' }}"
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                        placeholder="557532482489">
                                </div>
                            </div>
                            <div>
                                <label for="footer_site_prefeitura"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    URL do site externo (opcional)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-icon name="globe" class="w-5 h-5 text-gray-400" />
                                    </div>
                                    <input type="url" id="footer_site_prefeitura" name="footer_site_prefeitura"
                                        value="{{ $configs['footer_site_prefeitura'] ?? '' }}"
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                        placeholder="https://exemplo.org">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Créditos técnicos (rodapé, opcional) -->
                <div
                    class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Créditos técnicos no
                            rodapé</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Bloco discreto no fim do rodapé (equipe
                            técnica, apoio à plataforma). Desligado por omissão.</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <label
                            class="flex items-center justify-between gap-4 p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Exibir créditos no
                                rodapé</span>
                            <input type="hidden" name="footer_credit_visible" value="0">
                            <input type="checkbox" name="footer_credit_visible" value="1"
                                class="w-5 h-5 rounded text-blue-600"
                                {{ old('footer_credit_visible', $configs['footer_credit_visible'] ?? false) ? 'checked' : '' }}>
                        </label>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="footer_credit_organization"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Organização /
                                    equipe</label>
                                <input type="text" id="footer_credit_organization" name="footer_credit_organization"
                                    value="{{ old('footer_credit_organization', $configs['footer_credit_organization'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all"
                                    placeholder="Nome opcional">
                            </div>
                            <div>
                                <label for="footer_credit_contact_name"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Contacto /
                                    responsável</label>
                                <input type="text" id="footer_credit_contact_name" name="footer_credit_contact_name"
                                    value="{{ old('footer_credit_contact_name', $configs['footer_credit_contact_name'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all">
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="footer_credit_email"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">E-mail</label>
                                <input type="email" id="footer_credit_email" name="footer_credit_email"
                                    value="{{ old('footer_credit_email', $configs['footer_credit_email'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all">
                            </div>
                            <div>
                                <label for="footer_credit_phone"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Telefone</label>
                                <input type="text" id="footer_credit_phone" name="footer_credit_phone"
                                    value="{{ old('footer_credit_phone', $configs['footer_credit_phone'] ?? '') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300/50 dark:focus:ring-blue-900/50">
                        <x-icon name="floppy-disk" class="w-5 h-5" />
                        <span>Salvar Alterações</span>
                    </button>
                </div>
            </form>
        </div>

        @push('scripts')
            <script>
                function showTab(tabName) {
                    localStorage.setItem('homepage_active_tab', tabName);

                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.add('hidden');
                    });

                    document.querySelectorAll('.tab-button').forEach(button => {
                        button.classList.remove(
                            'border-blue-600', 'bg-white', 'text-blue-700', 'shadow-md', 'shadow-blue-600/15',
                            'dark:bg-slate-800', 'dark:text-blue-300'
                        );
                        button.classList.add(
                            'border-transparent', 'text-gray-600', 'hover:bg-gray-100',
                            'dark:text-gray-400', 'dark:hover:bg-slate-700'
                        );
                    });

                    document.getElementById('tab-content-' + tabName).classList.remove('hidden');

                    const activeTab = document.getElementById('tab-' + tabName);
                    activeTab.classList.remove(
                        'border-transparent', 'text-gray-600', 'hover:bg-gray-100',
                        'dark:text-gray-400', 'dark:hover:bg-slate-700'
                    );
                    activeTab.classList.add(
                        'border-blue-600', 'bg-white', 'text-blue-700', 'shadow-md', 'shadow-blue-600/15',
                        'dark:bg-slate-800', 'dark:text-blue-300'
                    );
                }

                // Section toggle functionality
                document.addEventListener('DOMContentLoaded', function() {
                    // Restore active tab
                    const activeTab = localStorage.getItem('homepage_active_tab') || 'sections';
                    showTab(activeTab);

                    const toggles = document.querySelectorAll('.section-toggle');

                    toggles.forEach(toggle => {
                        // Prevenir múltiplos cliques simultâneos
                        let isProcessing = false;

                        toggle.addEventListener('change', function() {
                            if (isProcessing) {
                                this.checked = !this.checked; // Reverter se já estiver processando
                                return;
                            }

                            isProcessing = true;
                            const section = this.dataset.section;
                            const enabled = this.checked;
                            const originalState = !enabled;

                            // Desabilitar toggle durante requisição
                            this.disabled = true;

                            fetch('{{ route('diretoria.homepage.toggle-section') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify({
                                        section: section,
                                        enabled: enabled
                                    })
                                })
                                .then(async response => {
                                    const contentType = response.headers.get('content-type');
                                    if (!contentType || !contentType.includes('application/json')) {
                                        const text = await response.text();
                                        throw new Error(
                                            `Resposta inválida: ${text.substring(0, 100)}`);
                                    }

                                    if (!response.ok) {
                                        const errorData = await response.json().catch(() => ({}));
                                        throw new Error(errorData.message ||
                                            `Erro HTTP ${response.status}`);
                                    }

                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        // Show notification
                                        showNotification(data.message, 'success');
                                    } else {
                                        // Revert toggle on error
                                        this.checked = originalState;
                                        showNotification(data.message || 'Erro ao atualizar seção',
                                            'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Erro:', error);
                                    // Revert toggle on error
                                    this.checked = originalState;
                                    showNotification('Erro ao atualizar seção. Tente novamente.',
                                        'error');
                                })
                                .finally(() => {
                                    isProcessing = false;
                                    this.disabled = false;
                                });
                        });
                    });

                    function showNotification(message, type) {
                        const notification = document.createElement('div');
                        notification.className = `fixed top-24 right-4 px-6 py-4 rounded-xl shadow-xl z-50 animate-in slide-in-from-right flex items-center gap-3 border ${
                type === 'success'
                    ? 'bg-white dark:bg-gray-800 border-blue-600 text-blue-800 dark:text-blue-400'
                    : 'bg-white dark:bg-gray-800 border-red-500 text-red-800 dark:text-red-400'
            }`;

                        const icon = type === 'success' ?
                            '<i class="fa-duotone fa-check-circle text-xl"></i>' :
                            '<i class="fa-duotone fa-triangle-exclamation text-xl"></i>';

                        notification.innerHTML = `${icon} <span class="font-medium">${message}</span>`;

                        document.body.appendChild(notification);
                        setTimeout(() => {
                            notification.style.opacity = '0';
                            notification.style.transition = 'opacity 0.3s';
                            setTimeout(() => notification.remove(), 300);
                        }, 3000);
                    }
                });
            </script>
        @endpush
    </div>
@endsection
