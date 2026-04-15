@extends('admin::layouts.app')

@section('title', $book->name . ' - ' . $version->name)

@section('content')
    <x-bible::admin.layout
        title="{{ $book->name }}"
        subtitle="{{ $version->name }} · {{ $book->testament == 'old' ? 'Antigo Testamento' : 'Novo Testamento' }} · {{ $chapters->count() }} capítulos">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('show', $version) }}"
                class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-stone-800 dark:text-stone-100 bg-white dark:bg-stone-800 border border-amber-200/80 dark:border-amber-900/50 rounded-xl hover:bg-amber-50 dark:hover:bg-stone-700 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 mr-2" />
                {{ $version->name }}
            </a>
        </x-slot>

        <nav class="flex items-center flex-wrap gap-x-2 gap-y-1 text-sm text-stone-600 dark:text-stone-400 mb-2">
            <a href="{{ bible_admin_route('index') }}" class="hover:text-amber-800 dark:hover:text-amber-300 transition-colors">Bíblia Digital</a>
            <x-icon name="chevron-right" style="duotone" class="w-4 h-4 shrink-0 opacity-70" />
            <a href="{{ bible_admin_route('show', $version) }}" class="hover:text-amber-800 dark:hover:text-amber-300 transition-colors">{{ $version->name }}</a>
            <x-icon name="chevron-right" style="duotone" class="w-4 h-4 shrink-0 opacity-70" />
            <span class="text-stone-900 dark:text-stone-100 font-medium">{{ $book->name }}</span>
        </nav>

        <!-- Chapters Grid -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-amber-200/70 dark:border-amber-900/45 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center font-serif">
                    <x-icon name="file-lines" class="w-6 h-6 mr-2 text-amber-800 dark:text-amber-300" />
                    Capítulos
                </h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $chapters->count() }} capítulos disponíveis</span>
            </div>

            @if($chapters->isEmpty())
                <div class="text-center py-12">
                    <x-icon name="file-lines" class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" />
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nenhum capítulo encontrado</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Este livro ainda não possui capítulos importados.</p>
                </div>
            @else
                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 xl:grid-cols-12 gap-3">
                    @foreach($chapters as $chapter)
                        <a href="{{ bible_admin_route('chapter', ['version' => $version->id, 'book' => $book->id, 'chapter' => $chapter->id]) }}"
                            class="group relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-amber-200/70 dark:border-amber-900/45 bg-linear-to-br from-amber-50/50 to-stone-50/80 dark:from-gray-800 dark:to-gray-700 hover:border-amber-600 dark:hover:border-amber-600 hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <x-icon name="chevron-right" style="duotone" class="w-4 h-4 text-amber-800 dark:text-amber-300" />
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-amber-900 dark:group-hover:text-amber-200 transition-colors mb-1 font-serif">
                                {{ $chapter->chapter_number }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
                                {{ $chapter->verses_count }} vers.
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </x-bible::admin.layout>
@endsection

