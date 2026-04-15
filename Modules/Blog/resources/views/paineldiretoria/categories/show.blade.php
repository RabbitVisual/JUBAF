@extends('paineldiretoria::components.layouts.app')

@section('title', $category->name . ' - Categoria')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    @include('blog::paineldiretoria.partials.subnav', ['active' => 'categories'])

    @include('blog::paineldiretoria.partials.page-hero', [
        'kicker' => 'Categoria',
        'title' => $category->name,
        'lead' => 'Slug: ' . $category->slug . ' · Cor exibida nos chips do blog.',
        'iconName' => 'folder-tree',
        'crumbs' => [
            ['label' => 'Diretoria', 'url' => route('diretoria.dashboard')],
            ['label' => 'Blog', 'url' => blog_admin_route('index')],
            ['label' => 'Categorias', 'url' => blog_admin_route('categories.index')],
            ['label' => Str::limit($category->name, 32), 'url' => null],
        ],
        'actions' => view('blog::paineldiretoria.partials.hero-actions-category-show', ['category' => $category])->render(),
    ])

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Category Info -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações da Categoria</h3>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome</label>
                        <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $category->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $category->slug }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        @if($category->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                            Ativa
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                            Inativa
                        </span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ordem</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $category->sort_order }}</p>
                    </div>
                </div>

                @if($category->description)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição</label>
                    <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-slate-700 p-3 rounded-lg">
                        {{ $category->description }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Associated Posts -->
        @if($category->posts->count() > 0)
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Posts Recentes na Categoria</h3>
                <a href="{{ blog_admin_route('index') }}?category={{ $category->id }}"
                   class="text-sm text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                    Ver todos
                </a>
            </div>

            <div class="space-y-4">
                @foreach($category->posts->take(10) as $post)
                <div class="border border-gray-200 dark:border-slate-600 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                <a href="{{ blog_admin_route('show', $post->id) }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                    {{ $post->title }}
                                </a>
                            </h4>
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Não publicado' }}</span>
                                <span class="flex items-center">
                                    <x-icon name="eye" class="w-4 h-4 mr-1 opacity-50" />
                                    {{ $post->views_count }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ blog_admin_route('show', $post->id) }}"
                           class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:text-white hover:bg-blue-600 dark:text-blue-400 dark:hover:bg-blue-500 rounded-lg transition-all border border-blue-200 dark:border-blue-800 shadow-sm" title="Ver post">
                            <x-icon name="eye" class="w-5 h-5" />
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6 text-center">
            <x-icon name="newspaper" class="mx-auto h-12 w-12 text-gray-300 dark:text-slate-600 mb-4" />
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Nenhum post nesta categoria</h3>
            <p class="text-gray-600 dark:text-gray-400">
                Esta categoria ainda não possui posts associados.
            </p>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ações Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ blog_admin_route('categories.edit', $category->id) }}"
                   class="w-full flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                    <x-icon name="pen-to-square" class="w-4 h-4 mr-2" />
                    Editar Categoria
                </a>

                @if($category->posts_count == 0)
                <form action="{{ blog_admin_route('categories.destroy', $category->id) }}" method="POST"
                      onsubmit="return confirm('Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg shadow-red-500/20 transition-all transform hover:-translate-y-0.5">
                        <x-icon name="trash" class="w-4 h-4 mr-2" />
                        Excluir Categoria
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        @if(isset($estatisticas))
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Metadados</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total de Posts:</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $estatisticas['total_posts'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Publicados:</span>
                    <span class="font-bold text-green-600 dark:text-green-400">{{ $estatisticas['published_posts'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Rascunhos:</span>
                    <span class="font-bold text-yellow-600 dark:text-yellow-400">{{ $estatisticas['draft_posts'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Visualizações:</span>
                    <span class="font-bold text-purple-600 dark:text-purple-400">{{ number_format($estatisticas['total_views']) }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
@endsection
