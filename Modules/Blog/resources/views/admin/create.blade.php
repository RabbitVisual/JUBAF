@extends('layouts.app')

@section('title', 'Novo Post - Blog Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 pb-4 border-b border-gray-200 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <x-icon name="newspaper" style="duotone" class="w-8 h-8 text-emerald-600 dark:text-emerald-500" />
                Novo Post
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Blog</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900 dark:text-gray-100">Novo Post</li>
                </ol>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ blog_admin_route('index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" />
                Voltar
            </a>
        </div>
    </div>
</div>

<!-- Form -->
<form action="{{ blog_admin_route('store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações Básicas</h3>

                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Título *
                        </label>
                        <input type="text" id="title" name="title" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="Digite o título do post">
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Slug (URL amigável)
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400 text-sm">
                                {{ config('app.url') }}/blog/
                            </span>
                            <input type="text" id="slug" name="slug"
                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-r-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Resumo
                        </label>
                        <textarea id="excerpt" name="excerpt" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                  placeholder="Breve descrição do post..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Content Editor -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Conteúdo</h3>

                @include('blog::admin.partials.bible-assist')

                <div class="blog-editor-wrapper">
                    <div id="blog-content-editor" class="h-96" data-upload-url="{{ blog_admin_route('upload-image') }}"></div>
                    <input type="hidden" name="content" id="content">
                </div>
            </div>

            <!-- Media -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <x-icon name="images" class="w-5 h-5 mr-2 text-emerald-600" />
                    Mídia
                </h3>

                <div class="space-y-6">
                    <!-- Featured Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 font-bold">
                            Imagem Destacada
                        </label>
                        <input type="file" name="featured_image" accept="image/*"
                               class="block w-full text-sm text-slate-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-emerald-50 file:text-emerald-700
                                      hover:file:bg-emerald-100 dark:file:bg-slate-700 dark:file:text-emerald-400">
                    </div>

                    <!-- Gallery Images -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 font-bold">
                            Galeria de Imagens
                        </label>
                        <input type="file" name="gallery_images[]" multiple accept="image/*"
                               class="block w-full text-sm text-slate-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-emerald-50 file:text-emerald-700
                                      hover:file:bg-emerald-100 dark:file:bg-slate-700 dark:file:text-emerald-400">
                    </div>
                </div>
            </div>

            <!-- Attachments -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <x-icon name="paperclip" class="w-5 h-5 mr-2 text-emerald-600" />
                    Anexos e Documentos (PDF)
                </h3>
                <input type="file" name="attachments[]" multiple accept=".pdf"
                       class="block w-full text-sm text-slate-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-red-50 file:text-red-700
                              hover:file:bg-red-100 dark:file:bg-slate-700 dark:file:text-red-400">
            </div>

            <!-- SEO -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <x-icon name="magnifying-glass" class="w-5 h-5 mr-2 text-emerald-600" />
                    Otimização SEO
                </h3>

                <div class="space-y-4">
                    <!-- Meta Title -->
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Meta Título
                        </label>
                        <input type="text" id="meta_title" name="meta_title"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="Título personalizado para buscadores (opcional)">
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Meta Descrição
                        </label>
                        <textarea id="meta_description" name="meta_description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                  placeholder="Descrição curta para o Google..."></textarea>
                    </div>

                    <!-- OG Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 font-bold">
                            Imagem Social (Open Graph)
                        </label>
                        <input type="file" name="og_image" accept="image/*"
                               class="block w-full text-sm text-slate-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-blue-400">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Publish -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-emerald-200 dark:border-emerald-900/30 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <x-icon name="paper-plane" class="w-5 h-5 mr-2 text-emerald-600" />
                    Publicação
                </h3>

                <div class="space-y-4">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Status
                        </label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 appearance-none">
                            <option value="draft">Rascunho</option>
                            <option value="review">Em Revisão</option>
                            <option value="published">Publicado</option>
                            <option value="archived">Arquivado</option>
                        </select>
                    </div>

                    <!-- Published At -->
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Data de Publicação
                        </label>
                        <input type="datetime-local" id="published_at" name="published_at"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- Featured -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Post em destaque</span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                    <div class="flex flex-col gap-3">
                        <button type="submit"
                                class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                            <x-icon name="check" class="w-4 h-4 inline mr-2" /> Criar Post
                        </button>
                        <a href="{{ blog_admin_route('index') }}"
                           class="w-full px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors text-center">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Categoria -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <x-icon name="folder-tree" class="w-5 h-5 mr-2 text-emerald-600" />
                    Categoria
                </h3>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                        Categoria *
                    </label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        <option value="">Selecione...</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @include('blog::partials.post-tags-field', ['tags' => $tags])
        </div>
    </div>
</form>
@endsection

@push('scripts')
@vite('resources/js/blog-editor.js')

<script>
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});
</script>
@endpush
