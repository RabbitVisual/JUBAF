{{--
    Design system Bíblia · Painel Jovens JUBAF (Unijovem)
    Azul + cinza alinhados ao painel (hero / cards).
--}}
@once
@push('styles')
<style>
    :root {
        --jubaf-blue: #2563eb;
        --jubaf-blue-dark: #1d4ed8;
        --jubaf-paper-light: rgb(255 253 248);
        --jubaf-paper-dark: rgb(23 23 21);
    }

    .jovens-bible-paper {
        background: linear-gradient(180deg, var(--jubaf-paper-light) 0%, rgb(250 248 242) 100%);
    }
    .dark .jovens-bible-paper {
        background: linear-gradient(180deg, var(--jubaf-paper-dark) 0%, rgb(15 15 14) 100%);
    }

    .jovens-bible-serif {
        font-family: Georgia, "Iowan Old Style", "Apple Garamond", "Times New Roman", serif;
    }

    .jovens-bible-card {
        border-radius: 1.5rem;
        border: 1px solid rgb(229 231 235 / 0.95);
        background: rgb(255 255 255);
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }
    .dark .jovens-bible-card {
        border-color: rgb(55 65 81);
        background: rgb(17 24 39 / 0.85);
    }

    .jovens-bible-hero {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        border: 1px solid rgb(229 231 235 / 0.9);
        background: linear-gradient(135deg, rgb(29 78 216 / 0.14) 0%, rgb(30 64 175 / 0.1) 40%, rgb(249 250 251) 100%);
    }
    .dark .jovens-bible-hero {
        border-color: rgb(55 65 81);
        background: linear-gradient(135deg, rgb(37 99 235 / 0.22) 0%, rgb(30 58 138 / 0.18) 45%, rgb(17 24 39) 100%);
    }

    mark.jovens-bible-search-mark,
    .jovens-bible-search mark {
        background-color: rgba(59, 130, 246, 0.35);
        color: inherit;
        padding: 0 2px;
        border-radius: 4px;
        font-weight: 700;
    }
    .dark mark.jovens-bible-search-mark,
    .dark .jovens-bible-search mark {
        background-color: rgba(59, 130, 246, 0.25);
    }

    .jovens-bible-quicklink {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 1rem;
        border: 1px solid rgb(229 231 235);
        background: rgb(255 255 255);
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: rgb(55 65 81);
        text-decoration: none;
        transition: border-color 0.15s, background-color 0.15s, color 0.15s;
    }
    .dark .jovens-bible-quicklink {
        border-color: rgb(75 85 99);
        background: rgb(31 41 55);
        color: rgb(229 231 235);
    }
    .jovens-bible-quicklink:hover {
        border-color: rgba(37, 99, 235, 0.45);
        background: rgba(239 246 255, 0.95);
        color: rgb(30 64 175);
    }
    .dark .jovens-bible-quicklink:hover {
        background: rgba(30 58 138, 0.35);
        color: rgb(191 219 254);
    }

    /* Campo de busca: sem botões nativos extra (alguns browsers/WebViews injectam controlo no canto) */
    .jovens-bible-search-field .jovens-bible-search-input {
        -webkit-appearance: none;
        appearance: none;
    }
    .jovens-bible-search-field .jovens-bible-search-input::-webkit-search-decoration,
    .jovens-bible-search-field .jovens-bible-search-input::-webkit-search-cancel-button,
    .jovens-bible-search-field .jovens-bible-search-input::-webkit-search-results-button {
        display: none;
        -webkit-appearance: none;
    }
</style>
@endpush
@endonce
