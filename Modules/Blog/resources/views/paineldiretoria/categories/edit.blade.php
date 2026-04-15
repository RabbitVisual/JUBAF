@extends('layouts.app')

@section('title', 'Editar Categoria - Blog Admin')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    @include('blog::paineldiretoria.partials.subnav', ['active' => 'categories'])

    @include('blog::paineldiretoria.partials.page-hero', [
        'kicker' => 'Taxonomia',
        'title' => 'Editar categoria',
        'lead' => 'Atualize rótulo, cor e visibilidade. Posts já publicados mantêm o vínculo com esta categoria.',
        'iconName' => 'folder-tree',
        'crumbs' => [
            ['label' => 'Diretoria', 'url' => route('diretoria.dashboard')],
            ['label' => 'Blog', 'url' => blog_admin_route('index')],
            ['label' => 'Categorias', 'url' => blog_admin_route('categories.index')],
            ['label' => Str::limit($category->name, 28), 'url' => null],
        ],
        'actions' => view('blog::paineldiretoria.partials.hero-actions-category-edit', ['category' => $category])->render(),
    ])

<!-- Form -->
<div class="max-w-2xl">
    <form action="{{ blog_admin_route('categories.update', $category->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações da Categoria</h3>

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="Nome da categoria...">
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Slug (URL amigável)
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="deixe-vazio-para-gerar-automaticamente">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Deixe vazio para gerar automaticamente</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descrição
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                              placeholder="Descrição da categoria...">{{ old('description', $category->description) }}</textarea>
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cor
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="color" id="color" name="color" value="{{ old('color', $category->color ?? '#3B82F6') }}"
                               class="w-12 h-10 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="text" id="color_text" readonly
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-600 text-gray-900 dark:text-gray-100"
                               value="{{ old('color', $category->color ?? '#3B82F6') }}">
                    </div>
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ordem de Exibição
                    </label>
                    <input type="number" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $category->sort_order) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="0">
                </div>

                <!-- Active -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                        Categoria ativa
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                <div class="flex gap-3">
                    <button type="submit"
                            class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                        <x-icon name="save" class="w-4 h-4 inline mr-2" /> Salvar Alterações
                    </button>
                    <a href="{{ blog_admin_route('categories.index') }}"
                       class="px-6 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});

// Update color text input
document.getElementById('color').addEventListener('input', function() {
    document.getElementById('color_text').value = this.value;
});
</script>
@endpush
