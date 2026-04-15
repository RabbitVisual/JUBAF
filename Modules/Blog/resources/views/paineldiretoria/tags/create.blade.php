@extends('paineldiretoria::components.layouts.app')

@section('title', 'Nova Tag - Blog Admin')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    @include('blog::paineldiretoria.partials.subnav', ['active' => 'tags'])

    @include('blog::paineldiretoria.partials.page-hero', [
        'kicker' => 'Taxonomia',
        'title' => 'Nova tag',
        'lead' => 'Tags conectam posts por palavras-chave. O slug é gerado automaticamente a partir do nome.',
        'iconName' => 'tags',
        'crumbs' => [
            ['label' => 'Diretoria', 'url' => route('diretoria.dashboard')],
            ['label' => 'Blog', 'url' => blog_admin_route('index')],
            ['label' => 'Tags', 'url' => blog_admin_route('tags.index')],
            ['label' => 'Nova', 'url' => null],
        ],
        'actions' => view('blog::paineldiretoria.partials.hero-actions-tag-form')->render(),
    ])

<!-- Form -->
<div class="max-w-2xl">
    <form action="{{ blog_admin_route('tags.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações da Tag</h3>

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome *
                    </label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Slug (URL amigável)
                    </label>
                    <input type="text" id="slug" name="slug"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Deixe vazio para gerar automaticamente</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descrição
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                <div class="flex gap-3">
                    <button type="submit"
                            class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                        <x-icon name="save" class="w-4 h-4 inline mr-2" /> Criar Tag
                    </button>
                    <a href="{{ blog_admin_route('tags.index') }}"
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
</script>
@endpush
