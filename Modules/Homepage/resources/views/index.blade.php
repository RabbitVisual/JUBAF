@extends('homepage::layouts.homepage')

@section('title', 'Juventude Batista Feirense — SOMOS UM')

@section('content')
    @include('homepage::layouts.navbar-homepage')

    <div
        class="min-h-screen bg-gradient-to-br from-slate-200 via-slate-100 to-blue-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Avisos no Topo (wrapper só quando houver avisos; evita espaço vazio com carrossel on/off) -->
        @if (module_enabled('Avisos'))
            <x-avisos::avisos-por-posicao posicao="topo" :container="true" />
        @endif

        <!-- Carousel Section -->
        @if ($carouselEnabled && $carouselSlides->count() > 0)
            <section class="jubaf-hero-surface relative overflow-hidden">
                <div data-hs-carousel='{
            "loadingClasses": "opacity-0",
            "isAutoPlay": true,
            "speed": 5000,
            "isInfiniteLoop": true
        }'
                    class="relative" id="homepageCarousel">
                    <div class="hs-carousel relative overflow-hidden w-full h-[400px] md:h-[500px] lg:h-[600px]">
                        <div class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700"
                            style="opacity: 1;">
                            @foreach ($carouselSlides as $index => $slide)
                                <div class="hs-carousel-slide flex-shrink-0 w-full h-full">
                                    <div class="relative w-full h-full flex items-center justify-center">
                                        @if ($slide->show_image && $slide->image)
                                            <div class="absolute inset-0 w-full h-full">
                                                <img src="{{ asset('storage/' . $slide->image) }}"
                                                    alt="{{ $slide->title ?? 'Slide ' . ($index + 1) }}"
                                                    class="w-full h-full object-cover">
                                                <div class="jubaf-carousel-scrim absolute inset-0"></div>
                                            </div>
                                        @else
                                            <div class="jubaf-hero-surface absolute inset-0 w-full h-full"></div>
                                        @endif

                                        <div
                                            class="relative z-10 w-full container mx-auto px-4 py-12 md:py-16 lg:py-20 text-center text-white">
                                            <div class="max-w-4xl mx-auto space-y-6">
                                                @if ($slide->title)
                                                    @php
                                                    $titleContent = $slide->title;
                                                    $styleTags = '';
                                                    $scriptTags = '';

                                                    if (preg_match_all('/<style[^>]*>.*?<\ /style>/is', $titleContent,
                                                            $styleMatches)) {
                                                            $styleTags = implode("\n", $styleMatches[0]);
                                                            $titleContent = preg_replace('/<style[^>]*>.*?<\ /style>/is',
                                                                    '', $titleContent);
                                                                    }

                                                                    if (preg_match_all('/
                                                                    <script[^>]*>.*?<\ /script>/is', $titleContent, $scriptMatches)) {
                                                                            $scriptTags = implode("\n", $scriptMatches[0]);
                                                                            $titleContent = preg_replace('/<script[^>]*>.*?<\ /script>/is', '', $titleContent);
                                                                                    }

                                                                                    $titleContent = preg_replace('/<p[^>]*>\s*<\ /p>/i', '', $titleContent);
                                                                                            $titleContent = preg_replace('/<p[^>]*>(\s*<br\s*\ /?>\s*)*<\ /p>/i', '', $titleContent);
                                                                                                        $titleContent = preg_replace('/<p[^>]*>(.*?)<\ /p>/is', '$1', $titleContent);
                                                                                                                $titleContent = trim($titleContent);
                                                                                                                @endphp
                                                                                                                @if ($styleTags)
                                                                                                                {!! $styleTags !!}
                                                                                                                @endif
                                                                                                                <div
                                                                                                                    class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight carousel-title">
                                                                                                                    {!! $titleContent !!}
                                                                                                                </div>
                                                                                                                @if ($scriptTags)
                                                                                                                {!! $scriptTags !!}
                                                                                                                @endif
                                                                                                                @endif

                                                                                                                @if ($slide->description)
                                                                                                                @php
                                                                                                                    $descContent = $slide->description;
                                                                                                                    $descStyleTags = '';
                                                                                                                    $descScriptTags = '';

                                                                                                                    if (preg_match_all('/<style[^>]*>.*?<\/style>/is', $descContent, $descStyleMatches)) {
                                                                                                                        $descStyleTags = implode("\n", $descStyleMatches[0]);
                                                                                                                        $descContent = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $descContent);
                                                                                                                    }

                                                                                                                    if (preg_match_all('/<script[^>]*>.*?<\/script>/is', $descContent, $descScriptMatches)) {
                                                                                                                        $descScriptTags = implode("\n", $descScriptMatches[0]);
                                                                                                                        $descContent = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $descContent);
                                                                                                                    }

                                                                                                                    $descContent = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $descContent);
                                                                                                                $descContent = preg_replace('/<p[^>]*>(\s*<br\s*\/@endphp\s*)*<\/p>/i', '', $descContent);
$descContent = preg_replace('/<p[^>]*>(.*?)<\/p>/is', '$1<br>', $descContent);
$descContent = preg_replace('/(<br\s*\/?>\s*)+$/i', '', $descContent);
$descContent = trim($descContent);
?>
                                                                                                                @if ($descStyleTags)
                                                                                                                {!! $descStyleTags !!}
                                                                                                                @endif
                                                                                                                <div
                                                                                                                    class="text-lg md:text-xl lg:text-2xl text-white/95 leading-relaxed carousel-description">
                                                                                                                    {!! $descContent !!}
                                                                                                                </div>
                                                                                                                @if ($descScriptTags)
                                                                                                                {!! $descScriptTags !!}
                                                                                                                @endif
                                                                                                                @endif

                                                                                                                @if ($slide->link)
                                                                                                                <div class="pt-4">
                                                                                                                    <a href="{{ $slide->link }}"
                                                                                                                        target="{{ str_starts_with($slide->link, 'http') ? '_blank' : '_self' }}"
                                                                                                                        class="inline-flex items-center gap-2 bg-white text-blue-700 px-8 py-4 rounded-xl font-semibold hover:bg-blue-50 hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                                                                                        <span>{{ $slide->link_text ?? 'Saiba mais' }}</span>
                                                                                                                        <x-icon name="arrow-right" class="w-5 h-5" />
                                                                                                                    </a>
                                                                                                                </div>
                                                                                                                @endif
                                                                                                                </div>
                                                                                                                </div>
                                                                                                                </div>
                                                                                                                </div>
                                                                                                                @endforeach
                                                                                                                </div>
                                                                                                                </div>

                                                                                                                <!-- Navigation Buttons -->
                                                                                                                <button type="button"
                                                                                                                    class="hs-carousel-prev absolute top-1/2 start-4 -translate-y-1/2 w-12 h-12 md:w-14 md:h-14 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg z-10">
                                                                                                                    <span class="sr-only">Anterior</span>
                                                                                                                    <x-icon name="chevron-left" class="w-6 h-6 md:w-7 md:h-7" />
                                                                                                                </button>

                                                                                                                <button type="button"
                                                                                                                    class="hs-carousel-next absolute top-1/2 end-4 -translate-y-1/2 w-12 h-12 md:w-14 md:h-14 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg z-10">
                                                                                                                    <span class="sr-only">Próximo</span>
                                                                                                                    <x-icon name="chevron-right" class="w-6 h-6 md:w-7 md:h-7" />
                                                                                                                </button>

                                                                                                                <!-- Pagination -->
                                                                                                                <div
                                                                                                                    class="hs-carousel-pagination flex justify-center gap-2 absolute bottom-4 start-0 end-0 z-10">
                                                                                                                    @foreach ($carouselSlides as $index => $slide)
                                                                                                                    <span
                                                                                                                        class="hs-carousel-pagination-item w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white/80 transition-colors cursor-pointer"></span>
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                                </div>
                                                                                                                </section>
                                                                                                                @endif

                                                                                                                <!-- Hero Section -->
                                                                                                                @if ($configs['hero_enabled'] ?? true)
                                                                                                                <section id="inicio"
                                                                                                                    class="jubaf-hero-surface relative overflow-hidden py-16 lg:py-24 {{ $carouselEnabled && $carouselSlides->count() > 0 ? 'hidden' : '' }}">
                                                                                                                    <div class="absolute inset-0 opacity-10">
                                                                                                                        <div class="absolute inset-0"
                                                                                                                            style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-size: 60px 60px;">
                                                                                                                        </div>
                                                                                                                    </div>

                                                                                                                    <div class="container mx-auto px-4 relative z-10">
                                                                                                                        <div class="grid lg:grid-cols-2 gap-12 items-center">
                                                                                                                            <div class="space-y-6 text-center lg:text-left text-white">
                                                                                                                                <div
                                                                                                                                    class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full text-sm font-medium text-white">
                                                                                                                                    <x-icon name="sparkles" style="duotone"
                                                                                                                                        class="w-5 h-5" />
                                                                                                                                    <span>{{ $configs['hero_badge'] ?? 'JUBAF · SOMOS UM' }}</span>
                                                                                                                                </div>

                                                                                                                                <h1
                                                                                                                                    class="text-4xl md:text-5xl lg:text-6xl font-bold font-poppins leading-tight text-white">
                                                                                                                                    {{ $configs['hero_title'] ?? 'Juventude Batista Feirense' }}
                                                                                                                                </h1>

                                                                                                                                <p
                                                                                                                                    class="text-lg md:text-xl text-white leading-relaxed max-w-2xl mx-auto lg:mx-0 opacity-95">
                                                                                                                                    {{ $configs['hero_subtitle'] ?? '' }}
                                                                                                                                </p>

                                                                                                                                <div
                                                                                                                                    class="flex flex-wrap gap-3 sm:gap-4 pt-4 justify-center lg:justify-start">
                                                                                                                                    @if (Route::has('blog.index'))
                                                                                                                                    <a href="{{ route('blog.index') }}"
                                                                                                                                        class="group inline-flex items-center gap-2 bg-white text-blue-800 px-5 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold hover:bg-blue-50 hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl text-sm sm:text-base">
                                                                                                                                        <x-icon name="newspaper" style="duotone"
                                                                                                                                            class="w-4 h-4 sm:w-5 sm:h-5" />
                                                                                                                                        <span class="hidden xs:inline">Notícias &
                                                                                                                                            blog</span>
                                                                                                                                        <span class="xs:hidden">Blog</span>
                                                                                                                                    </a>
                                                                                                                                    @endif
                                                                                                                                    @if (Route::has('demandas.public.consulta'))
                                                                                                                                    <a href="{{ route('demandas.public.consulta') }}"
                                                                                                                                        class="group inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-5 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold hover:bg-white/20 hover:scale-105 transition-all duration-300 text-sm sm:text-base">
                                                                                                                                        <x-icon name="magnifying-glass" style="duotone"
                                                                                                                                            class="w-4 h-4 sm:w-5 sm:h-5" />
                                                                                                                                        <span class="hidden xs:inline">Consultar
                                                                                                                                            Demanda</span>
                                                                                                                                        <span class="xs:hidden">Demandas</span>
                                                                                                                                    </a>
                                                                                                                                    @endif
                                                                                                                                    <a href="#servicos"
                                                                                                                                        class="group inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-5 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold hover:bg-white/20 hover:scale-105 transition-all duration-300 text-sm sm:text-base">
                                                                                                                                        <x-icon name="grid-2" style="duotone"
                                                                                                                                            class="w-4 h-4 sm:w-5 sm:h-5" />
                                                                                                                                        <span class="hidden xs:inline">Nossas
                                                                                                                                            atividades</span>
                                                                                                                                        <span class="xs:hidden">Atividades</span>
                                                                                                                                    </a>
                                                                                                                                </div>

                                                                                                                                @if (!empty($configs['telefone']) || !empty($configs['email']))
                                                                                                                                <div
                                                                                                                                    class="flex flex-wrap gap-6 pt-6 text-white/90 justify-center lg:justify-start">
                                                                                                                                    @if (!empty($configs['telefone']))
                                                                                                                                    <div class="flex items-center gap-2">
                                                                                                                                        <x-icon name="phone" style="duotone"
                                                                                                                                            class="w-5 h-5 text-sky-200" />
                                                                                                                                        <span
                                                                                                                                            class="text-sm">{{ $configs['telefone'] }}</span>
                                                                                                                                    </div>
                                                                                                                                    @endif
                                                                                                                                    @if (!empty($configs['email']))
                                                                                                                                    <div class="flex items-center gap-2">
                                                                                                                                        <x-icon name="envelope" style="duotone"
                                                                                                                                            class="w-5 h-5 text-sky-200" />
                                                                                                                                        <span
                                                                                                                                            class="text-sm">{{ $configs['email'] }}</span>
                                                                                                                                    </div>
                                                                                                                                    @endif
                                                                                                                                </div>
                                                                                                                                @endif
                                                                                                                            </div>

                                                                                                                            <div class="hidden lg:block animate-float">
                                                                                                                                <div class="relative flex justify-center">
                                                                                                                                    {{-- Fundo do hero é escuro: usar logo claro (transparente / traço claro), não o logo padrão para papel branco --}}
                                                                                                                                    <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}"
                                                                                                                                        alt="{{ \App\Support\SiteBranding::siteName() }}"
                                                                                                                                        class="w-full max-w-md mx-auto drop-shadow-2xl object-contain">
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </section>
                                                                                                                @endif

                                                                                                                <!-- Avisos no Meio -->
                                                                                                                @if (module_enabled('Avisos'))
                                                                                                                <x-avisos::avisos-por-posicao posicao="meio" :container="true"
                                                                                                                    container-class="container mx-auto px-4 sm:px-6 lg:px-8 py-8" />
                                                                                                                @endif

                                                                                                                @if (!empty($bibleDailyBlock) && ($bibleDailyBlock['position'] ?? '') === 'after_hero')
                                                                                                                    @include('homepage::partials.bible-daily-section', ['block' => $bibleDailyBlock])
                                                                                                                @endif

                                                                                                                <!-- Serviços / Atividades -->
                                                                                                                @if (!empty($bibleDailyBlock) && ($bibleDailyBlock['position'] ?? '') === 'before_servicos')
                                                                                                                    @include('homepage::partials.bible-daily-section', ['block' => $bibleDailyBlock])
                                                                                                                @endif

                                                                                                                @if ($configs['servicos_enabled'] ?? true)
                                                                                                                @php
                                                                                                                    $accentUi = [
                                                                                                                        'blue' => ['b' => 'border-blue-100 dark:border-blue-900/50 hover:border-blue-300 dark:hover:border-blue-700', 'g' => 'from-blue-100/50 dark:from-blue-900/20', 'i' => 'from-blue-500 to-blue-600', 't' => 'text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300', 'c' => 'text-blue-500'],
                                                                                                                        'indigo' => ['b' => 'border-indigo-100 dark:border-indigo-900/50 hover:border-indigo-300 dark:hover:border-indigo-700', 'g' => 'from-indigo-100/50 dark:from-indigo-900/20', 'i' => 'from-indigo-500 to-indigo-600', 't' => 'text-indigo-600 dark:text-indigo-400 font-semibold hover:text-indigo-700 dark:hover:text-indigo-300', 'c' => 'text-indigo-500'],
                                                                                                                        'violet' => ['b' => 'border-violet-100 dark:border-violet-900/50 hover:border-violet-300 dark:hover:border-violet-700', 'g' => 'from-violet-100/50 dark:from-violet-900/20', 'i' => 'from-violet-500 to-violet-600', 't' => 'text-violet-600 dark:text-violet-400 font-semibold hover:text-violet-700 dark:hover:text-violet-300', 'c' => 'text-violet-500'],
                                                                                                                        'cyan' => ['b' => 'border-cyan-100 dark:border-cyan-900/50 hover:border-cyan-300 dark:hover:border-cyan-700', 'g' => 'from-cyan-100/50 dark:from-cyan-900/20', 'i' => 'from-cyan-500 to-teal-500', 't' => 'text-cyan-600 dark:text-cyan-400 font-semibold hover:text-cyan-700 dark:hover:text-cyan-300', 'c' => 'text-cyan-500'],
                                                                                                                        'amber' => ['b' => 'border-amber-100 dark:border-amber-900/50 hover:border-amber-300 dark:hover:border-amber-700', 'g' => 'from-amber-100/50 dark:from-amber-900/20', 'i' => 'from-amber-500 to-orange-500', 't' => 'text-amber-600 dark:text-amber-400 font-semibold hover:text-amber-700 dark:hover:text-amber-300', 'c' => 'text-amber-500'],
                                                                                                                        'purple' => ['b' => 'border-purple-100 dark:border-purple-900/50 hover:border-purple-300 dark:hover:border-purple-700', 'g' => 'from-purple-100/50 dark:from-purple-900/20', 'i' => 'from-purple-500 to-purple-600', 't' => 'text-purple-600 dark:text-purple-400 font-semibold hover:text-purple-700 dark:hover:text-purple-300', 'c' => 'text-purple-500'],
                                                                                                                        'green' => ['b' => 'border-green-100 dark:border-green-900/50 hover:border-green-300 dark:hover:border-green-700', 'g' => 'from-green-100/50 dark:from-green-900/20', 'i' => 'from-green-500 to-emerald-600', 't' => 'text-green-600 dark:text-green-400 font-semibold hover:text-green-700 dark:hover:text-green-300', 'c' => 'text-green-500'],
                                                                                                                    ];
                                                                                                                @endphp
                                                                                                                <section id="servicos"
                                                                                                                    class="py-20 bg-gradient-to-br from-gray-50 via-white to-blue-50/30 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
                                                                                                                    <div class="container mx-auto px-4">
                                                                                                                        <div class="text-center mb-16">
                                                                                                                            <div
                                                                                                                                class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                                                                                                                                <x-icon name="heart" style="duotone" class="w-4 h-4" />
                                                                                                                                JUBAF
                                                                                                                            </div>
                                                                                                                            <h2
                                                                                                                                class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-gray-900 dark:text-white mb-4">
                                                                                                                                {{ $configs['servicos_section_title'] ?? 'O que vivemos juntos' }}
                                                                                                                            </h2>
                                                                                                                            <p
                                                                                                                                class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                                                                                                                                {{ $configs['servicos_section_subtitle'] ?? '' }}
                                                                                                                            </p>
                                                                                                                        </div>

                                                                                                                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                                                                                                                            @if (Route::has('demandas.public.consulta'))
                                                                                                                            <div
                                                                                                                                class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border-2 border-blue-100 dark:border-blue-900/50 hover:border-blue-300 dark:hover:border-blue-700 overflow-hidden">
                                                                                                                                <div
                                                                                                                                    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100/50 to-transparent dark:from-blue-900/20 rounded-bl-full">
                                                                                                                                </div>
                                                                                                                                <div class="relative">
                                                                                                                                    <div
                                                                                                                                        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                                                                                                                                        <x-icon module="demandas"
                                                                                                                                            class="w-8 h-8 text-white" />
                                                                                                                                    </div>
                                                                                                                                    <h3
                                                                                                                                        class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                                                                                                                                        Atendimento de Demandas</h3>
                                                                                                                                    <p
                                                                                                                                        class="text-gray-700 dark:text-gray-300 mb-5 leading-relaxed text-sm">
                                                                                                                                        Solicite melhorias na sua região e acompanhe o
                                                                                                                                        protocolo em tempo real.
                                                                                                                                    </p>
                                                                                                                                    <ul class="space-y-2.5 mb-6">
                                                                                                                                        <li class="flex items-start gap-2.5">
                                                                                                                                            <x-icon name="check-circle" style="duotone"
                                                                                                                                                class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                                                                                                                                            <span
                                                                                                                                                class="text-sm text-gray-600 dark:text-gray-400">Acompanhe
                                                                                                                                                o status da solicitação</span>
                                                                                                                                        </li>
                                                                                                                                        <li class="flex items-start gap-2.5">
                                                                                                                                            <x-icon name="check-circle"
                                                                                                                                                class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                                                                                                                                            <span
                                                                                                                                                class="text-sm text-gray-600 dark:text-gray-400">Notificações
                                                                                                                                                por e-mail</span>
                                                                                                                                        </li>
                                                                                                                                    </ul>
                                                                                                                                    <a href="{{ route('demandas.public.consulta') }}"
                                                                                                                                        class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300 transition-colors group-hover:gap-3">
                                                                                                                                        <span>Consultar minha demanda</span>
                                                                                                                                        <x-icon name="arrow-right" style="duotone"
                                                                                                                                            class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                                                                                                                    </a>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            @endif

                                                                                                                            @foreach ($servicosCards ?? [] as $card)
                                                                                                                            @php
                                                                                                                                $acc = $accentUi[$card['accent'] ?? 'blue'] ?? $accentUi['blue'];
                                                                                                                                $iconName = $card['icon'] ?? 'heart';
                                                                                                                                $bullets = $card['bullets'] ?? [];
                                                                                                                            @endphp
                                                                                                                            <div
                                                                                                                                class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border-2 {{ $acc['b'] }} overflow-hidden">
                                                                                                                                <div
                                                                                                                                    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br {{ $acc['g'] }} to-transparent rounded-bl-full">
                                                                                                                                </div>
                                                                                                                                <div class="relative">
                                                                                                                                    <div
                                                                                                                                        class="w-16 h-16 bg-gradient-to-br {{ $acc['i'] }} rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                                                                                                                                        <x-icon name="{{ $iconName }}" style="duotone"
                                                                                                                                            class="w-8 h-8 text-white" />
                                                                                                                                    </div>
                                                                                                                                    <h3
                                                                                                                                        class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                                                                                                                                        {{ $card['title'] ?? '' }}</h3>
                                                                                                                                    <p
                                                                                                                                        class="text-gray-700 dark:text-gray-300 mb-5 leading-relaxed text-sm">
                                                                                                                                        {{ $card['description'] ?? '' }}</p>
                                                                                                                                    @if (count($bullets))
                                                                                                                                    <ul class="space-y-2.5 mb-6">
                                                                                                                                        @foreach ($bullets as $item)
                                                                                                                                        <li class="flex items-start gap-2.5">
                                                                                                                                            <x-icon name="check-circle" style="duotone"
                                                                                                                                                class="w-5 h-5 {{ $acc['c'] }} mt-0.5 flex-shrink-0" />
                                                                                                                                            <span
                                                                                                                                                class="text-sm text-gray-600 dark:text-gray-400">{{ $item }}</span>
                                                                                                                                        </li>
                                                                                                                                        @endforeach
                                                                                                                                    </ul>
                                                                                                                                    @endif
                                                                                                                                    @if (!empty($card['link']))
                                                                                                                                    <a href="{{ $card['link'] }}"
                                                                                                                                        class="inline-flex items-center gap-2 {{ $acc['t'] }} transition-colors group-hover:gap-3">
                                                                                                                                        <span>{{ $card['link_text'] ?? 'Saiba mais' }}</span>
                                                                                                                                        <x-icon name="arrow-right"
                                                                                                                                            class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                                                                                                                    </a>
                                                                                                                                    @endif
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            @endforeach

                                                                                                                            @if (Route::has('blog.index'))
                                                                                                                            <div
                                                                                                                                class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border-2 border-slate-200 dark:border-slate-600 hover:border-blue-300 dark:hover:border-blue-700 overflow-hidden">
                                                                                                                                <div
                                                                                                                                    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-100/80 to-transparent dark:from-slate-700/40 rounded-bl-full">
                                                                                                                                </div>
                                                                                                                                <div class="relative">
                                                                                                                                    <div
                                                                                                                                        class="w-16 h-16 bg-gradient-to-br from-slate-600 to-slate-800 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-all duration-300 shadow-lg">
                                                                                                                                        <x-icon name="newspaper" style="duotone"
                                                                                                                                            class="w-8 h-8 text-white" />
                                                                                                                                    </div>
                                                                                                                                    <h3
                                                                                                                                        class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                                                                                                                                        Blog & notícias</h3>
                                                                                                                                    <p
                                                                                                                                        class="text-gray-700 dark:text-gray-300 mb-5 leading-relaxed text-sm">
                                                                                                                                        Acompanhe publicações, relatórios e avisos oficiais
                                                                                                                                        quando o módulo Blog estiver ativo.</p>
                                                                                                                                    <a href="{{ route('blog.index') }}"
                                                                                                                                        class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300 transition-colors group-hover:gap-3">
                                                                                                                                        <span>Abrir blog</span>
                                                                                                                                        <x-icon name="arrow-right"
                                                                                                                                            class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                                                                                                                    </a>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            @endif

                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </section>
                                                                                                                @endif

                                                                                                                <!-- Sobre a JUBAF -->
                                                                                                                @if ($configs['sobre_enabled'] ?? true)
                                                                                                                <section id="sobre"
                                                                                                                    class="py-20 bg-gradient-to-br from-slate-50 to-gray-100 dark:from-slate-800 dark:to-slate-900">
                                                                                                                    <div class="container mx-auto px-4">
                                                                                                                        <div class="grid lg:grid-cols-2 gap-12 items-center">
                                                                                                                            <div class="relative order-2 lg:order-1">
                                                                                                                                <div
                                                                                                                                    class="jubaf-blue-panel rounded-2xl p-8 shadow-2xl">
                                                                                                                                    <div
                                                                                                                                        class="bg-white/10 backdrop-blur-sm rounded-xl p-6 space-y-4">
                                                                                                                                        <div class="flex items-center gap-3 text-white">
                                                                                                                                            <x-icon name="bullseye" style="duotone"
                                                                                                                                                class="w-8 h-8" />
                                                                                                                                            <div>
                                                                                                                                                <h4 class="font-bold text-lg">Missão</h4>
                                                                                                                                                <p class="text-sm text-white/90">
                                                                                                                                                    {{ $configs['sobre_mission'] ?? '' }}
                                                                                                                                                </p>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="flex items-center gap-3 text-white">
                                                                                                                                            <x-icon name="eye" style="duotone"
                                                                                                                                                class="w-8 h-8" />
                                                                                                                                            <div>
                                                                                                                                                <h4 class="font-bold text-lg">Visão</h4>
                                                                                                                                                <p class="text-sm text-white/90">
                                                                                                                                                    {{ $configs['sobre_vision'] ?? '' }}
                                                                                                                                                </p>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="flex items-center gap-3 text-white">
                                                                                                                                            <x-icon name="heart" style="duotone"
                                                                                                                                                class="w-8 h-8" />
                                                                                                                                            <div>
                                                                                                                                                <h4 class="font-bold text-lg">Valores</h4>
                                                                                                                                                <p class="text-sm text-white/90">
                                                                                                                                                    {{ $configs['sobre_values'] ?? '' }}
                                                                                                                                                </p>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>

                                                                                                                            <div class="space-y-6 order-1 lg:order-2">
                                                                                                                                <div
                                                                                                                                    class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-4 py-2 rounded-full text-sm font-semibold">
                                                                                                                                    <x-icon name="circle-info" style="duotone"
                                                                                                                                        class="w-4 h-4" />
                                                                                                                                    {{ $configs['sobre_badge'] ?? 'Sobre nós' }}
                                                                                                                                </div>

                                                                                                                                <h2
                                                                                                                                    class="text-3xl md:text-4xl font-bold font-poppins text-gray-900 dark:text-white">
                                                                                                                                    {{ $configs['sobre_title'] ?? 'Juventude Batista Feirense' }}
                                                                                                                                </h2>

                                                                                                                                <p
                                                                                                                                    class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                                                                                                                                    {{ $configs['sobre_p1'] ?? '' }}
                                                                                                                                </p>

                                                                                                                                <p
                                                                                                                                    class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                                                                                                                                    {{ $configs['sobre_p2'] ?? '' }}
                                                                                                                                </p>

                                                                                                                                <div class="grid grid-cols-2 gap-4 pt-4">
                                                                                                                                    <div
                                                                                                                                        class="bg-white dark:bg-slate-700 rounded-xl p-4 shadow-sm">
                                                                                                                                        <div
                                                                                                                                            class="text-3xl font-bold text-blue-700 dark:text-blue-400 mb-1">
                                                                                                                                            {{ $configs['sobre_stat1_value'] ?? '' }}</div>
                                                                                                                                        <div
                                                                                                                                            class="text-sm text-gray-600 dark:text-gray-400">
                                                                                                                                            {{ $configs['sobre_stat1_label'] ?? '' }}</div>
                                                                                                                                    </div>
                                                                                                                                    <div
                                                                                                                                        class="bg-white dark:bg-slate-700 rounded-xl p-4 shadow-sm">
                                                                                                                                        <div
                                                                                                                                            class="text-3xl font-bold text-blue-700 dark:text-blue-400 mb-1">
                                                                                                                                            {{ $configs['sobre_stat2_value'] ?? '' }}</div>
                                                                                                                                        <div
                                                                                                                                            class="text-sm text-gray-600 dark:text-gray-400">
                                                                                                                                            {{ $configs['sobre_stat2_label'] ?? '' }}</div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </section>
                                                                                                                @endif

                                                                                                                <!-- Serviços Públicos Section -->
                                                                                                                @if ($configs['servicos_publicos_enabled'] ?? true)
                                                                                                                <section id="servicos-publicos"
                                                                                                                    class="py-20 bg-gradient-to-br from-gray-50 via-white to-blue-50/30 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
                                                                                                                    <div class="container mx-auto px-4">
                                                                                                                        <div class="text-center mb-16">
                                                                                                                            <div
                                                                                                                                class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                                                                                                                                <x-icon name="hand-holding-heart" style="duotone"
                                                                                                                                    class="w-4 h-4" />
                                                                                                                                Acesso rápido
                                                                                                                            </div>
                                                                                                                            <h2
                                                                                                                                class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-gray-900 dark:text-white mb-4">
                                                                                                                                Portais e ferramentas
                                                                                                                            </h2>
                                                                                                                            <p
                                                                                                                                class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                                                                                                                                Atalhos para blog, módulos ativos e consultas públicas
                                                                                                                                quando disponíveis nesta instalação.
                                                                                                                            </p>
                                                                                                                        </div>

                                                                                                                        <div
                                                                                                                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 max-w-6xl mx-auto">
                                                                                                                            <!-- Atalho: blog / informação -->
                                                                                                                            <a href="{{ Route::has('blog.index') ? route('blog.index') : route('homepage') }}"
                                                                                                                                class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border-2 border-blue-100 dark:border-blue-900/50 hover:border-blue-300 dark:hover:border-blue-700 overflow-hidden">
                                                                                                                                <div
                                                                                                                                    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100/50 to-transparent dark:from-blue-900/20 rounded-bl-full">
                                                                                                                                </div>
                                                                                                                                <div class="relative">
                                                                                                                                    <div
                                                                                                                                        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                                                                                                                                        <x-icon name="chart-mixed" style="duotone"
                                                                                                                                            class="w-8 h-8 text-white" />
                                                                                                                                    </div>
                                                                                                                                    <h3
                                                                                                                                        class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                                                                                                                                        Conteúdo & informações
                                                                                                                                    </h3>
                                                                                                                                    <p
                                                                                                                                        class="text-gray-700 dark:text-gray-300 mb-5 leading-relaxed text-sm">
                                                                                                                                        Acesse publicações e materiais do blog ou retorne à
                                                                                                                                        página inicial.
                                                                                                                                    </p>
                                                                                                                                    <ul class="space-y-2 mb-6">
                                                                                                                                        <li class="flex items-start gap-2.5">
                                                                                                                                            <x-icon name="newspaper" style="duotone"
                                                                                                                                                class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                                                                                                                                            <span
                                                                                                                                                class="text-sm text-gray-600 dark:text-gray-400">Notícias
                                                                                                                                                e comunicados</span>
                                                                                                                                        </li>
                                                                                                                                        <li class="flex items-start gap-2.5">
                                                                                                                                            <x-icon name="house" style="duotone"
                                                                                                                                                class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                                                                                                                                            <span
                                                                                                                                                class="text-sm text-gray-600 dark:text-gray-400">Página
                                                                                                                                                inicial JUBAF</span>
                                                                                                                                        </li>
                                                                                                                                    </ul>
                                                                                                                                    <div
                                                                                                                                        class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300 transition-colors group-hover:gap-3">
                                                                                                                                        <span>Abrir</span>
                                                                                                                                        <x-icon name="arrow-right"
                                                                                                                                            class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </a>

                                                                                                                            @if (module_enabled('Blog'))
                                                                                                                            <!-- Blog & Notícias -->
                                                                                                                            <a href="{{ route('blog.index') }}"
                                                                                                                                class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border-2 border-blue-100 dark:border-blue-900/50 hover:border-blue-300 dark:hover:border-blue-700 overflow-hidden">
                                                                                                                                <div
                                                                                                                                    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100/50 to-transparent dark:from-blue-900/20 rounded-bl-full">
                                                                                                                                </div>
                                                                                                                                <div class="relative">
                                                                                                                                    <div
                                                                                                                                        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                                                                                                                                        <x-icon name="newspaper" style="duotone"
                                                                                                                                            class="w-8 h-8 text-white" />
                                                                                                                                    </div>
                                                                                                                                    <h3
                                                                                                                                        class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                                                                                                                                        Blog & Notícias
                                                                                                                                    </h3>
                                                                                                                                    <p
                                                                                                                                        class="text-gray-700 dark:text-gray-300 mb-5 leading-relaxed text-sm">
                                                                                                                                        Acompanhe artigos e comunicados oficiais da JUBAF e
                                                                                                                                        dos módulos integrados.
                                                                                                                                    </p>
                                                                                                                                    <ul class="space-y-2 mb-6">
                                                                                                                                        <li class="flex items-start gap-2.5">
                                                                                                                                            <x-icon name="memo-circle-check" style="duotone"
                                                                                                                                                class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                                                                                                                                            <span
                                                                                                                                                class="text-sm text-gray-600 dark:text-gray-400">Notícias
                                                                                                                                                atualizadas</span>
                                                                                                                                        </li>
                                                                                                                                        <li class="flex items-start gap-2.5">
                                                                                                                                            <x-icon name="file-chart-column" style="duotone"
                                                                                                                                                class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                                                                                                                                            <span
                                                                                                                                                class="text-sm text-gray-600 dark:text-gray-400">Materiais
                                                                                                                                                e relatórios</span>
                                                                                                                                        </li>
                                                                                                                                    </ul>
                                                                                                                                    <div
                                                                                                                                        class="flex items-center text-blue-600 dark:text-blue-400 font-medium group-hover:gap-3 transition-all duration-300">
                                                                                                                                        <span class="text-sm">Acessar Blog</span>
                                                                                                                                        <x-icon name="arrow-right"
                                                                                                                                            class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" />
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </a>
                                                                                                                            @endif

                                                                                                                            @if (Route::has('demandas.public.consulta'))
                                                                                                                            <!-- Consulta de Demandas -->
                                                                                                                            <a href="{{ route('demandas.public.consulta') }}"
                                                                                                                                class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border-2 border-blue-100 dark:border-blue-900/50 hover:border-blue-300 dark:hover:border-blue-700 overflow-hidden">
                                                                                                                                <div
                                                                                                                                    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100/50 to-transparent dark:from-blue-900/20 rounded-bl-full">
                                                                                                                                </div>
                                                                                                                                <div class="relative">
                                                                                                                                    <div
                                                                                                                                        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                                                                                                                                        <x-icon name="magnifying-glass" style="duotone"
                                                                                                                                            class="w-8 h-8 text-white" />
                                                                                                                                    </div>
                                                                                                                                    <h3
                                                                                                                                        class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                                                                                                                                        Consultar Demanda
                                                                                                                                    </h3>
                                                                                                                                    <p
                                                                                                                                        class="text-gray-700 dark:text-gray-300 mb-5 leading-relaxed text-sm">
                                                                                                                                        Acompanhe o status da sua solicitação em tempo real
                                                                                                                                        usando o código do protocolo.
                                                                                                                                    </p>
                                                                                                                                    <ul class="space-y-2 mb-6">
                                                                                                                                        <li class="flex items-start gap-2.5">
                                                                                                                                            <x-icon name="barcode-read" style="duotone"
                                                                                                                                                class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                                                                                                                                            <span
                                                                                                                                                class="text-sm text-gray-600 dark:text-gray-400">Consulta
                                                                                                                                                por código/protocolo</span>
                                                                                                                                        </li>
                                                                                                                                        <li class="flex items-start gap-2.5">
                                                                                                                                            <x-icon name="clock" style="duotone"
                                                                                                                                                class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                                                                                                                                            <span
                                                                                                                                                class="text-sm text-gray-600 dark:text-gray-400">Acompanhamento
                                                                                                                                                em tempo real</span>
                                                                                                                                        </li>
                                                                                                                                        <li class="flex items-start gap-2.5">
                                                                                                                                            <x-icon name="file-contract" style="duotone"
                                                                                                                                                class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" />
                                                                                                                                            <span
                                                                                                                                                class="text-sm text-gray-600 dark:text-gray-400">Histórico
                                                                                                                                                completo da solicitação</span>
                                                                                                                                        </li>
                                                                                                                                    </ul>
                                                                                                                                    <div
                                                                                                                                        class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300 transition-colors group-hover:gap-3">
                                                                                                                                        <span>Consultar agora</span>
                                                                                                                                        <x-icon name="arrow-right"
                                                                                                                                            class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </a>
                                                                                                                            @endif
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </section>
                                                                                                                @endif

                                                                                                                @if (!empty($bibleDailyBlock) && ($bibleDailyBlock['position'] ?? '') === 'before_contato')
                                                                                                                    @include('homepage::partials.bible-daily-section', ['block' => $bibleDailyBlock])
                                                                                                                @endif

                                                                                                                <!-- Contato Section -->
                                                                                                                @if ($configs['contato_enabled'] ?? true)
                                                                                                                <section id="contato"
                                                                                                                    class="py-20 bg-gradient-to-br from-slate-50 to-gray-100 dark:from-slate-800 dark:to-slate-900">
                                                                                                                    <div class="container mx-auto px-4">
                                                                                                                        <div class="max-w-4xl mx-auto">
                                                                                                                            <div class="text-center mb-12">
                                                                                                                                <h2
                                                                                                                                    class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-gray-900 dark:text-white mb-4">
                                                                                                                                    Entre em Contato
                                                                                                                                </h2>
                                                                                                                                <p class="text-lg text-gray-600 dark:text-gray-300">
                                                                                                                                    {{ $configs['contato_home_cta'] ?? 'Formulário completo, dados institucionais e newsletter — abra a página de contato.' }}
                                                                                                                                </p>
                                                                                                                                <div class="mt-8">
                                                                                                                                    <a href="{{ route('contato') }}"
                                                                                                                                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:from-blue-700 hover:to-blue-900">
                                                                                                                                        <x-icon name="envelope" style="duotone" class="h-5 w-5" />
                                                                                                                                        Página completa de contato
                                                                                                                                        <x-icon name="arrow-right" class="h-4 w-4" />
                                                                                                                                    </a>
                                                                                                                                </div>
                                                                                                                            </div>

                                                                                                                            <div class="grid md:grid-cols-3 gap-8">
                                                                                                                                <div
                                                                                                                                    class="bg-white dark:bg-slate-700 rounded-2xl p-8 shadow-lg text-center">
                                                                                                                                    <div
                                                                                                                                        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                                                                                                                                        <x-icon name="phone" style="duotone"
                                                                                                                                            class="w-8 h-8 text-white" />
                                                                                                                                    </div>
                                                                                                                                    <h3
                                                                                                                                        class="font-bold text-gray-900 dark:text-white mb-2">
                                                                                                                                        Telefone</h3>
                                                                                                                                    <p class="text-gray-600 dark:text-gray-400">
                                                                                                                                        {{ $configs['telefone'] ?? '—' }}</p>
                                                                                                                                </div>

                                                                                                                                <div
                                                                                                                                    class="bg-white dark:bg-slate-700 rounded-2xl p-8 shadow-lg text-center">
                                                                                                                                    <div
                                                                                                                                        class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center mx-auto mb-4">
                                                                                                                                        <x-icon name="envelope" style="duotone"
                                                                                                                                            class="w-8 h-8 text-white" />
                                                                                                                                    </div>
                                                                                                                                    <h3
                                                                                                                                        class="font-bold text-gray-900 dark:text-white mb-2">
                                                                                                                                        E-mail</h3>
                                                                                                                                    <p
                                                                                                                                        class="text-gray-600 dark:text-gray-400 text-sm break-all">
                                                                                                                                        {{ $configs['email'] ?? '—' }}</p>
                                                                                                                                </div>

                                                                                                                                <div
                                                                                                                                    class="bg-white dark:bg-slate-700 rounded-2xl p-8 shadow-lg text-center">
                                                                                                                                    <div
                                                                                                                                        class="w-16 h-16 bg-gradient-to-br from-slate-600 to-slate-800 rounded-xl flex items-center justify-center mx-auto mb-4">
                                                                                                                                        <x-icon name="map-location-dot" style="duotone"
                                                                                                                                            class="w-8 h-8 text-white" />
                                                                                                                                    </div>
                                                                                                                                    <h3
                                                                                                                                        class="font-bold text-gray-900 dark:text-white mb-2">
                                                                                                                                        Endereço</h3>
                                                                                                                                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                                                                                                                                        {{ $configs['endereco'] ?? '—' }}</p>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </section>
                                                                                                                @endif
                                                                                                                </div>

                                                                                                                @include('homepage::layouts.footer-homepage')

                                                                                                                <!-- Back to Top Button -->
                                                                                                                <button id="backToTop"
                                                                                                                    class="fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-r from-[#0047AB] to-blue-800 dark:from-blue-600 dark:to-slate-800 text-white rounded-full shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 opacity-0 invisible z-50 flex items-center justify-center group">
                                                                                                                    <x-icon name="arrow-up"
                                                                                                                        class="w-6 h-6 group-hover:-translate-y-1 transition-transform" />
                                                                                                                </button>

                                                                                                                @push('scripts')
                                                                                                                <script>
                                                                                                                    document.addEventListener('DOMContentLoaded', function() {
                                                                                                                        // Smooth scroll para links âncora
                                                                                                                        document.querySelectorAll('a[href^="#"]').forEach(
                                                                                                                        anchor => {
                                                                                                                            anchor.addEventListener('click', function(e) {
                                                                                                                                const href = this.getAttribute(
                                                                                                                                    'href');
                                                                                                                                if (href !== '#' && href.length >
                                                                                                                                    1) {
                                                                                                                                    e.preventDefault();
                                                                                                                                    const target = document
                                                                                                                                        .querySelector(href);
                                                                                                                                    if (target) {
                                                                                                                                        const offsetTop = target
                                                                                                                                            .offsetTop - 80;
                                                                                                                                        window.scrollTo({
                                                                                                                                            top: offsetTop,
                                                                                                                                            behavior: 'smooth'
                                                                                                                                        });
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            });
                                                                                                                        });

                                                                                                                        // Back to Top Button
                                                                                                                        const backToTopBtn = document.getElementById('backToTop');
                                                                                                                        if (backToTopBtn) {
                                                                                                                            window.addEventListener('scroll', function() {
                                                                                                                                if (window.pageYOffset > 300) {
                                                                                                                                    backToTopBtn.classList.remove(
                                                                                                                                        'opacity-0', 'invisible');
                                                                                                                                    backToTopBtn.classList.add(
                                                                                                                                        'opacity-100', 'visible');
                                                                                                                                } else {
                                                                                                                                    backToTopBtn.classList.add('opacity-0',
                                                                                                                                        'invisible');
                                                                                                                                    backToTopBtn.classList.remove(
                                                                                                                                        'opacity-100', 'visible');
                                                                                                                                }
                                                                                                                            });

                                                                                                                            backToTopBtn.addEventListener('click', function() {
                                                                                                                                window.scrollTo({
                                                                                                                                    top: 0,
                                                                                                                                    behavior: 'smooth'
                                                                                                                                });
                                                                                                                            });
                                                                                                                        }

                                                                                                                        // Estilos completos para conteúdo HTML formatado do carousel (Quill Editor)
                                                                                                                        const carouselStyle = document.createElement('style');
                                                                                                                        carouselStyle.textContent = `
        .carousel-title h1, .carousel-title h2, .carousel-title h3 { color: white !important; font-weight: bold; }
        .carousel-description p { margin: 0.75rem 0; line-height: 1.6; }
    `;
                                                                                                                        document.head.appendChild(carouselStyle);

                                                                                                                        // Inicializar Preline Carousel
                                                                                                                        function initCarousel() {
                                                                                                                            if (typeof window.HSStaticMethods !== 'undefined') {
                                                                                                                                window.HSStaticMethods.autoInit();
                                                                                                                            }
                                                                                                                        }
                                                                                                                        setTimeout(initCarousel, 100);
                                                                                                                    });
                                                                                                                </script>
                                                                @endpush

                                                            @endsection
