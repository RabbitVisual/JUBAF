{{-- Painel lateral: simulação de como o slide pode aparecer na home --}}
<div class="space-y-4 lg:sticky lg:top-24">
    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
        <div class="flex items-start gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white dark:bg-slate-700">
                <x-icon name="eye" class="h-5 w-5" style="duotone" />
            </span>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Pré-visualização ao vivo</h3>
                <p class="mt-1 text-xs leading-relaxed text-gray-600 dark:text-slate-400">
                    Atualiza conforme você edita texto, imagem e botão. É uma aproximação do destaque na página inicial.
                </p>
            </div>
        </div>

        <div class="mt-5 overflow-hidden rounded-xl border border-gray-200 bg-slate-950 shadow-inner dark:border-slate-600">
            <div class="flex items-center gap-2 border-b border-white/10 bg-slate-900/90 px-3 py-2">
                <span class="h-2 w-2 rounded-full bg-red-400/90"></span>
                <span class="h-2 w-2 rounded-full bg-amber-400/90"></span>
                <span class="h-2 w-2 rounded-full bg-emerald-400/90"></span>
                <span class="ml-2 truncate text-[10px] text-slate-500">home — carrossel</span>
            </div>

            <div class="relative min-h-[220px] bg-slate-900">
                <div id="aside-preview-image" class="absolute inset-0 hidden transition-opacity duration-200">
                    <img
                        id="aside-preview-img"
                        src=""
                        alt=""
                        class="h-full w-full object-cover opacity-90"
                    />
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/70 to-slate-950/40"></div>
                </div>
                <div id="aside-preview-no-image" class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-gradient-to-br from-slate-800 to-slate-950 p-6 text-center">
                    <x-icon name="image" class="h-10 w-10 text-slate-600" style="duotone" />
                    <p class="text-xs text-slate-500">Nenhuma imagem ou imagem oculta</p>
                </div>

                <div class="relative z-10 flex min-h-[220px] flex-col justify-end p-5 sm:p-6">
                    <div
                        id="live-preview-title"
                        class="prose prose-invert max-w-none text-lg font-bold leading-snug text-white prose-p:my-0 [&_h1]:text-xl [&_h2]:text-lg [&_h3]:text-base"
                    ></div>
                    <div
                        id="live-preview-desc"
                        class="prose prose-invert mt-2 max-w-none text-sm text-slate-200 prose-p:my-1 prose-headings:text-white"
                    ></div>
                    <a
                        id="live-preview-btn"
                        href="#"
                        class="mt-4 hidden inline-flex w-fit items-center rounded-lg bg-pink-600 px-4 py-2 text-xs font-bold text-white shadow-lg shadow-pink-900/40 transition hover:bg-pink-500"
                    >
                        Saiba mais
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-violet-200/80 bg-violet-50/90 p-4 dark:border-violet-900/50 dark:bg-violet-950/40">
        <p class="text-xs font-semibold text-violet-900 dark:text-violet-200">Boas práticas</p>
        <ul class="mt-2 list-inside list-disc space-y-1 text-xs text-violet-800/90 dark:text-violet-300/90">
            <li>Textos curtos leem melhor em telas pequenas.</li>
            <li>Evite mais de um botão por slide; um link já basta.</li>
            <li>Teste o carrossel na lista após salvar — a ordem importa.</li>
        </ul>
    </div>
</div>
