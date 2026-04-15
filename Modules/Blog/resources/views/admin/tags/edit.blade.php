@extends('layouts.app')

@section('title', 'Editar Tag - Blog Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 pb-4 border-b border-gray-200 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <x-icon name="tags" class="w-8 h-8 text-emerald-600 dark:text-emerald-500" />
                Editar Tag
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Blog</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('tags.index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Tags</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900 dark:text-gray-100">Editar</li>
                </ol>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ blog_admin_route('tags.show', $tag->id) }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/20">
                <x-icon name="eye" class="w-4 h-4 mr-2" />
                Ver Tag
            </a>
            <a href="{{ blog_admin_route('tags.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" />
                Voltar
            </a>
        </div>
    </div>
</div>

<!-- Form -->
<div class="max-w-2xl">
    <form action="{{ blog_admin_route('tags.update', $tag->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações da Tag</h3>

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome *
                    </label>
                    <input type="text" id="name" name="name" value="{{ $tag->name }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Slug (URL amigável)
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ $tag->slug }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Deixe vazio para gerar automaticamente</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descrição
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ $tag->description }}</textarea>
                </div>
            </div>

            <!-- Statistics -->
            @if(isset($tag))
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Estatísticas</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Posts associados:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $tag->posts_count }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Criada em:</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $tag->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                <div class="flex gap-3">
                    <button type="submit"
                            class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                        <x-icon name="save" class="w-4 h-4 inline mr-2" /> Atualizar Tag
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
