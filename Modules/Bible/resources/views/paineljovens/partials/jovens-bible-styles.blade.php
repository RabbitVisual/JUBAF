{{--
    Design system Bíblia · Painel Jovens JUBAF (Unijovem)
    Teal + violeta alinhados ao layout (sidebar / hero).
--}}
@once
@push('styles')
<style>
    :root {
        --jubaf-teal: #0d9488;
        --jubaf-teal-dark: #0f766e;
        --jubaf-violet: #6d28d9;
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
        border: 1px solid rgb(231 229 228 / 0.9);
        background: rgb(255 255 255);
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }
    .dark .jovens-bible-card {
        border-color: rgb(41 37 36);
        background: rgb(28 25 23 / 0.8);
    }

    .jovens-bible-hero {
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        border: 1px solid rgb(231 229 228 / 0.8);
        background: linear-gradient(135deg, rgb(13 148 136 / 0.12) 0%, rgb(109 40 217 / 0.08) 45%, rgb(250 250 249) 100%);
    }
    .dark .jovens-bible-hero {
        border-color: rgb(41 37 36);
        background: linear-gradient(135deg, rgb(13 148 136 / 0.2) 0%, rgb(109 40 217 / 0.12) 40%, rgb(15 23 42) 100%);
    }

    mark.jovens-bible-search-mark,
    .jovens-bible-search mark {
        background-color: rgba(45, 212, 191, 0.35);
        color: inherit;
        padding: 0 2px;
        border-radius: 4px;
        font-weight: 700;
    }
    .dark mark.jovens-bible-search-mark,
    .dark .jovens-bible-search mark {
        background-color: rgba(45, 212, 191, 0.22);
    }

    .jovens-bible-quicklink {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 1rem;
        border: 1px solid rgb(231 229 228);
        background: rgb(255 255 255);
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: rgb(68 64 60);
        text-decoration: none;
        transition: border-color 0.15s, background-color 0.15s, color 0.15s;
    }
    .dark .jovens-bible-quicklink {
        border-color: rgb(68 64 60);
        background: rgb(28 25 23);
        color: rgb(231 229 228);
    }
    .jovens-bible-quicklink:hover {
        border-color: rgba(45, 212, 191, 0.55);
        background: rgba(240 253 250, 0.95);
        color: rgb(19 78 74);
    }
    .dark .jovens-bible-quicklink:hover {
        background: rgba(19 78 74, 0.35);
        color: rgb(204 251 241);
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
