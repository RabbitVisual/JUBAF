@props(['aviso'])

@php
    $tipo = $aviso->tipo ?? 'info';
    $estilo = $aviso->estilo ?? 'banner';
    $dismissivel = $aviso->dismissivel ?? false;
    $avisoId = $aviso->id;

    // Mapear icones por tipo
    $iconMap = [
        'info' => 'circle-info',
        'success' => 'circle-check',
        'warning' => 'triangle-exclamation',
        'danger' => 'circle-exclamation',
        'promocao' => 'tags',
        'novidade' => 'sparkles',
        'anuncio' => 'bullhorn',
    ];

    $iconName = $iconMap[$tipo] ?? 'circle-info';

    // Mapear tipo para classes Tailwind completas e estáticas
    $classesMap = [
        'info' => [
            'gradient' => 'from-indigo-600 to-indigo-700 dark:from-indigo-800 dark:to-indigo-900',
            'text-light' => 'text-indigo-100 dark:text-indigo-200',
            'bg-light' => 'bg-indigo-50 dark:bg-indigo-900/20',
            'border-color' => 'border-indigo-600 dark:border-indigo-400',
            'icon-bg' => 'bg-indigo-100 dark:bg-indigo-900/40',
            'icon-text' => 'text-indigo-600 dark:text-indigo-400',
            'title-text' => 'text-indigo-900 dark:text-indigo-100',
            'desc-text' => 'text-indigo-800 dark:text-indigo-200',
            'button-text' => 'text-indigo-600 dark:text-indigo-400',
            'button-hover' => 'hover:text-indigo-800 dark:hover:text-indigo-200 hover:bg-indigo-50',
        ],
        'success' => [
            'gradient' => 'from-emerald-600 to-emerald-700 dark:from-emerald-800 dark:to-emerald-900',
            'text-light' => 'text-emerald-100 dark:text-emerald-200',
            'bg-light' => 'bg-emerald-50 dark:bg-emerald-900/20',
            'border-color' => 'border-emerald-600 dark:border-emerald-400',
            'icon-bg' => 'bg-emerald-100 dark:bg-emerald-900/40',
            'icon-text' => 'text-emerald-600 dark:text-emerald-400',
            'title-text' => 'text-emerald-900 dark:text-emerald-100',
            'desc-text' => 'text-emerald-800 dark:text-emerald-200',
            'button-text' => 'text-emerald-600 dark:text-emerald-400',
            'button-hover' => 'hover:text-emerald-800 dark:hover:text-emerald-200 hover:bg-emerald-50',
        ],
        'warning' => [
            'gradient' => 'from-amber-600 to-amber-700 dark:from-amber-800 dark:to-amber-900',
            'text-light' => 'text-amber-100 dark:text-amber-200',
            'bg-light' => 'bg-amber-50 dark:bg-amber-900/20',
            'border-color' => 'border-amber-600 dark:border-amber-400',
            'icon-bg' => 'bg-amber-100 dark:bg-amber-900/40',
            'icon-text' => 'text-amber-600 dark:text-amber-400',
            'title-text' => 'text-amber-900 dark:text-amber-100',
            'desc-text' => 'text-amber-800 dark:text-amber-200',
            'button-text' => 'text-amber-600 dark:text-amber-400',
            'button-hover' => 'hover:text-amber-800 dark:hover:text-amber-200 hover:bg-amber-50',
        ],
        'danger' => [
            'gradient' => 'from-red-600 to-red-700 dark:from-red-800 dark:to-red-900',
            'text-light' => 'text-red-100 dark:text-red-200',
            'bg-light' => 'bg-red-50 dark:bg-red-900/20',
            'border-color' => 'border-red-600 dark:border-red-400',
            'icon-bg' => 'bg-red-100 dark:bg-red-900/40',
            'icon-text' => 'text-red-600 dark:text-red-400',
            'title-text' => 'text-red-900 dark:text-red-100',
            'desc-text' => 'text-red-800 dark:text-red-200',
            'button-text' => 'text-red-600 dark:text-red-400',
            'button-hover' => 'hover:text-red-800 dark:hover:text-red-200 hover:bg-red-50',
        ],
        'promocao' => [
            'gradient' => 'from-purple-600 to-purple-700 dark:from-purple-800 dark:to-purple-900',
            'text-light' => 'text-purple-100 dark:text-purple-200',
            'bg-light' => 'bg-purple-50 dark:bg-purple-900/20',
            'border-color' => 'border-purple-600 dark:border-purple-400',
            'icon-bg' => 'bg-purple-100 dark:bg-purple-900/40',
            'icon-text' => 'text-purple-600 dark:text-purple-400',
            'title-text' => 'text-purple-900 dark:text-purple-100',
            'desc-text' => 'text-purple-800 dark:text-purple-200',
            'button-text' => 'text-purple-600 dark:text-purple-400',
            'button-hover' => 'hover:text-purple-800 dark:hover:text-purple-200 hover:bg-purple-50',
        ],
        'novidade' => [
            'gradient' => 'from-blue-600 to-blue-700 dark:from-blue-800 dark:to-blue-900',
            'text-light' => 'text-blue-100 dark:text-blue-200',
            'bg-light' => 'bg-blue-50 dark:bg-blue-900/20',
            'border-color' => 'border-blue-600 dark:border-blue-400',
            'icon-bg' => 'bg-blue-100 dark:bg-blue-900/40',
            'icon-text' => 'text-blue-600 dark:text-blue-400',
            'title-text' => 'text-blue-900 dark:text-blue-100',
            'desc-text' => 'text-blue-800 dark:text-blue-200',
            'button-text' => 'text-blue-600 dark:text-blue-400',
            'button-hover' => 'hover:text-blue-800 dark:hover:text-blue-200 hover:bg-blue-50',
        ],
        'anuncio' => [
            'gradient' => 'from-gray-600 to-gray-700 dark:from-gray-800 dark:to-gray-900',
            'text-light' => 'text-gray-100 dark:text-gray-200',
            'bg-light' => 'bg-gray-50 dark:bg-gray-900/20',
            'border-color' => 'border-gray-600 dark:border-gray-400',
            'icon-bg' => 'bg-gray-100 dark:bg-gray-900/40',
            'icon-text' => 'text-gray-600 dark:text-gray-400',
            'title-text' => 'text-gray-900 dark:text-gray-100',
            'desc-text' => 'text-gray-800 dark:text-gray-200',
            'button-text' => 'text-gray-600 dark:text-gray-400',
            'button-hover' => 'hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50',
        ],
    ];

    $classes = $classesMap[$tipo] ?? $classesMap['info'];
@endphp

<div class="aviso-component aviso-{{ $avisoId }} aviso-{{ $estilo }} aviso-{{ $tipo }}"
     data-aviso-id="{{ $avisoId }}"
     data-posicao="{{ $aviso->posicao }}"
     data-dismissivel="{{ $dismissivel ? 'true' : 'false' }}">

    @if($estilo == 'banner')
        <!-- Banner Style -->
        <div class="bg-gradient-to-r {{ $classes['gradient'] }} rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 md:px-8 md:py-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <x-icon :name="$iconName" style="duotone" class="w-6 h-6 text-white/80" />
                            <h3 class="text-lg md:text-xl font-bold text-white">{{ $aviso->titulo }}</h3>
                        </div>
                        @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'banner'])
                        @if($aviso->descricao)
                            <p class="{{ $classes['text-light'] }} text-sm md:text-base mb-2">{{ $aviso->descricao }}</p>
                        @endif
                        @if($aviso->conteudo)
                            <div class="mt-3 {{ $classes['text-light'] }} prose prose-invert prose-sm max-w-none">
                                {!! $aviso->conteudo !!}
                            </div>
                        @endif
                        @if($aviso->botao_exibir && $aviso->url_acao)
                            <div class="mt-4">
                                <a href="{{ $aviso->url_acao }}"
                                   class="inline-flex items-center px-4 py-2 bg-white {{ $classes['button-text'] }} rounded-lg font-medium {{ $classes['button-hover'] }} transition-colors"
                                   onclick="window.registrarClique && window.registrarClique({{ $avisoId }})">
                                    {{ $aviso->texto_botao ?? 'Saiba Mais' }}
                                    <x-icon name="arrow-right" class="w-4 h-4 ml-2" />
                                </a>
                            </div>
                        @endif
                    </div>
                    @if($aviso->imagem)
                        <div class="hidden md:block flex-shrink-0">
                            <img src="{{ asset('storage/' . $aviso->imagem) }}"
                                 alt="{{ $aviso->titulo }}"
                                 class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-lg">
                        </div>
                    @endif
                    @if($dismissivel)
                        <button type="button"
                                onclick="window.fecharAviso && window.fecharAviso({{ $avisoId }})"
                                class="flex-shrink-0 text-white hover:opacity-75 transition-colors self-start">
                            <x-icon name="x-mark" class="w-6 h-6" />
                        </button>
                    @endif
                </div>
            </div>
        </div>

    @elseif($estilo == 'announcement')
        <!-- Announcement Style -->
        <div class="{{ $classes['bg-light'] }} border-l-4 {{ $classes['border-color'] }} rounded-lg p-4">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 {{ $classes['icon-bg'] }} rounded-full flex items-center justify-center">
                        <x-icon :name="$iconName" style="duotone" class="w-6 h-6 {{ $classes['icon-text'] }}" />
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-semibold {{ $classes['title-text'] }} mb-1">{{ $aviso->titulo }}</h3>
                    @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
                    @if($aviso->descricao)
                        <p class="text-sm {{ $classes['desc-text'] }}">{{ $aviso->descricao }}</p>
                    @endif
                    @if($aviso->conteudo)
                        <div class="mt-2 text-sm {{ $classes['desc-text'] }} prose prose-sm max-w-none">
                            {!! $aviso->conteudo !!}
                        </div>
                    @endif
                    @if($aviso->botao_exibir && $aviso->url_acao)
                        <div class="mt-3">
                            <a href="{{ $aviso->url_acao }}"
                               class="inline-flex items-center text-sm font-medium {{ $classes['button-text'] }} {{ $classes['button-hover'] }} transition-colors"
                               onclick="window.registrarClique && window.registrarClique({{ $avisoId }})">
                                {{ $aviso->texto_botao ?? 'Saiba Mais' }}
                                <x-icon name="arrow-right" class="w-4 h-4 ml-1" />
                            </a>
                        </div>
                    @endif
                </div>
                @if($dismissivel)
                    <button type="button"
                            onclick="window.fecharAviso && window.fecharAviso({{ $avisoId }})"
                            class="flex-shrink-0 {{ $classes['button-text'] }} {{ $classes['button-hover'] }} transition-colors">
                        <x-icon name="x-mark" class="w-5 h-5" />
                    </button>
                @endif
            </div>
        </div>

    @elseif($estilo == 'cta')
        <!-- CTA Style -->
        <div class="bg-gradient-to-br {{ $classes['gradient'] }} rounded-2xl shadow-xl p-8 text-center text-white relative overflow-hidden">
            @if($aviso->imagem)
                <div class="absolute inset-0 opacity-10">
                    <img src="{{ asset('storage/' . $aviso->imagem) }}"
                         alt="{{ $aviso->titulo }}"
                         class="w-full h-full object-cover">
                </div>
            @endif
            <div class="relative z-10 flex flex-col items-center">
                <div class="mb-4">
                     <x-icon :name="$iconName" style="duotone" class="w-12 h-12 text-white/90" />
                </div>
                <h3 class="text-2xl md:text-3xl font-bold mb-3">{{ $aviso->titulo }}</h3>
                @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'banner'])
                @if($aviso->descricao)
                    <p class="text-lg mb-6 text-white/90 max-w-2xl">{{ $aviso->descricao }}</p>
                @endif
                @if($aviso->conteudo)
                    <div class="mb-6 prose prose-invert prose-lg max-w-none">
                        {!! $aviso->conteudo !!}
                    </div>
                @endif
                @if($aviso->botao_exibir && $aviso->url_acao)
                    <a href="{{ $aviso->url_acao }}"
                       class="inline-flex items-center px-6 py-3 bg-white {{ $classes['button-text'] }} rounded-lg font-semibold {{ $classes['button-hover'] }} transition-colors shadow-lg"
                       onclick="window.registrarClique && window.registrarClique({{ $avisoId }})">
                        {{ $aviso->texto_botao ?? 'Saiba Mais' }}
                        <x-icon name="arrow-right" class="w-5 h-5 ml-2" />
                    </a>
                @endif
                @if($dismissivel)
                    <button type="button"
                            onclick="window.fecharAviso && window.fecharAviso({{ $avisoId }})"
                            class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors">
                        <x-icon name="x-mark" class="w-5 h-5" />
                    </button>
                @endif
            </div>
        </div>

    @elseif($estilo == 'toast' || $aviso->posicao == 'flutuante')
        <!-- Toast Style (ou Flutuante) - Canto Superior Esquerdo -->
        <div class="@if(!request()->is('admin/*')) fixed top-4 left-4 z-[9999] @else relative @endif max-w-sm w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-xl p-4 animate-slide-up aviso-flutuante" style="animation: slideUp 0.3s ease-out;">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 {{ $classes['icon-bg'] }} rounded-full flex items-center justify-center">
                        <x-icon :name="$iconName" style="duotone" class="w-5 h-5 {{ $classes['icon-text'] }}" />
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ $aviso->titulo }}</h4>
                    @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'toast'])
                    @if($aviso->descricao)
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $aviso->descricao }}</p>
                    @endif
                    @if($aviso->botao_exibir && $aviso->url_acao)
                        <a href="{{ $aviso->url_acao }}"
                           class="mt-2 inline-flex items-center text-xs font-medium {{ $classes['button-text'] }} {{ $classes['button-hover'] }} transition-colors"
                           onclick="window.registrarClique && window.registrarClique({{ $avisoId }})">
                            {{ $aviso->texto_botao ?? 'Ver' }}
                            <x-icon name="arrow-right" class="w-3 h-3 ml-1" />
                        </a>
                    @endif
                </div>
                @if($dismissivel)
                    <button type="button"
                            onclick="window.fecharAviso && window.fecharAviso({{ $avisoId }})"
                            class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <x-icon name="x-mark" class="w-4 h-4" />
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-slide-up {
        animation: slideUp 0.3s ease-out;
    }

    /* Responsivo: em telas pequenas, ajustar posição */
    @media (max-width: 640px) {
        .aviso-flutuante {
            top: 1rem;
            left: 1rem;
            right: 1rem;
            max-width: calc(100% - 2rem);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Definir funções globais apenas uma vez
    if (typeof window.avisosFunctionsDefined === 'undefined') {
        window.avisosFunctionsDefined = true;

        // Função global para registrar clique
        window.registrarClique = function(avisoId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                console.warn('CSRF token não encontrado');
                return;
            }

            fetch(`/api/avisos/${avisoId}/clique`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            }).catch(() => {});
        };

        // Função global para fechar aviso
        window.fecharAviso = function(avisoId) {
            const avisoComponent = document.querySelector(`.aviso-${avisoId}`);
            if (avisoComponent) {
                avisoComponent.style.transition = 'opacity 0.3s ease-out';
                avisoComponent.style.opacity = '0';
                setTimeout(() => {
                    avisoComponent.remove();
                }, 300);
                localStorage.setItem(`aviso-${avisoId}-fechado`, 'true');
            }
        };
    }

    // Registrar visualização quando o componente é exibido (apenas em páginas públicas, não no admin)
    document.addEventListener('DOMContentLoaded', function() {
        const avisoComponent = document.querySelector('.aviso-{{ $avisoId }}');
        // Não registrar visualização se estiver na página admin
        if (avisoComponent && !window.location.pathname.includes('/admin/') && !localStorage.getItem('aviso-{{ $avisoId }}-fechado')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                fetch('{{ route("avisos.api.visualizar", $avisoId) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                }).catch(() => {});
            }
        }
    });
</script>
@endpush
