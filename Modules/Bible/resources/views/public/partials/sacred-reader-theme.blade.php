{{--
  Tema Bíblia pública: pergaminho / livro sagrado
  Uso: envolva a página com class="bible-sacred-root" (e opcionalmente bible-public-container).
  Presets: data-bible-palette="classic" | "sepia" | "contrast" (persistir com localStorage bible_public_palette).
  Modo leitura (foco): no capítulo, Alpine adiciona class "bible-reading-mode" ao body — esconde navbar/rodapé do site.
  Interlinear: mesma raiz com .interlinear-root; variáveis --parchment / --ink são aliases de --sacred-*.
--}}
<style>
    /* --- Variáveis no bloco de leitura (também no overlay fullscreen) --- */
    .bible-sacred-root {
        --sacred-parchment: #f4ecd8;
        --sacred-parchment-mid: #ebe2cc;
        --sacred-parchment-deep: #e0d4b8;
        --sacred-edge: #a67c52;
        --sacred-edge-dark: #6b4423;
        --sacred-ink: #1f170f;
        --sacred-ink-muted: #4a3f32;
        --sacred-accent: #6b3410;
        --sacred-gold: #8b6914;
        --sacred-paper-shadow:
            0 2px 0 rgba(255, 255, 255, 0.45) inset,
            0 12px 40px rgba(44, 36, 22, 0.14),
            0 1px 0 rgba(107, 68, 35, 0.12);
        /* Sem CDN: stack de serifas do sistema (ver resources/css/app.css) */
        font-family: Georgia, 'Noto Serif', 'Times New Roman', ui-serif, serif;
        background-color: var(--sacred-parchment);
        background-image:
            radial-gradient(ellipse 120% 80% at 50% 0%, rgba(255, 252, 245, 0.9) 0%, transparent 55%),
            linear-gradient(180deg, var(--sacred-parchment) 0%, var(--sacred-parchment-mid) 45%, #d9ccb2 100%),
            repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(107, 52, 16, 0.04) 2px,
                rgba(107, 52, 16, 0.04) 3px
            ),
            repeating-linear-gradient(
                90deg,
                transparent,
                transparent 28px,
                rgba(166, 124, 82, 0.06) 28px,
                rgba(166, 124, 82, 0.06) 29px
            );
        color: var(--sacred-ink);
        min-height: 100%;
    }

    /* Preset: sépia mais profundo */
    .bible-sacred-root[data-bible-palette='sepia'] {
        --sacred-parchment: #e8dcc4;
        --sacred-parchment-mid: #ddcfba;
        --sacred-parchment-deep: #d0c0a8;
        --sacred-edge: #8b6342;
        --sacred-edge-dark: #5c3d24;
        --sacred-ink: #1a120a;
        --sacred-ink-muted: #4d4034;
        --sacred-accent: #5c2e0c;
        --sacred-gold: #7a5a0f;
    }

    /* Preset: alto contraste (leitura) */
    .bible-sacred-root[data-bible-palette='contrast'] {
        --sacred-parchment: #faf6ec;
        --sacred-parchment-mid: #f2ead8;
        --sacred-parchment-deep: #e8dcc8;
        --sacred-edge: #3d2918;
        --sacred-edge-dark: #1f140c;
        --sacred-ink: #0a0705;
        --sacred-ink-muted: #2a2218;
        --sacred-accent: #4a2008;
        --sacred-gold: #6b4a00;
    }

    .dark .bible-sacred-root {
        --sacred-parchment: #1c1814;
        --sacred-parchment-mid: #161210;
        --sacred-parchment-deep: #12100e;
        --sacred-edge: #6b5a45;
        --sacred-edge-dark: #3d3428;
        --sacred-ink: #f3ebe0;
        --sacred-ink-muted: #b5a896;
        --sacred-accent: #e8b876;
        --sacred-gold: #d4a84b;
        --sacred-paper-shadow:
            0 0 0 1px rgba(212, 168, 75, 0.12) inset,
            0 16px 48px rgba(0, 0, 0, 0.55);
        background-image:
            radial-gradient(ellipse 100% 60% at 50% 0%, rgba(60, 48, 36, 0.35) 0%, transparent 50%),
            linear-gradient(180deg, var(--sacred-parchment) 0%, #0e0c0a 100%),
            repeating-linear-gradient(
                90deg,
                transparent,
                transparent 24px,
                rgba(212, 168, 75, 0.05) 24px,
                rgba(212, 168, 75, 0.05) 25px
            );
    }

    .dark .bible-sacred-root[data-bible-palette='sepia'] {
        --sacred-parchment: #181410;
        --sacred-parchment-mid: #14110e;
        --sacred-parchment-deep: #0f0d0b;
        --sacred-edge: #7a6a52;
        --sacred-accent: #e0a85a;
        --sacred-gold: #c9983e;
    }

    .dark .bible-sacred-root[data-bible-palette='contrast'] {
        --sacred-parchment: #0f0e0c;
        --sacred-parchment-mid: #0c0b09;
        --sacred-parchment-deep: #080706;
        --sacred-ink: #fffaf2;
        --sacred-ink-muted: #d4c4b0;
        --sacred-edge: #a89070;
        --sacred-accent: #ffc266;
        --sacred-gold: #ffd080;
    }

    /* Aliases interlinear / markup legado → tokens sagrados */
    .bible-sacred-root.interlinear-root {
        --parchment: var(--sacred-parchment);
        --parchment-edge: var(--sacred-edge);
        --parchment-dark: var(--sacred-parchment-deep);
        --ink: var(--sacred-ink);
        --ink-muted: var(--sacred-ink-muted);
        --accent: var(--sacred-accent);
        --gold: var(--sacred-gold);
    }

    .bible-sacred-paper,
    .interlinear-paper {
        background: linear-gradient(
            165deg,
            color-mix(in srgb, var(--sacred-parchment) 88%, #fff) 0%,
            var(--sacred-parchment-mid) 48%,
            var(--sacred-parchment-deep) 100%
        );
        border: 1px solid color-mix(in srgb, var(--sacred-edge) 55%, transparent);
        border-radius: 1.25rem;
        box-shadow: var(--sacred-paper-shadow);
    }
    .dark .bible-sacred-paper,
    .dark .interlinear-paper {
        background: linear-gradient(165deg, #252018 0%, var(--sacred-parchment) 100%);
        border-color: color-mix(in srgb, var(--sacred-edge) 45%, transparent);
    }

    .interlinear-scroll-edge {
        background: repeating-linear-gradient(
            90deg,
            transparent,
            transparent 2px,
            color-mix(in srgb, var(--sacred-accent) 12%, transparent) 2px,
            color-mix(in srgb, var(--sacred-accent) 12%, transparent) 4px
        );
    }

    .font-scripture {
        font-family: Georgia, 'Noto Serif', 'Times New Roman', ui-serif, serif;
    }

    .font-morph {
        font-family: ui-monospace, 'Cascadia Code', 'Segoe UI Mono', monospace;
    }

    .interlinear-original {
        font-feature-settings: 'kern' 1;
        line-height: 1.35;
    }

    /* Moldura da “página” em listagens (livros / capítulos) */
    .bible-sacred-page-frame {
        border: 2px solid color-mix(in srgb, var(--sacred-edge) 40%, transparent);
        border-radius: 1.5rem;
        background: linear-gradient(180deg, color-mix(in srgb, var(--sacred-parchment) 70%, transparent) 0%, transparent 100%);
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.35),
            0 8px 32px rgba(44, 36, 22, 0.08);
    }
    .dark .bible-sacred-page-frame {
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.04),
            0 8px 32px rgba(0, 0, 0, 0.35);
    }

    .bible-sacred-header {
        background: color-mix(in srgb, var(--sacred-parchment) 88%, #fff);
        border-bottom: 1px solid color-mix(in srgb, var(--sacred-edge) 45%, transparent);
        backdrop-filter: blur(14px);
    }
    .dark .bible-sacred-header {
        background: color-mix(in srgb, var(--sacred-parchment) 92%, #000);
        border-bottom-color: color-mix(in srgb, var(--sacred-edge) 35%, transparent);
    }

    .bible-sacred-reading-column {
        max-width: 42rem;
        line-height: 1.82;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
    }
    @media (min-width: 640px) {
        .bible-sacred-reading-column { line-height: 1.88; }
    }

    .bible-sacred-verse-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        height: 2rem;
        font-family: ui-sans-serif, system-ui, sans-serif;
        font-size: 0.6875rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #fff;
        background: linear-gradient(145deg, var(--sacred-accent), color-mix(in srgb, var(--sacred-gold) 65%, var(--sacred-accent)));
        border-radius: 9999px;
        box-shadow:
            0 2px 8px color-mix(in srgb, var(--sacred-accent) 40%, transparent),
            inset 0 1px 0 rgba(255, 255, 255, 0.22);
        flex-shrink: 0;
    }

    .bible-sacred-chapter-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.35rem;
        height: 2.35rem;
        font-family: ui-sans-serif, system-ui, sans-serif;
        font-size: 0.875rem;
        font-weight: 800;
        color: #fff;
        background: linear-gradient(145deg, var(--sacred-accent), color-mix(in srgb, var(--sacred-gold) 60%, var(--sacred-accent)));
        border-radius: 9999px;
        box-shadow: 0 2px 10px color-mix(in srgb, var(--sacred-accent) 35%, transparent);
    }

    .bible-sacred-verse-block {
        scroll-margin-top: 6.5rem;
    }

    .bible-sacred-spine {
        width: 0.35rem;
        border-radius: 9999px;
        background: linear-gradient(180deg, var(--sacred-gold), var(--sacred-accent));
        box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.15);
    }

    /* Alertas dentro do pergaminho (coerência com tokens) */
    .bible-sacred-alert-error {
        border-radius: 0.75rem;
        border: 1px solid color-mix(in srgb, #b91c1c 45%, var(--sacred-edge));
        background: color-mix(in srgb, #fef2f2 88%, var(--sacred-parchment));
        color: color-mix(in srgb, #7f1d1d 90%, var(--sacred-ink));
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }
    .dark .bible-sacred-alert-error {
        background: color-mix(in srgb, #450a0a 55%, var(--sacred-parchment));
        border-color: color-mix(in srgb, #991b1b 50%, var(--sacred-edge));
        color: #fecaca;
    }

    .bible-sacred-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: color-mix(in srgb, var(--sacred-edge) 55%, transparent) transparent;
    }
    .bible-sacred-scrollbar::-webkit-scrollbar {
        height: 6px;
        width: 6px;
    }
    .bible-sacred-scrollbar::-webkit-scrollbar-thumb {
        background: color-mix(in srgb, var(--sacred-edge) 50%, transparent);
        border-radius: 9999px;
    }

    /* --- Modo leitura / foco: esconde menu e rodapé do site (homepage) --- */
    body.bible-reading-mode #site-primary-nav,
    body.bible-reading-mode #site-primary-footer {
        display: none !important;
    }
    body.bible-reading-mode {
        overflow: hidden !important;
    }

    body.interlinear-focus-mode #site-primary-nav,
    body.interlinear-focus-mode #site-primary-footer {
        display: none !important;
    }
    body.interlinear-focus-mode #conteudo {
        position: relative;
        z-index: 50;
    }
    body.interlinear-focus-mode {
        overflow: hidden;
    }

    /* Overlay de leitura acima de tudo no site (navbar z-50) */
    #bible-reading-mode-container {
        z-index: 10000 !important;
        isolation: isolate;
    }

    /* Folha única legível dentro do modo leitura + tela cheia do navegador */
    .bible-reading-sheet {
        max-width: 40rem;
        margin-left: auto;
        margin-right: auto;
        padding: 1.5rem 1.25rem 2.5rem;
        background: linear-gradient(
            180deg,
            color-mix(in srgb, var(--sacred-parchment) 92%, #fff) 0%,
            var(--sacred-parchment-mid) 100%
        );
        border: 2px solid color-mix(in srgb, var(--sacred-edge) 50%, transparent);
        border-radius: 1rem;
        box-shadow:
            inset 0 0 80px rgba(107, 52, 16, 0.06),
            0 4px 24px rgba(44, 36, 22, 0.12);
        color: var(--sacred-ink);
        border-left-width: 6px;
        border-left-color: color-mix(in srgb, var(--sacred-edge-dark) 70%, var(--sacred-gold));
    }
    .dark .bible-reading-sheet {
        background: linear-gradient(180deg, #252018 0%, var(--sacred-parchment) 100%);
        border-color: color-mix(in srgb, var(--sacred-edge) 40%, transparent);
        border-left-color: var(--sacred-gold);
        box-shadow: inset 0 0 60px rgba(0, 0, 0, 0.35);
    }

    .bible-reading-sheet .bible-reading-verse-text {
        line-height: 1.78;
        font-weight: 450;
    }
    @media (min-width: 640px) {
        .bible-reading-sheet .bible-reading-verse-text {
            line-height: 1.85;
        }
    }

    #bible-reading-mode-container:fullscreen,
    #bible-reading-mode-container:-webkit-full-screen {
        background-color: var(--sacred-parchment) !important;
        color: var(--sacred-ink) !important;
    }
</style>
