@extends('layouts.app')

@section('title', 'Strong '.$entry->strong_number)

@section('content')
    <x-bible::admin.layout
        title="Editar {{ $entry->strong_number }}"
        subtitle="A coluna «texto importado» (description_original) não é sobrescrita pelas reimportações. Congele a descrição editável para proteger glossário técnico.">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('study.strongs.index') }}"
                class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-stone-800 dark:text-stone-100 bg-white dark:bg-stone-800 border border-amber-200/80 dark:border-amber-900/50 rounded-xl hover:bg-amber-50 dark:hover:bg-stone-700 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 mr-2" />
                Lista
            </a>
        </x-slot>

        <div class="max-w-4xl space-y-6">

        @if (session('success'))
            <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-amber-200/70 dark:border-amber-900/45 p-6 space-y-4 shadow-sm">
            <div>
                <span class="text-xs font-bold text-amber-800/80 dark:text-amber-400/90 uppercase tracking-wider">Lemma (original)</span>
                <p class="text-2xl font-serif mt-1 text-gray-900 dark:text-white" dir="rtl">{{ $entry->lemma }}</p>
            </div>
        </div>

        <div class="bg-stone-50 dark:bg-stone-900/40 rounded-2xl border border-stone-200 dark:border-stone-700 p-6 space-y-3">
            <h3 class="text-sm font-bold text-stone-800 dark:text-stone-200 uppercase tracking-wider">Texto importado (referência, só leitura)</h3>
            <p class="text-xs text-stone-600 dark:text-stone-400">Gravado na primeira importação ou cópia explícita; não é actualizado por <code>bible:import-strongs</code> em linhas existentes.</p>
            <textarea readonly rows="5"
                class="w-full px-4 py-3 border border-stone-200 dark:border-stone-600 rounded-lg bg-white dark:bg-stone-950 text-gray-700 dark:text-gray-300 font-mono text-sm">{{ $entry->description_original ?? '—' }}</textarea>
        </div>

        <form action="{{ bible_admin_route('study.strongs.update', $entry->strong_number) }}" method="POST" class="bg-white dark:bg-gray-800 rounded-2xl border border-amber-200/70 dark:border-amber-900/45 p-6 space-y-6 shadow-sm">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Equivalente semântico (lemma_br)</label>
                <textarea name="lemma_br" rows="2"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">{{ old('lemma_br', $entry->lemma_br) }}</textarea>
                @error('lemma_br')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Equivalente semântico (nota editorial)</label>
                <textarea name="semantic_equivalent_pt" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">{{ old('semantic_equivalent_pt', $entry->semantic_equivalent_pt) }}</textarea>
                @error('semantic_equivalent_pt')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Significado e uso (nota editorial)</label>
                <textarea name="meaning_usage_pt" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">{{ old('meaning_usage_pt', $entry->meaning_usage_pt) }}</textarea>
                @error('meaning_usage_pt')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-amber-200/50 dark:border-amber-900/40 pt-6 space-y-4">
                <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200 uppercase tracking-wider">Campos técnicos (inglês / BSRTB)</h3>
                @if ($entry->description_frozen)
                    <div class="rounded-lg border border-amber-300/80 bg-amber-50/80 dark:border-amber-800 dark:bg-amber-950/30 p-4 text-sm text-amber-950 dark:text-amber-100">
                        <p class="font-semibold mb-2">Descrição congelada</p>
                        <p class="mb-3 text-xs opacity-90">Para alterar transliteração, pronúncia ou descrição técnica, confirme abaixo (responsabilidade editorial).</p>
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="allow_technical_edit" value="0">
                            <input type="checkbox" name="allow_technical_edit" id="allow_technical_edit" value="1" @checked(old('allow_technical_edit'))
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="allow_technical_edit" class="text-sm">Permitir edição técnica neste envio</label>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Transliteração (xlit)</label>
                    <input type="text" name="xlit" value="{{ old('xlit', $entry->xlit) }}"
                        @if ($entry->description_frozen && ! old('allow_technical_edit')) readonly @endif
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white @if ($entry->description_frozen && ! old('allow_technical_edit')) opacity-60 @endif">
                    @error('xlit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pronúncia</label>
                    <input type="text" name="pronounce" value="{{ old('pronounce', $entry->pronounce) }}"
                        @if ($entry->description_frozen && ! old('allow_technical_edit')) readonly @endif
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white @if ($entry->description_frozen && ! old('allow_technical_edit')) opacity-60 @endif">
                    @error('pronounce')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Descrição / glossário (editável)</label>
                    <textarea name="description" rows="6"
                        @if ($entry->description_frozen && ! old('allow_technical_edit')) readonly @endif
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white font-mono text-sm @if ($entry->description_frozen && ! old('allow_technical_edit')) opacity-60 @endif">{{ old('description', $entry->description) }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-wrap gap-4 items-center">
                    <button type="submit" name="restore_description_from_original" value="1" formaction="{{ bible_admin_route('study.strongs.update', $entry->strong_number) }}"
                        formnovalidate
                        class="px-4 py-2 text-sm font-semibold rounded-xl border border-stone-300 dark:border-stone-600 text-stone-800 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                        Restaurar descrição a partir do texto importado
                    </button>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-gray-100 dark:border-gray-700 pt-6">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="description_frozen" value="0">
                    <input type="checkbox" name="description_frozen" id="description_frozen" value="1" @checked(old('description_frozen', $entry->description_frozen))
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="description_frozen" class="text-sm text-gray-700 dark:text-gray-300">Congelar descrição técnica (importações só actualizam campos PT)</label>
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="admin_locked" value="0">
                    <input type="checkbox" name="admin_locked" id="admin_locked" value="1" @checked(old('admin_locked', $entry->admin_locked))
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="admin_locked" class="text-sm text-gray-700 dark:text-gray-300">Bloquear linha completa na reimportação (<code>bible:import-strongs</code>)</label>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-linear-to-r from-amber-800 to-stone-900 text-amber-50 rounded-xl font-semibold hover:from-amber-900 hover:to-stone-950 shadow-md">Salvar</button>
            </div>
        </form>
        </div>
    </x-bible::admin.layout>
@endsection
