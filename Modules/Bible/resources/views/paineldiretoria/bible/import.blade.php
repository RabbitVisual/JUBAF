@extends('layouts.app')

@section('title', 'Bíblia digital — importar')

@section('content')
    <x-bible::admin.layout
        title="Importar versão (JSON)"
        subtitle="Carregue um ficheiro no formato JUBAF para criar ou atualizar uma tradução visível na app e no site.">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 shrink-0" />
                Voltar às versões
            </a>
        </x-slot>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <x-icon name="cloud-arrow-up" class="w-5 h-5 mr-2 text-amber-800 dark:text-amber-300" />
                    Dados da Versão
                </h2>
            </div>

            <form action="{{ bible_admin_route('import.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Nome da Versão <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors"
                            placeholder="Ex: Almeida Revista e Atualizada">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Abbreviation and Language -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="abbreviation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Abreviação <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="abbreviation" name="abbreviation" required maxlength="10"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors"
                                placeholder="Ex: ARA">
                            @error('abbreviation')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="language" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Idioma <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="language" name="language" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors"
                                value="pt-BR">
                            @error('language')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Descrição
                        </label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors resize-none"
                            placeholder="Descrição opcional da versão"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Arquivo JSON <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 dark:border-gray-600 dark:hover:border-blue-500 transition-colors">
                            <div class="space-y-1 text-center">
                                <x-icon name="file-arrow-up" class="mx-auto h-12 w-12 text-gray-400" />
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="file" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Selecione um arquivo</span>
                                        <input id="file" name="file" type="file" accept=".json" required class="sr-only">
                                    </label>
                                    <p class="pl-1">ou arraste e solte</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">JSON até 10MB</p>
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Available Files -->
                    @if(count($availableFiles) > 0)
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-3 flex items-center">
                                <x-icon name="file-lines" class="w-4 h-4 mr-2" />
                                Arquivos JSON Disponíveis
                            </h3>
                            <div class="grid md:grid-cols-2 gap-2">
                                @foreach($availableFiles as $file)
                                    <div class="flex items-center space-x-2 p-2 bg-white dark:bg-gray-800 rounded border border-blue-200 dark:border-blue-700">
                                        <x-icon name="file-lines" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" />
                                        <span class="text-sm text-gray-700 dark:text-gray-300 flex-1 truncate">{{ $file }}</span>
                                        @if(isset($versionsInfo) && !empty($versionsInfo))
                                            @php
                                                $versionInfo = collect($versionsInfo)->firstWhere('file', $file);
                                            @endphp
                                            @if($versionInfo)
                                                <span class="text-xs text-blue-600 dark:text-blue-400 flex-shrink-0">({{ $versionInfo['name'] }})</span>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Default Version -->
                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                        <input type="checkbox" id="is_default" name="is_default" value="1"
                            class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="is_default" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                            Definir como versão padrão
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3">
                    <a href="{{ bible_admin_route('index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-sm transition-colors inline-flex items-center gap-2">
                        <x-icon name="cloud-arrow-up" class="w-5 h-5 shrink-0" />
                        Importar versão
                    </button>
                </div>
            </form>
        </div>

        <!-- Format Info Card -->
        <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-amber-50/50 dark:bg-amber-950/20 p-6">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-amber-100 mb-3 flex items-center gap-2">
                <x-icon name="circle-info" style="duotone" class="w-5 h-5 shrink-0" />
                Formato do Arquivo JSON
            </h3>
            <p class="text-sm text-stone-800 dark:text-stone-300 mb-3">
                O arquivo JSON deve ser um array de livros, onde cada livro contém:
            </p>
            <pre class="bg-stone-100/90 dark:bg-stone-900/80 px-4 py-3 rounded-xl text-xs overflow-x-auto border border-amber-200/60 dark:border-amber-900/40 font-mono"><code>[
  {
    "name": "Gênesis",
    "abbrev": "Gn",
    "chapters": [
      ["Versículo 1", "Versículo 2", ...],
      ["Versículo 1", "Versículo 2", ...]
    ]
  },
  ...
]</code></pre>
        </div>
    </x-bible::admin.layout>
@endsection

