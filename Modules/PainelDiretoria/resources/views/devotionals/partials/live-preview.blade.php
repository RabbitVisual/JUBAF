{{-- Pré-visualização ao vivo — alinhada ao fluxo do carrossel --}}
@php
    $previewCoverUrl =
        isset($devotional) && $devotional->cover_image_path
            ? asset('storage/' . $devotional->cover_image_path)
            : null;
@endphp
<div class="space-y-4 lg:sticky lg:top-24">
    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
        <div class="flex items-start gap-3">
            <span
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-600 text-white shadow-md shadow-amber-600/30 dark:bg-amber-700">
                <x-icon name="eye" class="h-5 w-5" style="duotone" />
            </span>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Pré-visualização</h3>
                <p class="mt-1 text-xs leading-relaxed text-gray-600 dark:text-slate-400">
                    Atualiza enquanto preenche título, data, referência e reflexão. A capa aparece ao escolher uma imagem.
                </p>
            </div>
        </div>

        <div
            class="mt-5 overflow-hidden rounded-xl border border-amber-200/80 bg-gradient-to-br from-amber-50/90 via-white to-white shadow-inner dark:border-amber-900/40 dark:from-amber-950/40 dark:via-slate-900 dark:to-slate-900">
            <div id="devotional-preview-cover-wrap"
                class="relative {{ $previewCoverUrl ? '' : 'hidden' }} aspect-[21/9] max-h-48 w-full bg-slate-200 dark:bg-slate-800">
                <img id="devotional-preview-cover-img" src="{{ $previewCoverUrl ?? '' }}" alt=""
                    class="h-full w-full object-cover" />
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent">
                </div>
            </div>
            <div class="p-5 sm:p-6">
                <p id="devotional-preview-date"
                    class="text-[11px] font-bold uppercase tracking-widest text-amber-700 dark:text-amber-400"></p>
                <h4 id="devotional-preview-title"
                    class="mt-2 text-lg font-bold leading-snug text-gray-900 dark:text-white"></h4>
                <p id="devotional-preview-ref"
                    class="mt-3 border-l-4 border-amber-500 pl-3 text-sm font-semibold text-amber-900 dark:text-amber-200">
                </p>
                <div id="devotional-preview-scripture"
                    class="mt-3 max-h-28 overflow-hidden text-sm leading-relaxed text-gray-700 dark:text-slate-300 font-serif">
                </div>
                <div id="devotional-preview-body"
                    class="prose prose-sm mt-4 max-w-none text-gray-600 dark:prose-invert dark:text-slate-400 line-clamp-6">
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-amber-200/80 bg-amber-50/90 p-4 dark:border-amber-900/50 dark:bg-amber-950/35">
        <p class="text-xs font-semibold text-amber-900 dark:text-amber-200">Integração Bíblia</p>
        <ul class="mt-2 list-inside list-disc space-y-1 text-xs text-amber-900/90 dark:text-amber-300/90">
            <li>Use o assistente (livro → capítulo → versículos) e depois «Carregar texto».</li>
            <li>Intervalos longos podem ser recusados (máx. 40 versículos).</li>
            @if (Route::has('bible.public.index'))
                <li>
                    <a href="{{ route('bible.public.index') }}" target="_blank" rel="noopener noreferrer"
                        class="font-semibold text-amber-800 underline hover:text-amber-950 dark:text-amber-200">Abrir a Bíblia no site</a>
                    para consultar antes de colar a referência.
                </li>
            @endif
        </ul>
    </div>
</div>
