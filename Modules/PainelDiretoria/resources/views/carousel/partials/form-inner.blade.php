{{--
    Campos do formulário de slide (sem <form> — usado ao lado da pré-visualização).
    @var \App\Models\CarouselSlide|null $carousel
    @var bool|null $isEdit
--}}
@php
    $c = $carousel ?? null;
    $isEdit = $isEdit ?? ($c !== null);
    $orderDefault = $c
        ? old('order', $c->order)
        : old('order', \App\Models\CarouselSlide::max('order') + 1);
    $hasStoredImage = $c && $c->image;
@endphp

<div class="space-y-6 lg:space-y-8">
    {{-- Etapa 1: texto --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6" aria-labelledby="step-content-heading">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-pink-100 text-sm font-bold text-pink-700 dark:bg-pink-950/60 dark:text-pink-300" aria-hidden="true">1</span>
            <div class="min-w-0 flex-1">
                <h2 id="step-content-heading" class="text-base font-semibold text-gray-900 dark:text-white">Texto do slide</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    O título aparece em destaque; a descrição complementa a mensagem. Você pode usar negrito, cores e tamanhos — o editor abaixo funciona como um mini processador de texto.
                </p>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <span class="mb-2 flex flex-wrap items-baseline gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                    Título principal
                    <span class="rounded-md bg-amber-50 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-amber-800 dark:bg-amber-950/50 dark:text-amber-200">Obrigatório</span>
                </span>
                <p class="mb-2 text-xs text-gray-500 dark:text-slate-500">Ideal para a manchete ou chamada principal. HTML e formatação são aceitos.</p>
                <div id="quill-title" class="quill-editor-wrapper rounded-xl border border-gray-200 bg-gray-50/80 dark:border-slate-600 dark:bg-slate-900"></div>
                <textarea id="title" name="title" class="hidden">{!! old('title', $c?->title) !!}</textarea>
                @error('title')
                    <p class="mt-2 flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400"><x-icon name="exclamation-circle" class="h-4 w-4 shrink-0" /> {{ $message }}</p>
                @enderror
            </div>

            <div>
                <span class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Texto de apoio</span>
                <p class="mb-2 text-xs text-gray-500 dark:text-slate-500">Parágrafos, listas e links enriquecem o slide. Opcional, mas recomendado para contexto.</p>
                <div id="quill-description" class="quill-editor-wrapper min-h-[140px] rounded-xl border border-gray-200 bg-gray-50/80 dark:border-slate-600 dark:bg-slate-900"></div>
                <textarea id="description" name="description" class="hidden">{!! old('description', $c?->description) !!}</textarea>
                @error('description')
                    <p class="mt-2 flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400"><x-icon name="exclamation-circle" class="h-4 w-4 shrink-0" /> {{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Etapa 2: imagem --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6" aria-labelledby="step-image-heading">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-sm font-bold text-violet-700 dark:bg-violet-950/60 dark:text-violet-300" aria-hidden="true">2</span>
            <div class="min-w-0 flex-1">
                <h2 id="step-image-heading" class="text-base font-semibold text-gray-900 dark:text-white">Imagem de destaque</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Use uma foto horizontal de boa qualidade. Formatos JPG, PNG ou WebP. Se preferir só texto, desative a exibição da imagem na etapa 4.
                </p>
            </div>
        </div>

        <div
            class="group relative rounded-2xl border-2 border-dashed border-gray-300 bg-gradient-to-b from-gray-50/80 to-white transition-colors hover:border-pink-400 dark:border-slate-600 dark:from-slate-900/50 dark:to-slate-900 dark:hover:border-pink-500/60"
            data-carousel-dropzone
        >
            <label for="image" class="flex cursor-pointer flex-col items-center gap-3 px-4 py-10 text-center sm:px-8">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-pink-100 text-pink-600 shadow-inner dark:bg-pink-950/50 dark:text-pink-300">
                    <x-icon name="upload" class="h-7 w-7" style="duotone" />
                </span>
                <span class="text-sm font-semibold text-gray-900 dark:text-white">Arraste uma imagem ou clique para escolher</span>
                <span class="max-w-md text-xs text-gray-500 dark:text-slate-400">A pré-visualização ao lado atualiza automaticamente. Na edição, enviar um arquivo novo substitui a imagem atual.</span>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    class="sr-only"
                    data-carousel-file-input
                />
            </label>
        </div>
        @error('image')
            <p class="mt-3 flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400"><x-icon name="exclamation-circle" class="h-4 w-4 shrink-0" /> {{ $message }}</p>
        @enderror

        <div id="imagePreview" class="{{ $hasStoredImage ? '' : 'hidden' }} mt-5 overflow-hidden rounded-xl border border-gray-200 shadow-md dark:border-slate-600">
            <div class="relative aspect-[21/9] max-h-56 w-full bg-slate-100 dark:bg-slate-900 sm:max-h-none sm:min-h-[200px]">
                <img
                    id="previewImg"
                    src="{{ $hasStoredImage ? asset('storage/' . $c->image) : '' }}"
                    alt=""
                    class="h-full w-full object-cover"
                    data-original-src="{{ $hasStoredImage ? asset('storage/' . $c->image) : '' }}"
                />
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>
                <p class="absolute bottom-3 left-3 right-3 text-left text-xs font-medium text-white drop-shadow">
                    <span id="previewImgLabel">{{ $hasStoredImage ? 'Imagem atual' : 'Pré-visualização' }}</span>
                </p>
            </div>
        </div>
    </section>

    {{-- Etapa 3: ordem e CTA --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6" aria-labelledby="step-cta-heading">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sm font-bold text-sky-800 dark:bg-sky-950/60 dark:text-sky-300" aria-hidden="true">3</span>
            <div class="min-w-0 flex-1">
                <h2 id="step-cta-heading" class="text-base font-semibold text-gray-900 dark:text-white">Ordem e botão de ação</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    A ordem define a sequência na página inicial (número menor = aparece antes). O link e o texto do botão são opcionais — deixe em branco se não quiser call-to-action.
                </p>
            </div>
        </div>

        {{-- Três colunas alinhadas (ordem | URL | rótulo) em telas médias+ --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3 md:gap-6">
            <div class="min-w-0">
                <label for="order" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Posição na fila</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <x-icon name="list-bullet" class="h-5 w-5 text-gray-400" />
                    </div>
                    <input
                        type="number"
                        id="order"
                        name="order"
                        value="{{ $orderDefault }}"
                        min="0"
                        class="block w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3 text-gray-900 transition-colors focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:ring-pink-500/30"
                        inputmode="numeric"
                    />
                </div>
                <p class="mt-1.5 text-xs text-gray-500 dark:text-slate-500">Ou reordene arrastando na lista do carrossel.</p>
                @error('order')
                    <p class="mt-2 flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400"><x-icon name="exclamation-circle" class="h-4 w-4 shrink-0" /> {{ $message }}</p>
                @enderror
            </div>

            <div class="min-w-0 md:col-span-1">
                <label for="link" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">URL do botão</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <x-icon name="link" class="h-5 w-5 text-gray-400" />
                    </div>
                    <input
                        type="url"
                        id="link"
                        name="link"
                        value="{{ old('link', $c?->link) }}"
                        class="block w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3 text-gray-900 placeholder:text-gray-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:ring-pink-500/30"
                        placeholder="https://…"
                        autocomplete="url"
                        data-live-link
                    />
                </div>
            </div>

            <div class="min-w-0 md:col-span-1">
                <label for="link_text" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Rótulo do botão</label>
                <input
                    type="text"
                    id="link_text"
                    name="link_text"
                    value="{{ old('link_text', $c ? $c->link_text : 'Saiba mais') }}"
                    class="block w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 px-3 text-gray-900 placeholder:text-gray-400 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:ring-pink-500/30"
                    placeholder="Ex.: Ver detalhes"
                    maxlength="80"
                    data-live-link-text
                />
            </div>
        </div>
    </section>

    {{-- Etapa 4: publicação --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6" aria-labelledby="step-publish-heading">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-sm font-bold text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300" aria-hidden="true">4</span>
            <div class="min-w-0 flex-1">
                <h2 id="step-publish-heading" class="text-base font-semibold text-gray-900 dark:text-white">Visibilidade</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">Controle se o slide aparece no site e se a imagem deve ser mostrada junto com o texto.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:items-stretch">
            <div class="flex items-center gap-4 rounded-xl border border-gray-100 bg-gray-50/80 p-4 sm:p-5 dark:border-slate-700 dark:bg-slate-900/40">
                <label class="relative inline-flex shrink-0 cursor-pointer items-center">
                    <input type="checkbox" name="is_active" value="1" class="peer sr-only" {{ old('is_active', $c ? $c->is_active : true) ? 'checked' : '' }}>
                    <span class="relative h-6 w-11 shrink-0 rounded-full bg-gray-200 transition-colors peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:bg-gray-700 dark:peer-focus:ring-emerald-800 peer-checked:bg-emerald-500 after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:shadow-sm after:transition-transform after:content-[''] peer-checked:after:translate-x-5 dark:after:border-gray-600"></span>
                </label>
                <div class="min-w-0 flex-1 leading-snug">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $isEdit ? 'Slide publicado' : 'Publicar ao salvar' }}</p>
                    <p class="mt-1 text-xs text-gray-600 dark:text-slate-400">Desligado = oculto na home; continua na lista para edição.</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-xl border border-gray-100 bg-gray-50/80 p-4 sm:p-5 dark:border-slate-700 dark:bg-slate-900/40">
                <label class="relative inline-flex shrink-0 cursor-pointer items-center">
                    <input type="checkbox" name="show_image" value="1" class="peer sr-only" {{ old('show_image', $c ? $c->show_image : true) ? 'checked' : '' }} data-show-image-toggle>
                    <span class="relative h-6 w-11 shrink-0 rounded-full bg-gray-200 transition-colors peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 dark:bg-gray-700 dark:peer-focus:ring-pink-800 peer-checked:bg-pink-600 after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:shadow-sm after:transition-transform after:content-[''] peer-checked:after:translate-x-5 dark:after:border-gray-600"></span>
                </label>
                <div class="min-w-0 flex-1 leading-snug">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Mostrar imagem no slide</p>
                    <p class="mt-1 text-xs text-gray-600 dark:text-slate-400">Desative para banner só com texto ou sem arte.</p>
                </div>
            </div>
        </div>
    </section>
</div>
