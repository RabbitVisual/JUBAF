{{-- Pré-visualização do cartão na equipa --}}
@php
    $previewPhoto =
        isset($boardMember) && $boardMember->photo_path
            ? $boardMember->photoUrl()
            : null;
    $gLabel = trim((string) old('group_label', $boardMember->group_label ?? ''));
@endphp
<div class="space-y-4 lg:sticky lg:top-24">
    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
        <div class="flex items-start gap-3">
            <span
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-600/30 dark:bg-indigo-700">
                <x-icon name="eye" class="h-5 w-5" style="duotone" />
            </span>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Pré-visualização</h3>
                <p class="mt-1 text-xs leading-relaxed text-gray-600 dark:text-slate-400">
                    Aproximação de como o cartão pode surgir na página pública da equipa.
                </p>
            </div>
        </div>

        <div
            class="mt-5 overflow-hidden rounded-2xl border border-indigo-200/70 bg-gradient-to-br from-indigo-50/90 via-white to-violet-50/40 shadow-inner dark:border-indigo-900/40 dark:from-indigo-950/40 dark:via-slate-900 dark:to-slate-900">
            <div class="p-6 sm:p-7">
                <div class="flex flex-col items-center text-center sm:flex-row sm:items-start sm:text-left sm:gap-5">
                    <div id="bm-preview-avatar-wrap"
                        class="relative flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-white bg-indigo-100 shadow-lg ring-2 ring-indigo-100 dark:border-slate-700 dark:bg-indigo-950/50 dark:ring-indigo-900/50">
                        <img id="bm-preview-avatar-img" src="{{ $previewPhoto ?? '' }}" alt=""
                            class="{{ $previewPhoto ? 'h-full w-full object-cover' : 'hidden h-full w-full object-cover' }}" />
                        <span id="bm-preview-avatar-placeholder"
                            class="{{ $previewPhoto ? 'hidden' : 'flex' }} h-full w-full items-center justify-center text-indigo-400 dark:text-indigo-500">
                            <x-icon name="user" class="h-12 w-12" style="duotone" />
                        </span>
                    </div>
                    <div class="mt-4 min-w-0 flex-1 sm:mt-0">
                        <p id="bm-preview-group"
                            class="text-[10px] font-bold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-400 {{ $gLabel === '' ? 'hidden' : '' }}">{{ $gLabel }}</p>
                        <h4 id="bm-preview-name" class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                            {{ old('full_name', $boardMember->full_name) ?: 'Nome do membro' }}</h4>
                        <p id="bm-preview-title" class="mt-1 text-sm font-semibold text-indigo-800 dark:text-indigo-300">
                            {{ old('public_title', $boardMember->public_title) ?: 'Cargo público' }}</p>
                        <p id="bm-preview-bio" class="mt-3 line-clamp-4 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                            {{ old('bio_short', $boardMember->bio_short) ?: 'A bio curta aparecerá aqui.' }}</p>
                        <div class="mt-4 flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                            <span id="bm-preview-order"
                                class="inline-flex items-center rounded-lg bg-white/90 px-2.5 py-1 text-xs font-semibold text-gray-700 shadow-sm ring-1 ring-gray-200/80 dark:bg-slate-800/90 dark:text-slate-200 dark:ring-slate-600">Ordem:
                                {{ old('sort_order', $boardMember->sort_order ?? 0) }}</span>
                            @php
                                $activePreview = old('is_active', $boardMember->is_active ?? true);
                            @endphp
                            <span id="bm-preview-active"
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $activePreview ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300' }}">{{ $activePreview ? 'Visível' : 'Oculto' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-violet-200/80 bg-violet-50/90 p-4 dark:border-violet-900/50 dark:bg-violet-950/35">
        <p class="text-xs font-semibold text-violet-900 dark:text-violet-200">Sugestões</p>
        <ul class="mt-2 list-inside list-disc space-y-1 text-xs text-violet-800/90 dark:text-violet-300/90">
            <li>Fotos quadradas ou retrato leem melhor nos cartões.</li>
            <li>A ordem menor aparece primeiro na listagem.</li>
            <li>Desative «Visível» para ocultar sem apagar o registo.</li>
        </ul>
    </div>
</div>
