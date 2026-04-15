@extends('layouts.app')

@section('title', 'Categorias - Blog Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 pb-4 border-b border-gray-200 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <x-icon name="folder-tree" class="w-8 h-8 text-emerald-600 dark:text-emerald-500" />
                Categorias do Blog
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Blog</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900 dark:text-gray-100">Categorias</li>
                </ol>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ blog_admin_route('index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" />
                Voltar ao Blog
            </a>
            <a href="{{ blog_admin_route('categories.create') }}"
               class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                <x-icon name="plus" class="w-4 h-4 mr-2" />
                Nova Categoria
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
                <x-icon name="list-ul" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $estatisticas['total_categories'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg">
                <x-icon name="circle-check" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ativas</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $estatisticas['active_categories'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-slate-100 dark:bg-slate-700 rounded-lg">
                <x-icon name="circle-xmark" class="w-6 h-6 text-slate-500 dark:text-slate-400" />
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Inativas</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $estatisticas['inactive_categories'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Categories Table -->
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
            <thead class="bg-gray-50 dark:bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Categoria</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descrição</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Posts</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ordem</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-4"
                                 style="background-color: {{ $category->color }}20">
                                <div class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <a href="{{ blog_admin_route('categories.show', $category->id) }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                        {{ $category->name }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $category->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            {{ Str::limit($category->description, 60) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            {{ $category->posts_count }} posts
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($category->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                            Ativa
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                            Inativa
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $category->sort_order }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <button onclick="toggleStatus({{ $category->id }}, {{ $category->is_active ? 'false' : 'true' }})"
                                    class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:text-white hover:bg-blue-600 dark:text-blue-400 dark:hover:bg-blue-500 rounded-lg transition-all border border-blue-200 dark:border-blue-800 shadow-sm"
                                    title="{{ $category->is_active ? 'Desativar' : 'Ativar' }}">
                                @if($category->is_active)
                                <x-icon name="toggle-on" class="w-4 h-4" />
                                @else
                                <x-icon name="toggle-off" class="w-4 h-4" />
                                @endif
                            </button>
                            <a href="{{ blog_admin_route('categories.edit', $category->id) }}"
                               class="p-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 hover:text-white hover:bg-emerald-600 dark:text-emerald-400 dark:hover:bg-emerald-500 rounded-lg transition-all border border-emerald-200 dark:border-emerald-800 shadow-sm" title="Editar">
                                <x-icon name="pen-to-square" class="w-4 h-4" />
                            </a>
                            @if($category->posts_count == 0)
                            <form action="{{ blog_admin_route('categories.destroy', $category->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
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
                    <td colspan="6" class="px-6 py-12 text-center">
                        <x-icon name="folder-tree" class="mx-auto h-12 w-12 text-gray-300 dark:text-slate-600" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma categoria encontrada</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando sua primeira categoria.</p>
                        <div class="mt-6">
                            <a href="{{ blog_admin_route('categories.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                                <x-icon name="plus" class="w-4 h-4 mr-2" />
                                Nova Categoria
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
async function toggleStatus(categoryId, newStatus) {
    try {
        const response = await fetch(`/admin/blog/categorias/${categoryId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ is_active: newStatus })
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    } catch (error) {
        alert('Erro ao alterar status: ' + error.message);
    }
}
</script>
@endpush
