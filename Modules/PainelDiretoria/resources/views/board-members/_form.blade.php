@php
    /** @var \App\Models\BoardMember $boardMember */
    $isEdit = $boardMember->exists;
    $routePrefix = $routePrefix ?? 'diretoria.board-members';
@endphp

<div class="space-y-6 lg:space-y-8">
    {{-- Etapa 1 --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6"
        aria-labelledby="bm-step-1">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-sm font-bold text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300"
                aria-hidden="true">1</span>
            <div class="min-w-0 flex-1">
                <h2 id="bm-step-1" class="text-base font-semibold text-gray-900 dark:text-white">Perfil público</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Nome, cargo e texto que os visitantes veem na página da diretoria.
                </p>
            </div>
        </div>
        <div class="space-y-5">
            <div>
                <label for="full_name" class="mb-2 flex flex-wrap items-baseline gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                    Nome completo
                    <span
                        class="rounded-md bg-red-50 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-700 dark:bg-red-950/50 dark:text-red-300">Obrigatório</span>
                </label>
                <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $boardMember->full_name) }}"
                    required maxlength="255"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    placeholder="Nome como deve aparecer no site" />
                @error('full_name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="public_title" class="mb-2 flex flex-wrap items-baseline gap-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                    Cargo público
                    <span
                        class="rounded-md bg-red-50 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-700 dark:bg-red-950/50 dark:text-red-300">Obrigatório</span>
                </label>
                <input type="text" name="public_title" id="public_title"
                    value="{{ old('public_title', $boardMember->public_title) }}" required maxlength="255"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    placeholder="Ex.: Presidente, Tesoureiro" />
                @error('public_title')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="group_label" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Grupo /
                    chapéu</label>
                <p class="mb-2 text-xs text-gray-500 dark:text-slate-500">Etiqueta opcional acima do nome (ex.: Mesa
                    diretora).</p>
                <input type="text" name="group_label" id="group_label"
                    value="{{ old('group_label', $boardMember->group_label) }}" maxlength="120"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    placeholder="Ex.: Mesa diretora" />
                @error('group_label')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="bio_short" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Bio
                    curta</label>
                <textarea name="bio_short" id="bio_short" rows="5" maxlength="2000"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    placeholder="Uma ou duas frases sobre a função ou experiência.">{{ old('bio_short', $boardMember->bio_short) }}</textarea>
                @error('bio_short')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Etapa 2 --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6"
        aria-labelledby="bm-step-2">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-sm font-bold text-violet-800 dark:bg-violet-950/60 dark:text-violet-300"
                aria-hidden="true">2</span>
            <div class="min-w-0 flex-1">
                <h2 id="bm-step-2" class="text-base font-semibold text-gray-900 dark:text-white">Foto e visibilidade</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Imagem de rosto ou busto; ordem na grelha e se o cartão fica visível no site.
                </p>
            </div>
        </div>
        <div class="space-y-6">
            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Fotografia</label>
                <label for="board-member-photo-input"
                    class="group flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-indigo-200/90 bg-gradient-to-b from-indigo-50/40 to-white px-4 py-10 text-center transition hover:border-indigo-400 hover:bg-indigo-50/60 dark:border-indigo-800/60 dark:from-indigo-950/20 dark:to-slate-900/50 dark:hover:border-indigo-500">
                    <span
                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 shadow-inner dark:bg-indigo-900/40 dark:text-indigo-300">
                        <x-icon name="camera" class="h-7 w-7" style="duotone" />
                    </span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Arrastar ou clicar para enviar</span>
                    <span class="max-w-sm text-xs text-gray-500 dark:text-slate-500">JPG, PNG ou WebP. A pré-visualização
                        atualiza ao lado.</span>
                    <input type="file" name="photo" id="board-member-photo-input" accept="image/*" class="sr-only" />
                </label>
                @if ($isEdit && $boardMember->photo_path)
                    <p
                        class="mt-3 flex flex-wrap items-center gap-2 rounded-lg border border-indigo-100 bg-indigo-50/80 px-3 py-2 text-xs text-indigo-900 dark:border-indigo-900/40 dark:bg-indigo-950/40 dark:text-indigo-200">
                        <x-icon name="circle-check" class="h-4 w-4 shrink-0" style="solid" />
                        Foto atual no servidor.
                        <a href="{{ $boardMember->photoUrl() }}" target="_blank" rel="noopener noreferrer"
                            class="font-semibold underline hover:text-indigo-700 dark:hover:text-white">Abrir imagem</a>
                    </p>
                @endif
                @error('photo')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 sm:items-end">
                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Ordem
                        na listagem</label>
                    <input type="number" name="sort_order" id="sort_order"
                        value="{{ old('sort_order', $boardMember->sort_order ?? 0) }}" min="0" max="9999"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-500">Números menores aparecem primeiro.</p>
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50/80 px-4 py-4 dark:border-slate-600 dark:bg-slate-900/50">
                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Visível no site</span>
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-500">Desligue para ocultar o cartão sem eliminar o
                        membro.</p>
                    <div class="mt-4 flex items-center gap-3">
                        <label for="is_active" class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="peer sr-only"
                                {{ old('is_active', $boardMember->is_active) ? 'checked' : '' }} />
                            <span
                                class="absolute inset-0 rounded-full bg-gray-300 transition peer-checked:bg-indigo-600 peer-focus-visible:ring-4 peer-focus-visible:ring-indigo-300/50 dark:bg-slate-600 dark:peer-checked:bg-indigo-600"></span>
                            <span
                                class="pointer-events-none absolute left-0.5 top-1/2 h-5 w-5 -translate-y-1/2 rounded-full bg-white shadow transition peer-checked:translate-x-[1.375rem] dark:shadow-md"></span>
                        </label>
                        <span id="is-active-label" class="text-sm font-medium text-gray-700 dark:text-slate-300">Ativo no
                            site</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Etapa 3 --}}
    <section class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:p-6"
        aria-labelledby="bm-step-3">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sm font-bold text-sky-800 dark:bg-sky-950/60 dark:text-sky-300"
                aria-hidden="true">3</span>
            <div class="min-w-0 flex-1">
                <h2 id="bm-step-3" class="text-base font-semibold text-gray-900 dark:text-white">Localização e mandato</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Dados opcionais para fichas ou filtros futuros no site.
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="city" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Cidade</label>
                <input type="text" name="city" id="city" value="{{ old('city', $boardMember->city) }}" maxlength="120"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                @error('city')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="state" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">UF</label>
                <input type="text" name="state" id="state" value="{{ old('state', $boardMember->state) }}" maxlength="8"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    placeholder="Ex.: SP" />
                @error('state')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="birth_date" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Data de
                    nascimento</label>
                <input type="date" name="birth_date" id="birth_date"
                    value="{{ old('birth_date', $boardMember->birth_date?->format('Y-m-d')) }}"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                @error('birth_date')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="mandate_year" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Gestão
                    / mandato (texto)</label>
                <input type="text" name="mandate_year" id="mandate_year"
                    value="{{ old('mandate_year', $boardMember->mandate_year) }}" maxlength="40"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                    placeholder="Ex.: 2026–2027" />
                @error('mandate_year')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div class="md:col-span-2">
                <label for="mandate_end" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Mandato
                    até</label>
                <input type="date" name="mandate_end" id="mandate_end"
                    value="{{ old('mandate_end', $boardMember->mandate_end?->format('Y-m-d')) }}"
                    class="w-full max-w-xs rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                @error('mandate_end')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Etapa 4 --}}
    <section class="rounded-2xl border border-indigo-200/70 bg-gradient-to-br from-indigo-50/50 to-white p-5 shadow-sm dark:border-indigo-900/35 dark:from-indigo-950/25 dark:to-slate-900 sm:p-6"
        aria-labelledby="bm-step-4">
        <div class="mb-5 flex flex-wrap items-start gap-3 border-b border-indigo-100/80 pb-4 dark:border-indigo-900/30">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-200 text-sm font-bold text-indigo-900 dark:bg-indigo-800 dark:text-indigo-100"
                aria-hidden="true">4</span>
            <div class="min-w-0 flex-1">
                <h2 id="bm-step-4" class="text-base font-semibold text-gray-900 dark:text-white">Conta de utilizador</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Opcional: associar a um utilizador interno (ex.: para futuras integrações ou perfis).
                </p>
            </div>
        </div>
        <div>
            <label for="user_id" class="mb-2 block text-sm font-semibold text-gray-800 dark:text-gray-200">Utilizador
                vinculado</label>
            <select name="user_id" id="user_id"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-gray-900 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                <option value="">— Nenhum —</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" @selected(old('user_id', $boardMember->user_id) == $u->id)>{{ $u->name }}
                        ({{ $u->email }})</option>
                @endforeach
            </select>
            @error('user_id')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </section>
</div>

@push('scripts')
    @include('paineldiretoria::board-members.partials.form-scripts', ['boardMember' => $boardMember])
@endpush
