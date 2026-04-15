@extends('admin::layouts.admin')

@section('title', 'Blog - Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 pb-4 border-b border-gray-200 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <x-icon module="Blog" class="w-8 h-8 text-emerald-600 dark:text-emerald-500" />
                Gerenciar Blog
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900 dark:text-gray-100">Blog</li>
                </ol>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('blog.index') }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                <x-icon name="eye" class="w-4 h-4 mr-2" />
                Ver Blog
            </a>
            <a href="{{ blog_admin_route('create') }}"
               class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                <x-icon name="plus" class="w-4 h-4 mr-2" />
                Novo Post
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
@if(isset($estatisticas))
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                <x-icon name="newspaper" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Posts</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $estatisticas['total_posts'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg">
                <x-icon name="circle-check" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Publicados</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $estatisticas['published_posts'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-amber-100 dark:bg-amber-900/40 rounded-lg">
                <x-icon name="pen-to-square" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rascunhos</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $estatisticas['draft_posts'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-purple-100 dark:bg-purple-900/40 rounded-lg">
                <x-icon name="eye" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Visualizações</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($estatisticas['total_views'] ?? 0) }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="mb-8">
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ações Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ blog_admin_route('categories.index') }}"
               class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-all border border-transparent hover:border-emerald-200 group">
                <x-icon name="folder-tree" class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 mr-3 transition-colors" />
                <div>
                    <div class="font-medium text-gray-900 dark:text-gray-100">Categorias</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $estatisticas['total_categories'] ?? 0 }} categorias</div>
                </div>
            </a>

            <a href="{{ blog_admin_route('tags.index') }}"
               class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-all border border-transparent hover:border-emerald-200 group">
                <x-icon name="tags" class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 mr-3 transition-colors" />
                <div>
                    <div class="font-medium text-gray-900 dark:text-gray-100">Tags</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $estatisticas['total_tags'] ?? 0 }} tags</div>
                </div>
            </a>

            <a href="{{ blog_admin_route('comments.index') }}"
               class="flex items-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-all border border-transparent hover:border-emerald-200 group">
                <x-icon name="comments" class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 mr-3 transition-colors" />
                <div>
                    <div class="font-medium text-gray-900 dark:text-gray-100">Comentários</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $estatisticas['pending_comments'] ?? 0 }} pendentes</div>
                </div>
            </a>

            <button onclick="generateMonthlyReport()"
                    class="flex items-center p-4 bg-emerald-50/50 dark:bg-emerald-900/20 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-all border border-emerald-100/50 dark:border-emerald-900/30 group text-left">
                <x-icon name="file-chart-column" class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mr-3 group-hover:scale-110 transition-transform" />
                <div>
                    <div class="font-medium text-emerald-700 dark:text-emerald-300">Relatório Mensal</div>
                    <div class="text-sm text-emerald-600 dark:text-emerald-400">Gerar automaticamente</div>
                </div>
            </button>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="mb-6">
    <form method="GET" class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}"
                       placeholder="Título, conteúdo..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select id="status" name="status"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todos</option>
                    <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                    <option value="published" {{ ($filters['status'] ?? '') === 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="archived" {{ ($filters['status'] ?? '') === 'archived' ? 'selected' : '' }}>Arquivado</option>
                </select>
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoria</label>
                <select id="category" name="category"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ ($filters['category'] ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors">
                    Filtrar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Posts Table -->
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
            <thead class="bg-gray-50 dark:bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Post</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Categoria</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Autor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estatísticas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}"
                                 class="w-12 h-12 rounded-lg object-cover mr-4">
                            @else
                            <div class="w-12 h-12 bg-gray-100 dark:bg-slate-700 rounded-lg mr-4 flex items-center justify-center border border-gray-200 dark:border-slate-600">
                                <x-icon name="image" class="w-6 h-6 text-gray-400" />
                            </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <a href="{{ blog_admin_route('show', $post->id) }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                        {{ Str::limit($post->title, 50) }}
                                    </a>
                                </div>
                                @if($post->is_featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400 mt-1">
                                    Destaque
                                </span>
                                @endif
                                @if($post->auto_generated_from)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400 mt-1">
                                    Auto
                                </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                              style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }}">
                            {{ $post->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                        {{ $post->author->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($post->status === 'published')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                            Publicado
                        </span>
                        @elseif($post->status === 'draft')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                            Rascunho
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                            Arquivado
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex space-x-4">
                            <span class="flex items-center" title="Visualizações">
                                <x-icon name="eye" class="w-4 h-4 mr-1 text-gray-400" />
                                {{ $post->views()->count() }}
                            </span>
                            <span class="flex items-center" title="Comentários">
                                <x-icon name="comments" class="w-4 h-4 mr-1 text-gray-400" />
                                {{ $post->comments->count() }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            @if($post->status === 'published')
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                               class="p-2 bg-gray-100 dark:bg-slate-700 text-gray-600 hover:text-emerald-600 dark:text-gray-400 dark:hover:text-emerald-400 rounded-lg transition-colors border border-gray-200 dark:border-slate-600 shadow-sm" title="Ver post">
                                <x-icon name="eye" class="w-4 h-4" />
                            </a>
                            @endif
                            <a href="{{ blog_admin_route('edit', $post->id) }}"
                               class="p-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 hover:text-white hover:bg-emerald-600 dark:text-emerald-400 dark:hover:bg-emerald-500 rounded-lg transition-all border border-emerald-200 dark:border-emerald-800 shadow-sm" title="Editar">
                                <x-icon name="pen-to-square" class="w-4 h-4" />
                            </a>
                            <button type="button"
                                    onclick="confirmDelete({{ $post->id }}, '{{ $post->title }}')"
                                    class="p-2 bg-red-50 dark:bg-red-900/20 text-red-600 hover:text-white hover:bg-red-600 dark:text-red-400 dark:hover:bg-red-500 rounded-lg transition-all border border-red-200 dark:border-red-800 shadow-sm" title="Excluir">
                                <x-icon name="trash" class="w-4 h-4" />
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                    <td colspan="7" class="px-6 py-12 text-center">
                        <x-icon name="newspaper" class="mx-auto h-12 w-12 text-gray-300 dark:text-slate-600" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum post encontrado</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando seu primeiro post no blog.</p>
                        <div class="mt-6">
                            <a href="{{ blog_admin_route('create') }}"
                               class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                                <x-icon name="plus" class="w-4 h-4 mr-2" />
                                Novo Post
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection

<!-- Page Scripts -->
<script>
async function generateMonthlyReport() {
    if (!confirm('Deseja gerar o relatório mensal automático?')) return;

    try {
        const response = await fetch('/admin/blog/generate-monthly-report', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            alert('Relatório mensal gerado com sucesso!');
            window.location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    } catch (error) {
        alert('Erro ao gerar relatório: ' + error.message);
    }
}
</script>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" aria-hidden="true">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-slate-800">
        <div class="mt-3">
            <div class="flex items-center justify-center mb-4">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/40 mb-4">
                    <x-icon name="triangle-exclamation" class="h-8 w-8 text-red-600 dark:text-red-400" />
                </div>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white text-center mb-2" id="delete-modal-title">
                Confirmar Exclusão
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-4" id="delete-modal-message">
                Tem certeza que deseja excluir este post? Esta ação não pode ser desfeita.
            </p>
            <div class="flex justify-center space-x-4">
                <button id="cancel-delete" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-800 dark:text-gray-200 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button id="confirm-delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Excluir
                </button>
            </div>
        </div>
    </div>
</div>
