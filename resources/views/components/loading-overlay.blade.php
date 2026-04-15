@php
    /** Logótipo para fundos escuros (ficheiro “claro” / contraste sobre overlay). */
    $loadingLogoUrl = \App\Support\SiteBranding::logoLightUrl();
@endphp

{{--
    Overlay global de carregamento — JUBAF / Vertex
    - z-[10030]: abaixo dos modais (dialogs.js z-[10050])
    - Sem texto “Carregando página…”; estado comunicado a leitores de ecrã (sr-only)
    - Logo com URL de branding (transparente sobre fundo escuro)
--}}
<div id="global-loading-overlay"
    x-data="@js([
        'show' => false,
        'detailMessage' => '',
        'defaultLoading' => __('A carregar…'),
    ])"
    x-show="show"
    x-bind:aria-busy="show ? 'true' : 'false'"
    role="status"
    x-on:vertex-loading-show.window="show = true; detailMessage = ($event.detail && $event.detail.message) ? String($event.detail.message) : ''"
    x-on:vertex-loading-hide.window="show = false; detailMessage = ''"
    x-on:jub-loading-show.window="show = true; detailMessage = ($event.detail && $event.detail.message) ? String($event.detail.message) : ''"
    x-on:jub-loading-hide.window="show = false; detailMessage = ''"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[10030] flex items-center justify-center bg-slate-950/70 backdrop-blur-md supports-[backdrop-filter]:bg-slate-950/50"
    style="display: none;">

    <div class="relative flex max-w-[17rem] flex-col items-center justify-center rounded-3xl border border-white/10 bg-white/5 px-8 py-10 shadow-2xl shadow-black/20 ring-1 ring-white/5 backdrop-blur-2xl sm:max-w-xs sm:px-10 sm:py-12">

        <div class="pointer-events-none absolute -top-24 left-1/2 h-48 w-48 -translate-x-1/2 rounded-full bg-violet-500/15 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-16 right-0 h-40 w-40 rounded-full bg-teal-500/10 blur-3xl" aria-hidden="true"></div>

        {{-- Marca: fundo transparente; sem caixa escura por trás --}}
        <div class="relative mb-7 flex min-h-[4rem] items-center justify-center bg-transparent px-2">
            <img src="{{ $loadingLogoUrl }}"
                alt="{{ \App\Support\SiteBranding::siteName() }}"
                width="200"
                height="64"
                decoding="async"
                fetchpriority="low"
                class="jubaf-loading-logo h-14 w-auto max-w-[min(100%,220px)] object-contain object-center [image-rendering:auto]" />
        </div>

        {{-- Indicador: anel com animate-spin (Tailwind v4) --}}
        <div class="jubaf-loading-spinner size-12 shrink-0 rounded-full border-2 border-white/15 border-t-teal-400 border-r-violet-500/90 border-b-transparent border-l-transparent animate-spin"
            aria-hidden="true"></div>

        {{-- Mensagem opcional (ex.: evento customizado com detail.message) — nunca “Carregando página…” por defeito --}}
        <p x-show="detailMessage.length > 0"
            x-text="detailMessage"
            x-transition.opacity.duration.200ms
            class="mt-6 max-w-[14rem] text-center text-sm font-medium leading-snug text-white/90"
            x-cloak></p>

        {{-- Acessibilidade: leitor de ecrã --}}
        <span class="sr-only" x-show="show" x-text="detailMessage.length ? detailMessage : defaultLoading"></span>

        <div class="mt-7 h-1 w-full max-w-[10rem] overflow-hidden rounded-full bg-white/10" aria-hidden="true">
            <div class="jubaf-loading-bar-fill h-full w-1/3 rounded-full bg-linear-to-r from-teal-400 via-violet-400 to-teal-400"></div>
        </div>
    </div>
</div>

<style>
    /*
     * Animações locais: garantem efeito mesmo se utilities motion-* não gerarem no build.
     * prefers-reduced-motion: feedback suave sem desligar totalmente.
     */
    #global-loading-overlay .jubaf-loading-bar-fill {
        animation: jubaf-progress-slide 2s ease-in-out infinite;
        will-change: transform;
    }

    #global-loading-overlay .jubaf-loading-logo {
        animation: jubaf-logo-breathe 2.4s ease-in-out infinite;
    }

    @keyframes jubaf-progress-slide {
        0% { transform: translateX(-150%) scaleX(0.45); }
        50% { transform: translateX(80%) scaleX(1); }
        100% { transform: translateX(320%) scaleX(0.45); }
    }

    @keyframes jubaf-logo-breathe {
        0%, 100% { opacity: 1; filter: drop-shadow(0 0 12px rgba(45, 212, 191, 0.25)); }
        50% { opacity: 0.94; filter: drop-shadow(0 0 20px rgba(139, 92, 246, 0.35)); }
    }

    @media (prefers-reduced-motion: reduce) {
        #global-loading-overlay .jubaf-loading-bar-fill {
            animation: jubaf-progress-soft 2s ease-in-out infinite;
        }
        #global-loading-overlay .jubaf-loading-logo {
            animation: jubaf-logo-soft 2.5s ease-in-out infinite;
        }
        #global-loading-overlay .jubaf-loading-spinner {
            animation: spin-soft 1.4s linear infinite;
        }
    }

    @keyframes jubaf-progress-soft {
        0%, 100% { opacity: 0.35; }
        50% { opacity: 0.85; }
    }

    @keyframes jubaf-logo-soft {
        0%, 100% { opacity: 0.92; }
        50% { opacity: 1; }
    }

    @keyframes spin-soft {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>

<script>
    (function () {
        const showOverlay = (msg) => {
            const detail = {};
            if (msg !== undefined && msg !== null && String(msg).trim() !== '') {
                detail.message = String(msg);
            }
            window.dispatchEvent(new CustomEvent('vertex-loading-show', { detail }));
            window.dispatchEvent(new CustomEvent('jub-loading-show', { detail }));
        };

        const hideOverlay = () => {
            window.dispatchEvent(new CustomEvent('vertex-loading-hide'));
            window.dispatchEvent(new CustomEvent('jub-loading-hide'));
        };

        document.addEventListener('livewire:init', () => {
            if (typeof Livewire === 'undefined') return;
            Livewire.hook('commit', ({ succeed, fail, respond }) => {
                showOverlay();
                succeed(() => hideOverlay());
                fail(() => hideOverlay());
                respond(() => hideOverlay());
            });

            document.addEventListener('livewire:navigate', () => showOverlay());
            document.addEventListener('livewire:navigated', hideOverlay);
        });

        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.method.toLowerCase() === 'get' || form.hasAttribute('data-no-loading')) return;
            showOverlay();
        });

        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (!link) return;

            const isInternal = link.href && link.href.startsWith(window.location.origin);
            const isNoLoad = link.hasAttribute('data-no-loading') || link.getAttribute('target') === '_blank';
            const isSpecial = e.ctrlKey || e.shiftKey || e.metaKey || link.href.includes('#') || link.href.startsWith('javascript:');

            if (isInternal && !isNoLoad && !isSpecial) {
                if (!link.classList.contains('page-link') && !link.hasAttribute('role')) {
                    showOverlay();
                }
            }
        });

        window.addEventListener('pageshow', () => hideOverlay());
        window.addEventListener('load', hideOverlay);

        window.VertexLoading = { show: showOverlay, hide: hideOverlay };
        window.JubLoading = window.VertexLoading;
    })();
</script>
