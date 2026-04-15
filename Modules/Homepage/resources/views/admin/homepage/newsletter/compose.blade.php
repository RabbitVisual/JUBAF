@extends(request()->routeIs('diretoria.*') ? 'paineldiretoria::components.layouts.app' : 'admin::layouts.admin')

@php
    use Modules\Homepage\App\Support\HomepageNewsletterTemplates;
    $previewTokens = [
        HomepageNewsletterTemplates::TOKEN_SITE_NAME => \App\Support\SiteBranding::siteName(),
        HomepageNewsletterTemplates::TOKEN_YEAR => date('Y'),
        HomepageNewsletterTemplates::TOKEN_HOMEPAGE_URL => url('/'),
        HomepageNewsletterTemplates::TOKEN_CONTATO_URL => route('contato'),
    ];
    $isDiretoria = request()->routeIs('diretoria.*');
@endphp

@section('title', 'Nova campanha de newsletter')

@section('content')
<div class="{{ $isDiretoria ? 'mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in' : 'max-w-7xl mx-auto space-y-6' }}">
    @if ($isDiretoria)
        @include('paineldiretoria::partials.homepage-newsletter-subnav', ['active' => 'compose'])

        <header class="overflow-hidden rounded-3xl border border-indigo-100/90 bg-gradient-to-br from-indigo-50/90 via-white to-white p-6 shadow-sm dark:border-indigo-900/25 dark:from-indigo-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Campanha · e-mail em massa</p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Criar newsletter</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                        Escolha um modelo à esquerda (substitui o HTML), ajuste o assunto e o corpo, insira marcadores e blocos prontos. A pré-visualização usa os valores reais do site. O envio vai para <strong class="font-semibold text-gray-800 dark:text-slate-200">todos os assinantes ativos</strong>.
                    </p>
                    <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                        <a href="{{ route('diretoria.dashboard') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <a href="{{ route('diretoria.homepage.newsletter.index') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Newsletter</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <span class="font-medium text-gray-800 dark:text-slate-300">Nova campanha</span>
                    </nav>
                </div>
                <div class="shrink-0">
                    <a href="{{ homepage_panel_route('newsletter.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                        <x-icon name="arrow-left" class="h-4 w-4" style="solid" />
                        Voltar à lista
                    </a>
                </div>
            </div>
        </header>

        <div class="flex gap-4 rounded-2xl border border-violet-200/80 bg-violet-50/90 p-4 dark:border-violet-900/40 dark:bg-violet-950/30 md:items-center md:p-5">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-200/80 text-violet-900 dark:bg-violet-900/60 dark:text-violet-200">
                <x-icon name="layer-group" class="h-5 w-5" style="duotone" />
            </span>
            <p class="min-w-0 text-sm leading-relaxed text-violet-950/95 dark:text-violet-100/90">
                <span class="font-semibold text-violet-900 dark:text-violet-100">HTML inline</span>
                — os modelos usam estilos compatíveis com Gmail e Outlook. Imagens precisam de URL absoluta (<code class="rounded bg-white/70 px-1 font-mono text-xs dark:bg-black/30">https://…</code>).
            </p>
        </div>
    @else
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-900/40">
                        <x-icon name="envelope" class="h-5 w-5 text-indigo-600 dark:text-indigo-300" style="duotone" />
                    </span>
                    Criar newsletter
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Modelos, marcadores e pré-visualização. HTML com estilos inline.</p>
            </div>
            <a href="{{ homepage_panel_route('newsletter.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
                Voltar à lista
            </a>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200" role="alert">
            <p class="font-semibold">Corrija os seguintes pontos:</p>
            <ul class="mt-2 list-inside list-disc text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="newsletterComposeForm" action="{{ homepage_panel_route('newsletter.send') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
            {{-- Coluna esquerda: modelos e ajuda --}}
            <div class="space-y-6 lg:col-span-4 xl:col-span-4">
                <div class="lg:sticky lg:top-24 lg:space-y-6">
                    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6">
                        <div class="mb-4 flex items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-sm font-bold text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300" aria-hidden="true">1</span>
                            <div>
                                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Modelo base</h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Substitui todo o HTML do corpo. Guarde cópia se precisar do rascunho anterior.</p>
                            </div>
                        </div>
                        <div class="max-h-[min(480px,50vh)] space-y-2 overflow-y-auto pr-1">
                            @foreach ($newsletterTemplates as $tpl)
                                <button
                                    type="button"
                                    class="newsletter-template-btn w-full rounded-xl border-2 border-gray-100 bg-gray-50/80 p-3 text-left transition hover:border-indigo-300 hover:bg-indigo-50/50 dark:border-slate-600 dark:bg-slate-900/50 dark:hover:border-indigo-700 dark:hover:bg-indigo-950/30"
                                    data-template-id="{{ $tpl['id'] }}"
                                >
                                    <span class="block text-sm font-bold text-gray-900 dark:text-white">{{ $tpl['name'] }}</span>
                                    <span class="mt-0.5 block text-xs text-gray-600 dark:text-gray-400">{{ $tpl['description'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6">
                        <div class="mb-4 flex items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-sm font-bold text-violet-800 dark:bg-violet-950/60 dark:text-violet-300" aria-hidden="true">2</span>
                            <div>
                                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Marcadores</h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Inserem valores dinâmicos no envio.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($newsletterTokenLabels as $token => $label)
                                <button
                                    type="button"
                                    class="newsletter-insert-token rounded-lg border border-indigo-200 bg-indigo-50 px-2 py-1 text-xs font-mono text-indigo-900 hover:bg-indigo-100 dark:border-indigo-800 dark:bg-indigo-950/50 dark:text-indigo-100 dark:hover:bg-indigo-900/40"
                                    data-token="{{ $token }}"
                                    title="{{ $label }}"
                                >
                                    {{ $token }}
                                </button>
                            @endforeach
                        </div>
                    </section>

                    <div class="rounded-2xl border border-amber-200/90 bg-amber-50/90 p-4 text-xs text-amber-950 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-100">
                        <p class="font-semibold text-amber-900 dark:text-amber-200">Boas práticas</p>
                        <ul class="mt-2 list-inside list-disc space-y-1 text-amber-900/90 dark:text-amber-100/90">
                            <li>Um assunto curto melhora a taxa de abertura.</li>
                            <li>Depois de aplicar um modelo, revise links e datas no HTML.</li>
                            <li>Para campanhas grandes, teste primeiro com a sua caixa de correio.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Coluna direita: assunto, editor, ações --}}
            <div class="flex flex-col gap-8 lg:col-span-8 xl:col-span-8">
                <section class="space-y-6 rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6">
                    <div class="flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sm font-bold text-sky-800 dark:bg-sky-950/60 dark:text-sky-300" aria-hidden="true">3</span>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Assunto e corpo</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">O assunto aparece na caixa de entrada; o corpo é HTML completo do e-mail.</p>
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Assunto do e-mail</label>
                        <input
                            type="text"
                            name="subject"
                            id="subject"
                            required
                            maxlength="255"
                            value="{{ old('subject') }}"
                            placeholder="Ex.: Novidades da JUBAF — março {{ date('Y') }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:ring-indigo-500/30"
                        />
                    </div>

                    <div>
                        <span class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Blocos rápidos</span>
                        <p class="mb-3 text-xs text-gray-500 dark:text-slate-500">Inseridos na posição do cursor no editor.</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="newsletter-snippet rounded-lg border border-gray-200 bg-slate-100 px-3 py-2 text-xs font-medium text-slate-800 hover:bg-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600" data-snippet="button">Botão (link)</button>
                            <button type="button" class="newsletter-snippet rounded-lg border border-gray-200 bg-slate-100 px-3 py-2 text-xs font-medium text-slate-800 hover:bg-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600" data-snippet="callout">Caixa de destaque</button>
                            <button type="button" class="newsletter-snippet rounded-lg border border-gray-200 bg-slate-100 px-3 py-2 text-xs font-medium text-slate-800 hover:bg-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600" data-snippet="divider">Linha separadora</button>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                            <label for="content" class="text-sm font-semibold text-gray-800 dark:text-gray-200">HTML do corpo</label>
                            <div class="flex rounded-lg border border-gray-200 p-0.5 dark:border-slate-600">
                                <button type="button" id="tabBtnEditor" class="rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white">Editar</button>
                                <button type="button" id="tabBtnPreview" class="rounded-md px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700">Pré-visualizar</button>
                            </div>
                        </div>
                        <div id="panelEditor">
                            <textarea
                                name="content"
                                id="content"
                                rows="18"
                                required
                                minlength="20"
                                class="w-full rounded-xl border border-gray-200 bg-slate-950 px-4 py-3 font-mono text-sm text-slate-100 placeholder:text-slate-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600"
                                placeholder="Aplique um modelo à esquerda ou escreva o seu HTML."
                            >{!! old('content') !!}</textarea>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400"><span id="contentCharCount">0</span> caracteres · máx. 100&nbsp;000</p>
                        </div>
                        <div id="panelPreview" class="hidden">
                            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-slate-600">
                                <iframe id="newsletterPreviewFrame" title="Pré-visualização do e-mail" class="h-[min(520px,60vh)] w-full bg-gray-100"></iframe>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Pré-visualização aproximada; alguns clientes podem alterar ligeiramente o aspeto.</p>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col-reverse gap-3 rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:px-6 sm:py-5">
                    <a
                        href="{{ homepage_panel_route('newsletter.index') }}"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-900 dark:text-gray-200 dark:hover:bg-slate-700 sm:w-auto sm:border-0 sm:bg-transparent sm:shadow-none dark:sm:bg-transparent"
                    >
                        <x-icon name="xmark" class="h-5 w-5 shrink-0" style="solid" />
                        Cancelar
                    </a>
                    <div class="flex w-full flex-col gap-3 sm:w-auto sm:items-end">
                        <p class="text-center text-xs text-gray-500 dark:text-slate-400 sm:text-right">
                            Envio para <strong class="text-gray-800 dark:text-slate-200">todos os assinantes ativos</strong>. Ação irreversível.
                        </p>
                        <button
                            type="submit"
                            id="btnSendCampaign"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-800 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/20 transition hover:from-indigo-700 hover:to-indigo-900 focus:outline-none focus:ring-4 focus:ring-indigo-300/50 sm:w-auto sm:min-w-[12rem] dark:focus:ring-indigo-900/50"
                        >
                            <x-icon name="paper-plane" style="duotone" class="h-5 w-5" />
                            Enviar campanha
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const templates = @json($newsletterTemplates);
    const tokenMap = @json($previewTokens);
    const textarea = document.getElementById('content');
    const iframe = document.getElementById('newsletterPreviewFrame');
    const panelEditor = document.getElementById('panelEditor');
    const panelPreview = document.getElementById('panelPreview');
    const tabBtnEditor = document.getElementById('tabBtnEditor');
    const tabBtnPreview = document.getElementById('tabBtnPreview');
    const charCountEl = document.getElementById('contentCharCount');
    const form = document.getElementById('newsletterComposeForm');

    function replaceTokens(html) {
        let out = html;
        Object.keys(tokenMap).forEach(function (key) {
            const val = tokenMap[key];
            out = out.split(key).join(val);
        });
        return out;
    }

    function getTemplateById(id) {
        return templates.find(function (t) { return t.id === id; });
    }

    function updateCharCount() {
        if (!textarea || !charCountEl) return;
        charCountEl.textContent = String(textarea.value.length);
    }

    function insertAtCursor(text) {
        if (!textarea) return;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const before = textarea.value.substring(0, start);
        const after = textarea.value.substring(end);
        textarea.value = before + text + after;
        const pos = start + text.length;
        textarea.focus();
        textarea.setSelectionRange(pos, pos);
        updateCharCount();
    }

    function refreshPreview() {
        if (!iframe || !textarea) return;
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        const bodyHtml = replaceTokens(textarea.value);
        doc.open();
        doc.write('<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head><body style="margin:0;padding:0;">' + bodyHtml + '</body></html>');
        doc.close();
    }

    function showEditorTab() {
        panelEditor.classList.remove('hidden');
        panelPreview.classList.add('hidden');
        tabBtnEditor.classList.add('bg-indigo-600', 'text-white');
        tabBtnEditor.classList.remove('text-gray-600', 'hover:bg-gray-100', 'dark:text-gray-300', 'dark:hover:bg-slate-700');
        tabBtnPreview.classList.remove('bg-indigo-600', 'text-white');
        tabBtnPreview.classList.add('text-gray-600', 'hover:bg-gray-100', 'dark:text-gray-300', 'dark:hover:bg-slate-700');
    }

    function showPreviewTab() {
        refreshPreview();
        panelEditor.classList.add('hidden');
        panelPreview.classList.remove('hidden');
        tabBtnPreview.classList.add('bg-indigo-600', 'text-white');
        tabBtnPreview.classList.remove('text-gray-600', 'hover:bg-gray-100', 'dark:text-gray-300', 'dark:hover:bg-slate-700');
        tabBtnEditor.classList.remove('bg-indigo-600', 'text-white');
        tabBtnEditor.classList.add('text-gray-600', 'hover:bg-gray-100', 'dark:text-gray-300', 'dark:hover:bg-slate-700');
    }

    document.querySelectorAll('.newsletter-template-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = btn.getAttribute('data-template-id');
            const tpl = getTemplateById(id);
            if (!tpl) return;
            const hasContent = textarea.value.trim().length > 0;
            if (hasContent && !confirm('Substituir todo o HTML atual pelo modelo «' + tpl.name + '»?')) {
                return;
            }
            textarea.value = tpl.html;
            updateCharCount();
            showEditorTab();
        });
    });

    document.querySelectorAll('.newsletter-insert-token').forEach(function (btn) {
        btn.addEventListener('click', function () {
            insertAtCursor(btn.getAttribute('data-token') || '');
        });
    });

    const snippets = {
        button: '<table role="presentation" cellpadding="0" cellspacing="0" style="margin:16px 0;"><tr><td style="border-radius:8px;background:#2563eb;"><a href="___HOMEPAGE_URL___" style="display:inline-block;padding:12px 24px;font-family:Arial,sans-serif;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;">Texto do botão</a></td></tr></table>\n',
        callout: '<table role="presentation" width="100%" style="margin:16px 0;"><tr><td style="padding:16px;background:#eff6ff;border-radius:8px;border-left:4px solid #2563eb;font-family:Arial,sans-serif;font-size:15px;line-height:1.5;color:#1e3a8a;"><strong>Destaque:</strong> O seu texto aqui.</td></tr></table>\n',
        divider: '<p style="margin:24px 0;border-top:1px solid #e2e8f0;line-height:0;font-size:0;">&nbsp;</p>\n'
    };

    document.querySelectorAll('.newsletter-snippet').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const key = btn.getAttribute('data-snippet');
            if (snippets[key]) insertAtCursor(snippets[key]);
        });
    });

    if (tabBtnEditor) tabBtnEditor.addEventListener('click', showEditorTab);
    if (tabBtnPreview) tabBtnPreview.addEventListener('click', showPreviewTab);

    if (textarea) {
        textarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('Enviar esta campanha para todos os assinantes ativos da newsletter?')) {
                e.preventDefault();
            }
        });
    }
})();
</script>
@endpush
