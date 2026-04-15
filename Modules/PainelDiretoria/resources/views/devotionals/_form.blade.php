@php
    /** @var \App\Models\Devotional $devotional */
    $versions = module_enabled('Bible')
        ? \Modules\Bible\App\Models\BibleVersion::query()
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get(['id', 'name', 'abbreviation'])
        : collect();
    $bibleEnabled = module_enabled('Bible') && $versions->isNotEmpty();
@endphp

<div class="space-y-6 lg:space-y-8">
    {{-- Etapa 1 --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6"
        aria-labelledby="devotional-step-1">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-sm font-bold text-amber-800 dark:bg-amber-950/60 dark:text-amber-300"
                aria-hidden="true">1</span>
            <div class="min-w-0 flex-1">
                <h2 id="devotional-step-1" class="text-base font-semibold text-gray-900 dark:text-white">Identidade e
                    publicação</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Título, URL amigável, data e estado. O slug é opcional; se vazio, será gerado a partir do título.
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-2 flex flex-wrap items-baseline gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                    Título
                    <span
                        class="rounded-md bg-red-50 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-700 dark:bg-red-950/50 dark:text-red-300">Obrigatório</span>
                </label>
                <input type="text" name="title" id="devotional-title" required
                    value="{{ old('title', $devotional->title) }}"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 shadow-sm transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    placeholder="Ex.: A paz que excede todo o entendimento" />
                @error('title')
                    <p class="mt-2 flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400"><x-icon
                            name="exclamation-circle" class="h-4 w-4 shrink-0" /> {{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Slug (opcional)</label>
                <input type="text" name="slug" value="{{ old('slug', $devotional->slug) }}"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    placeholder="aparece-na-url" />
                @error('slug')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Data do devocional</label>
                <input type="date" name="devotional_date" id="devotional-date"
                    value="{{ old('devotional_date', $devotional->devotional_date?->format('Y-m-d')) }}"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Tema (opcional)</label>
                <input type="text" name="theme" value="{{ old('theme', $devotional->theme) }}"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    placeholder="Ex.: Fé, gratidão, família" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Estado</label>
                <select name="status"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                    <option value="{{ \App\Models\Devotional::STATUS_DRAFT }}" @selected(old('status', $devotional->status) === \App\Models\Devotional::STATUS_DRAFT)>
                        Rascunho</option>
                    <option value="{{ \App\Models\Devotional::STATUS_PUBLISHED }}" @selected(old('status', $devotional->status) === \App\Models\Devotional::STATUS_PUBLISHED)>
                        Publicado</option>
                </select>
            </div>
        </div>
    </section>

    {{-- Etapa 2 — Leitura bíblica --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6"
        aria-labelledby="devotional-step-2">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sm font-bold text-sky-800 dark:bg-sky-950/60 dark:text-sky-300"
                aria-hidden="true">2</span>
            <div class="min-w-0 flex-1">
                <h2 id="devotional-step-2" class="text-base font-semibold text-gray-900 dark:text-white">Leitura bíblica
                </h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Escolha a versão, use o assistente para montar a referência no mesmo formato do módulo Bíblia
                    («Livro capítulo:versículo») e carregue o texto automaticamente.
                </p>
            </div>
        </div>

        @if ($bibleEnabled)
            <div class="mb-6 rounded-xl border border-sky-100 bg-sky-50/60 p-4 dark:border-sky-900/40 dark:bg-sky-950/25">
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-800 dark:text-sky-300">Assistente de
                    passagem</p>
                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-12 lg:items-end">
                    <div class="sm:col-span-2 lg:col-span-4">
                        <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-slate-300">Versão</label>
                        <select id="bible_version_id" name="bible_version_id"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                            <option value="">Versão padrão do site</option>
                            @foreach ($versions as $v)
                                <option value="{{ $v->id }}" @selected(old('bible_version_id', $devotional->bible_version_id) == $v->id)>{{ $v->name }}
                                    ({{ $v->abbreviation }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-4">
                        <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-slate-300">Livro</label>
                        <select id="picker_book"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 disabled:opacity-60 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                            <option value="">Carregue após escolher a versão</option>
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-slate-300">Capítulo</label>
                        <select id="picker_chapter" disabled
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 disabled:opacity-60 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                            <option value="">—</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2 lg:col-span-2">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-slate-300">De</label>
                            <input type="number" id="verse_start" min="1" value="1"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-slate-300">Até</label>
                            <input type="number" id="verse_end" min="1" value="1"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="button" id="btn-build-ref"
                        class="inline-flex items-center gap-2 rounded-xl border border-sky-300 bg-white px-4 py-2.5 text-sm font-semibold text-sky-900 shadow-sm transition hover:bg-sky-50 dark:border-sky-700 dark:bg-slate-900 dark:text-sky-200 dark:hover:bg-slate-800">
                        <x-icon name="book-open" class="h-4 w-4" style="duotone" />
                        Preencher referência
                    </button>
                </div>
            </div>
        @else
            <div
                class="mb-6 rounded-xl border border-amber-200 bg-amber-50/80 px-4 py-3 text-sm text-amber-900 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-200">
                O módulo Bíblia está inativo ou sem versões. Introduza a referência manualmente abaixo e o texto da
                passagem.
            </div>
        @endif

        <div class="space-y-4">
            <div>
                <label class="mb-2 flex flex-wrap items-baseline gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                    Referência bíblica
                    <span
                        class="rounded-md bg-red-50 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-700 dark:bg-red-950/50 dark:text-red-300">Obrigatório</span>
                </label>
                <p class="mb-2 text-xs text-gray-500 dark:text-slate-500">Formato: «Salmos 23:1-3» — espaço antes do número
                    do capítulo, como na Bíblia integrada.</p>
                <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                    <input type="text" id="scripture_reference" name="scripture_reference" required
                        value="{{ old('scripture_reference', $devotional->scripture_reference) }}"
                        class="min-w-0 flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                        placeholder="Salmos 23:1-3" />
                    @if ($bibleEnabled)
                        <button type="button" id="btn-fetch-scripture"
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-slate-800 dark:bg-amber-700 dark:hover:bg-amber-600">
                            <x-icon name="download" class="h-4 w-4" style="solid" />
                            Carregar texto
                        </button>
                    @endif
                </div>
                <p id="fetch-scripture-msg" class="mt-2 text-xs text-gray-500 dark:text-slate-500"></p>
                @error('scripture_reference')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Texto da passagem</label>
                <textarea id="scripture_text" name="scripture_text" rows="8"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 font-serif text-sm leading-relaxed text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    placeholder="Preenchido ao carregar da Bíblia ou edite à mão.">{{ old('scripture_text', $devotional->scripture_text) }}</textarea>
            </div>
        </div>
    </section>

    {{-- Etapa 3 — Reflexão --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6"
        aria-labelledby="devotional-step-3">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-sm font-bold text-violet-800 dark:bg-violet-950/60 dark:text-violet-300"
                aria-hidden="true">3</span>
            <div class="min-w-0 flex-1">
                <h2 id="devotional-step-3" class="text-base font-semibold text-gray-900 dark:text-white">Reflexão</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    O corpo do devocional: mensagem principal para os leitores.
                </p>
            </div>
        </div>
        <div>
            <label class="mb-2 flex flex-wrap items-baseline gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                Conteúdo
                <span
                    class="rounded-md bg-red-50 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-700 dark:bg-red-950/50 dark:text-red-300">Obrigatório</span>
            </label>
            <textarea name="body" id="devotional-body" required rows="12"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                placeholder="Escreva a reflexão pastoral ou de aplicação...">{{ old('body', $devotional->body) }}</textarea>
            @error('body')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </section>

    {{-- Etapa 4 — Mídia --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6"
        aria-labelledby="devotional-step-4">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-sm font-bold text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300"
                aria-hidden="true">4</span>
            <div class="min-w-0 flex-1">
                <h2 id="devotional-step-4" class="text-base font-semibold text-gray-900 dark:text-white">Capa e vídeo</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Imagem opcional para partilha; vídeo em ficheiro ou URL externa segura.
                </p>
            </div>
        </div>
        @php
            $hasStoredCover = filled($devotional->cover_image_path ?? null);
            $hasStoredVideoFile = filled($devotional->video_path ?? null);
            $videoUrlValue = old('video_url', $devotional->video_url);
            $videoModeInitial = 'none';
            if (filled($videoUrlValue)) {
                $videoModeInitial = 'url';
            } elseif ($hasStoredVideoFile || $errors->has('video')) {
                $videoModeInitial = 'file';
            }
        @endphp

        <input type="hidden" name="clear_devotional_video" id="clear_devotional_video" value="0" />

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-8">
            {{-- Capa --}}
            <div
                class="flex flex-col rounded-2xl border border-emerald-200/70 bg-gradient-to-b from-emerald-50/60 to-white p-4 shadow-sm dark:border-emerald-900/40 dark:from-emerald-950/30 dark:to-slate-900/90 sm:p-5">
                <div class="flex items-start gap-3">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                        <x-icon name="image" class="h-5 w-5" style="duotone" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm font-semibold text-emerald-950 dark:text-emerald-100">Imagem de capa</h3>
                        <p class="mt-1 text-xs leading-relaxed text-gray-600 dark:text-slate-400">
                            Opcional. Usada em cartões e partilhas. JPG, PNG ou WebP.
                        </p>
                    </div>
                </div>

                <label for="devotional-cover-input"
                    class="group mt-4 flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-emerald-200/90 bg-white/80 px-4 py-8 text-center transition hover:border-emerald-400 hover:bg-emerald-50/50 dark:border-emerald-800/60 dark:bg-slate-900/40 dark:hover:border-emerald-600 dark:hover:bg-emerald-950/20">
                    <span
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 shadow-inner dark:bg-emerald-900/40 dark:text-emerald-300">
                        <x-icon name="upload" class="h-6 w-6" style="duotone" />
                    </span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Arrastar ou clicar para escolher</span>
                    <span class="max-w-xs text-xs text-gray-500 dark:text-slate-500">A pré-visualização à direita atualiza ao
                        selecionar.</span>
                    <input type="file" id="devotional-cover-input" name="cover" accept="image/*" class="sr-only" />
                </label>
                @if ($hasStoredCover)
                    <p
                        class="mt-3 flex items-center gap-2 rounded-lg border border-emerald-100 bg-emerald-50/80 px-3 py-2 text-xs text-emerald-900 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-200">
                        <x-icon name="circle-check" class="h-4 w-4 shrink-0" style="solid" />
                        Já existe capa. Um novo ficheiro substitui a atual.
                    </p>
                @endif
                @error('cover')
                    <p class="mt-2 flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400"><x-icon
                            name="exclamation-circle" class="h-4 w-4 shrink-0" /> {{ $message }}</p>
                @enderror
            </div>

            {{-- Vídeo: modo explícito --}}
            <div
                class="flex flex-col rounded-2xl border border-emerald-200/70 bg-gradient-to-b from-white to-emerald-50/40 p-4 shadow-sm dark:border-emerald-900/40 dark:from-slate-900/90 dark:to-emerald-950/20 sm:p-5"
                id="devotional-video-block" data-initial-mode="{{ $videoModeInitial }}">
                <div class="flex items-start gap-3">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                        <x-icon name="circle-play" class="h-5 w-5" style="duotone" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm font-semibold text-emerald-950 dark:text-emerald-100">Vídeo</h3>
                        <p class="mt-1 text-xs leading-relaxed text-gray-600 dark:text-slate-400">
                            Use <strong class="font-semibold text-gray-800 dark:text-slate-200">um</strong> dos modos: ficheiro
                            alojado no site ou URL externa (YouTube, Vimeo, etc.). Não combine os dois.
                        </p>
                    </div>
                </div>

                <fieldset class="mt-4">
                    <legend class="sr-only">Modo de vídeo</legend>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-3" role="radiogroup" aria-label="Origem do vídeo">
                        <button type="button" data-video-mode="none"
                            class="video-mode-btn flex items-center justify-center gap-2 rounded-xl border-2 border-transparent bg-gray-100/90 px-3 py-2.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-200/90 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                            <x-icon name="ban" class="h-3.5 w-3.5" style="solid" />
                            Sem vídeo
                        </button>
                        <button type="button" data-video-mode="file"
                            class="video-mode-btn flex items-center justify-center gap-2 rounded-xl border-2 border-transparent bg-gray-100/90 px-3 py-2.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-200/90 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                            <x-icon name="file-video" class="h-3.5 w-3.5" style="solid" />
                            Ficheiro
                        </button>
                        <button type="button" data-video-mode="url"
                            class="video-mode-btn flex items-center justify-center gap-2 rounded-xl border-2 border-transparent bg-gray-100/90 px-3 py-2.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-200/90 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                            <x-icon name="link" class="h-3.5 w-3.5" style="solid" />
                            URL
                        </button>
                    </div>
                </fieldset>

                <div id="video-panel-none"
                    class="mt-4 rounded-xl border border-dashed border-gray-200 bg-gray-50/80 px-4 py-6 text-center dark:border-slate-600 dark:bg-slate-800/50">
                    <p class="text-sm text-gray-600 dark:text-slate-400">Nenhum vídeo será associado a este devocional.</p>
                    @if ($hasStoredVideoFile || filled($videoUrlValue))
                        <p class="mt-2 text-xs text-amber-800 dark:text-amber-200">Ao guardar com este modo, o vídeo atual
                            será removido.</p>
                    @endif
                </div>

                <div id="video-panel-file" class="mt-4 hidden">
                    <label for="devotional-video-input"
                        class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-emerald-200/90 bg-white/90 px-4 py-8 text-center transition hover:border-emerald-400 dark:border-emerald-800/60 dark:bg-slate-900/50 dark:hover:border-emerald-600">
                        <x-icon name="file-video" class="h-8 w-8 text-emerald-600 dark:text-emerald-400" style="duotone" />
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">MP4 ou WebM (máx. ~100 MB)</span>
                        <span class="text-xs text-gray-500 dark:text-slate-500">Clique para escolher o ficheiro</span>
                        <input type="file" id="devotional-video-input" name="video" accept="video/mp4,video/webm"
                            class="sr-only" />
                    </label>
                    <p id="devotional-video-filename" class="mt-2 hidden text-center text-xs font-medium text-emerald-800 dark:text-emerald-300">
                    </p>
                    @if ($hasStoredVideoFile)
                        <p
                            class="mt-3 flex items-center gap-2 rounded-lg border border-emerald-100 bg-emerald-50/80 px-3 py-2 text-xs text-emerald-900 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-200">
                            <x-icon name="circle-check" class="h-4 w-4 shrink-0" style="solid" />
                            Já existe um vídeo no servidor. Enviar um novo substitui o anterior.
                        </p>
                    @endif
                    @error('video')
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400"><x-icon
                                name="exclamation-circle" class="h-4 w-4 shrink-0" /> {{ $message }}</p>
                    @enderror
                </div>

                <div id="video-panel-url" class="mt-4 hidden">
                    <label for="devotional-video-url" class="mb-2 block text-xs font-semibold text-gray-700 dark:text-slate-300">URL
                        do vídeo</label>
                    <input type="url" name="video_url" id="devotional-video-url" value="{{ $videoUrlValue }}"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                        placeholder="https://www.youtube.com/watch?v=… ou link Vimeo"
                        autocomplete="url" />
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-500">Cole o endereço completo (com https://). Ao
                        guardar, um vídeo em ficheiro anterior é substituído por esta ligação.</p>
                    @error('video_url')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    {{-- Etapa 5 — Autor --}}
    <section class="rounded-2xl border border-amber-200/80 bg-gradient-to-br from-amber-50/80 to-white p-5 shadow-sm dark:border-amber-900/40 dark:from-amber-950/25 dark:to-slate-900 sm:p-6"
        aria-labelledby="devotional-step-5">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-amber-100/80 pb-4 dark:border-amber-900/30">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-200 text-sm font-bold text-amber-900 dark:bg-amber-800 dark:text-amber-100"
                aria-hidden="true">5</span>
            <div class="min-w-0 flex-1">
                <h2 id="devotional-step-5" class="text-base font-semibold text-gray-900 dark:text-white">Autoria</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Quem aparece como autor na publicação: utilizador interno, membro da diretoria ou convidado.
                </p>
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Tipo de autor</label>
                <select name="author_type" id="author_type"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                    <option value="{{ \App\Models\Devotional::AUTHOR_USER }}" @selected(old('author_type', $devotional->author_type) === \App\Models\Devotional::AUTHOR_USER)>
                        Utilizador</option>
                    <option value="{{ \App\Models\Devotional::AUTHOR_BOARD_MEMBER }}" @selected(old('author_type', $devotional->author_type) === \App\Models\Devotional::AUTHOR_BOARD_MEMBER)>
                        Membro da diretoria</option>
                    <option value="{{ \App\Models\Devotional::AUTHOR_PASTOR_GUEST }}" @selected(old('author_type', $devotional->author_type) === \App\Models\Devotional::AUTHOR_PASTOR_GUEST)>
                        Pastor / convidado</option>
                </select>
            </div>
            <div id="box-user" class="author-box">
                <label class="mb-2 block text-xs font-semibold text-gray-700 dark:text-slate-300">Utilizador</label>
                <select name="user_id"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" @selected(old('user_id', $devotional->user_id) == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div id="box-board" class="author-box hidden">
                <label class="mb-2 block text-xs font-semibold text-gray-700 dark:text-slate-300">Diretoria</label>
                <select name="board_member_id"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                    <option value="">—</option>
                    @foreach ($boardMembers as $bm)
                        <option value="{{ $bm->id }}" @selected(old('board_member_id', $devotional->board_member_id) == $bm->id)>{{ $bm->full_name }} —
                            {{ $bm->public_title }}</option>
                    @endforeach
                </select>
                @error('board_member_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div id="box-guest" class="author-box hidden space-y-3">
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-700 dark:text-slate-300">Nome</label>
                    <input type="text" name="guest_author_name"
                        value="{{ old('guest_author_name', $devotional->guest_author_name) }}"
                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                    @error('guest_author_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold text-gray-700 dark:text-slate-300">Cargo / título</label>
                    <input type="text" name="guest_author_title"
                        value="{{ old('guest_author_title', $devotional->guest_author_title) }}"
                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    @include('paineldiretoria::devotionals.partials.form-scripts', [
        'routePrefix' => $routePrefix,
        'bibleEnabled' => $bibleEnabled,
    ])
@endpush
