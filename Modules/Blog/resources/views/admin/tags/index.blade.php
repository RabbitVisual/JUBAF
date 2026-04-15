@extends('layouts.app')

@section('title', 'Tags - Blog Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 pb-4 border-b border-gray-200 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <x-icon name="tags" class="w-8 h-8 text-emerald-600 dark:text-emerald-500" />
                Tags do Blog
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Blog</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900 dark:text-gray-100">Tags</li>
                </ol>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ blog_admin_route('index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" />
                Voltar ao Blog
            </a>
            <a href="{{ blog_admin_route('tags.create') }}"
               class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                <x-icon name="plus" class="w-4 h-4 mr-2" />
                Nova Tag
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
@if(isset($estatisticas))
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                <x-icon name="tags" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $estatisticas['total_tags'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg">
                <x-icon name="circle-check" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Usadas</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $estatisticas['used_tags'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-slate-100 dark:bg-slate-700 rounded-lg">
                <x-icon name="circle-xmark" class="w-6 h-6 text-slate-500 dark:text-slate-400" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Não usadas</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $estatisticas['unused_tags'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Tags Table -->
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
            <thead class="bg-gray-50 dark:bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tag</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Posts</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Criada em</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                @forelse($tags as $tag)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center mr-4">
                                <x-icon name="tag" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <a href="{{ blog_admin_route('tags.show', $tag->id) }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                        {{ $tag->name }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $tag->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            {{ $tag->posts_count }} posts
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $tag->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ blog_admin_route('tags.edit', $tag->id) }}"
                               class="p-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 hover:text-white hover:bg-emerald-600 dark:text-emerald-400 dark:hover:bg-emerald-500 rounded-lg transition-all border border-emerald-200 dark:border-emerald-800 shadow-sm" title="Editar">
                                <x-icon name="pen-to-square" class="w-4 h-4" />
                            </a>
                            @if($tag->posts_count == 0)
                            <form action="{{ blog_admin_route('tags.destroy', $tag->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Tem certeza que deseja excluir esta tag?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-50 dark:bg-red-900/20 text-red-600 hover:text-white hover:bg-red-600 dark:text-red-400 dark:hover:bg-red-500 rounded-lg transition-all border border-red-200 dark:border-red-800 shadow-sm" title="Excluir">
                                    <x-icon name="trash" class="w-4 h-4" />
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                    <td colspan="4" class="px-6 py-12 text-center">
                        <x-icon name="tags" class="mx-auto h-12 w-12 text-gray-300 dark:text-slate-600" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma tag encontrada</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando sua primeira tag.</p>
                        <div class="mt-6">
                            <a href="{{ blog_admin_route('tags.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                                <x-icon name="plus" class="w-4 h-4 mr-2" />
                                Nova Tag
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($tags->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
        {{ $tags->links() }}
    </div>
    @endif
</div>

<!-- Clean Unused Tags Button -->
@if(isset($estatisticas) && ($estatisticas['unused_tags'] ?? 0) > 0)
<div class="mt-6">
    <form action="{{ blog_admin_route('tags.clean-unused') }}" method="POST" class="inline-block">
        @csrf
        <button type="submit"
                onclick="return confirm('Tem certeza que deseja excluir todas as tags não utilizadas? Esta ação não pode ser desfeita.')"
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg shadow-red-500/20 transition-all transform hover:-translate-y-0.5">
            <x-icon name="trash-can-arrow-up" class="w-4 h-4 mr-2" />
            Limpar Tags Não Utilizadas ({{ $estatisticas['unused_tags'] }})
        </button>
    </form>
</div>
@endif
@endsection
