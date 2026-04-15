@extends('layouts.app')

@section('title', 'Editar: ' . $post->title . ' - Blog Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 pb-4 border-b border-gray-200 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <x-icon name="newspaper" style="duotone" class="w-8 h-8 text-emerald-600 dark:text-emerald-500" />
                Editar Post
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Blog</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('show', $post->id) }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">{{ Str::limit($post->title, 20) }}</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900 dark:text-gray-100">Editar</li>
                </ol>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ blog_admin_route('show', $post->id) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" />
                Voltar
            </a>
        </div>
    </div>
</div>

<!-- Form -->
<form action="{{ blog_admin_route('update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

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
                        <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
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
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $post->slug) }}"
                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-r-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Resumo
                        </label>
                        <textarea id="excerpt" name="excerpt" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Content Editor -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Conteúdo</h3>

                @include('blog::admin.partials.bible-assist')

                <div class="blog-editor-wrapper">
                    <div id="blog-content-editor" class="h-96" data-upload-url="{{ blog_admin_route('upload-image') }}">{!! old('content', $post->content) !!}</div>
                    <input type="hidden" name="content" id="content" value="{{ old('content', $post->content) }}">
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Imagem Destacada
                        </label>

                        @if($post->featured_image)
                        <div class="mb-4 relative w-48 h-32 rounded-lg overflow-hidden border border-gray-200 dark:border-slate-600 group">
                            <img src="{{ Storage::url($post->featured_image) }}" class="object-cover w-full h-full">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button type="button" onclick="openPrivacyEditor('{{ Storage::url($post->featured_image) }}')"
                                        class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 shadow-lg transform hover:scale-110 transition-all" title="Censurar/Editar (LGPD)">
                                    <x-icon name="shield-check" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                        @endif

                        <input type="file" name="featured_image" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-slate-700 dark:file:text-emerald-400">
                    </div>

                    <!-- Gallery Images -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 font-bold">
                            Galeria de Imagens
                        </label>

                        @if($post->gallery_images)
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                            @foreach($post->gallery_images as $img)
                            <div class="relative aspect-square rounded-lg overflow-hidden border border-gray-200 dark:border-slate-600 group shadow-sm">
                                <img src="{{ Storage::url($img) }}" class="object-cover w-full h-full">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <button type="button" onclick="openPrivacyEditor('{{ Storage::url($img) }}')"
                                            class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 shadow-lg transform hover:scale-110 transition-all" title="Censurar/Editar (LGPD)">
                                        <x-icon name="shield-check" class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <input type="file" name="gallery_images[]" multiple accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-slate-700 dark:file:text-emerald-400">
                    </div>
                </div>
            </div>

            <!-- Attachments -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <x-icon name="paperclip" class="w-5 h-5 mr-2 text-emerald-600" />
                    Anexos e Documentos (PDF)
                </h3>

                <div class="space-y-4">
                    @if($post->attachments)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        @foreach($post->attachments as $att)
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-slate-600">
                            <x-icon name="file-pdf" class="w-5 h-5 text-red-500 mr-3" />
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $att['name'] }}</div>
                                <div class="text-[10px] text-gray-500 uppercase">Documento PDF</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <input type="file" name="attachments[]" multiple accept=".pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>
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
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}"
                               placeholder="Título para motores de busca"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Meta Descrição
                        </label>
                        <textarea id="meta_description" name="meta_description" rows="3"
                                  placeholder="Descrição curta para o Google..."
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('meta_description', $post->meta_description) }}</textarea>
                    </div>

                    <!-- OG Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 font-bold">
                            Imagem Social (Open Graph)
                        </label>

                        @if($post->og_image)
                        <div class="mb-3">
                            <img src="{{ Storage::url($post->og_image) }}" class="h-24 w-auto rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm">
                        </div>
                        @else
                        <div class="mb-3 p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg text-xs text-gray-500 border border-dashed border-gray-300 dark:border-slate-600">
                            Nenhuma imagem social definida. Usará a destacada por padrão.
                        </div>
                        @endif

                        <input type="file" name="og_image" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-blue-400">
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
                        <div class="relative">
                            <select id="status" name="status"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 appearance-none">
                                <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Rascunho</option>
                                <option value="review" {{ $post->status == 'review' ? 'selected' : '' }}>Em Revisão</option>
                                <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Publicado</option>
                                <option value="archived" {{ $post->status == 'archived' ? 'selected' : '' }}>Arquivado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Published At -->
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Data de Publicação
                        </label>
                        <input type="datetime-local" id="published_at" name="published_at"
                               value="{{ $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '' }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- Featured -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ $post->is_featured ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Post em destaque</span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                    <div class="flex flex-col gap-3">
                        <button type="submit"
                                class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5">
                            <x-icon name="save" class="w-4 h-4 inline mr-2" /> Salvar Alterações
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
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        @include('blog::partials.post-tags-field', ['tags' => $tags, 'post' => $post])
    </div>
</div>
</form>

<!-- Privacy Editor Modal -->
<div id="privacyEditorModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm flex items-center justify-center p-4">
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-5xl w-full flex flex-col max-h-[95vh] overflow-hidden">
    <div class="p-4 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-900/50">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                <x-icon name="shield-check" class="w-6 h-6 text-emerald-600" />
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Editor de Privacidade</h3>
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Conformidade LGPD - Proteção de Dados</p>
            </div>
        </div>
        <button onclick="closePrivacyEditor()" class="text-gray-400 hover:text-red-500 transition-colors">
            <x-icon name="xmark" class="w-7 h-7" />
        </button>
    </div>

    <div class="flex-1 overflow-auto p-8 bg-slate-100 dark:bg-slate-900 flex justify-center items-center relative" id="canvas-container">
        <canvas id="editorCanvas" class="max-w-full shadow-2xl rounded-lg bg-white dark:bg-slate-800 cursor-crosshair"></canvas>
    </div>

    <div class="p-6 border-t border-gray-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center bg-white dark:bg-slate-800 gap-4">
        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
            <x-icon name="circle-info" class="w-4 h-4 text-blue-500" />
            <span>Arraste para selecionar a área de censura na imagem.</span>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="applyBlur()" class="group px-4 py-2 bg-amber-100 hover:bg-amber-600 text-amber-700 hover:text-white rounded-lg font-bold flex items-center transition-all border border-amber-200">
                <x-icon name="droplet" class="w-4 h-4 mr-2 group-hover:animate-pulse" /> Borrar
            </button>
            <button type="button" onclick="applyPixelate()" class="group px-4 py-2 bg-blue-100 hover:bg-blue-600 text-blue-700 hover:text-white rounded-lg font-bold flex items-center transition-all border border-blue-200">
                <x-icon name="border-none" class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform" /> Pixelar
            </button>
            <div class="w-px h-8 bg-gray-200 dark:bg-slate-700 mx-1"></div>
            <button type="button" onclick="saveRedaction()" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/20 flex items-center transform hover:scale-105 transition-all">
                <x-icon name="floppy-disk" class="w-4 h-4 mr-2" /> Salvar Edição
            </button>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
@vite('resources/js/blog-editor.js')

<script>
// Privacy Editor Logic
// Privacy Editor Logic
let canvas, ctx;
let isDrawing = false;
let startX, startY, currentX, currentY;
let currentImageSrc = '';
let imgObj = new Image();

function openPrivacyEditor(src) {
    currentImageSrc = src;
    document.getElementById('privacyEditorModal').classList.remove('hidden');

    canvas = document.getElementById('editorCanvas');
    ctx = canvas.getContext('2d');

    imgObj.onload = function() {
        canvas.width = imgObj.width;
        canvas.height = imgObj.height;
        ctx.drawImage(imgObj, 0, 0);
    };
    imgObj.src = src;

    // Mouse Events for Selection
    canvas.onmousedown = function(e) {
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        startX = (e.clientX - rect.left) * scaleX;
        startY = (e.clientY - rect.top) * scaleY;
        isDrawing = true;
    };

    canvas.onmousemove = function(e) {
        if (!isDrawing) return;
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        currentX = (e.clientX - rect.left) * scaleX;
        currentY = (e.clientY - rect.top) * scaleY;
        ctx.drawImage(imgObj, 0, 0); // Redraw image
        ctx.strokeStyle = 'red';
        ctx.lineWidth = 2;
        ctx.strokeRect(startX, startY, currentX - startX, currentY - startY);
    };

    canvas.onmouseup = function() {
        isDrawing = false;
    };
}

function closePrivacyEditor() {
    document.getElementById('privacyEditorModal').classList.add('hidden');
}

function applyPixelate() {
    if (startX === undefined) return;
    const w = currentX - startX;
    const h = currentY - startY;
    const size = 15; // Pixel size
    ctx.drawImage(imgObj, 0, 0); // Redraw original image to clear selection box
    const tempCanvas = document.createElement('canvas');
    const tCtx = tempCanvas.getContext('2d');
    tempCanvas.width = Math.abs(w);
    tempCanvas.height = Math.abs(h);
    tCtx.drawImage(canvas, startX, startY, w, h, 0, 0, Math.abs(w)/size, Math.abs(h)/size);
    ctx.imageSmoothingEnabled = false;
    ctx.drawImage(tempCanvas, 0, 0, Math.abs(w)/size, Math.abs(h)/size, startX, startY, w, h);
    ctx.imageSmoothingEnabled = true;
    imgObj.src = canvas.toDataURL(); // Update imgObj for sequential edits
}

function applyBlur() {
    if (startX === undefined) return;
    const w = currentX - startX;
    const h = currentY - startY;
    ctx.drawImage(imgObj, 0, 0);
    // Simulating blur with a semi-transparent rectangle or fill if filter is tricky
    // Actually using black redact box for simplicity or actual blur if supported
    ctx.filter = 'blur(15px)';
    ctx.drawImage(canvas, startX, startY, w, h, startX, startY, w, h);
    ctx.filter = 'none';
    imgObj.src = canvas.toDataURL();
}

function saveRedaction() {
    const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
    fetch('{{ route("admin.blog.redact-image") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            image_path: currentImageSrc,
            image_data: dataUrl
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Imagem protegida com sucesso!');
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Erro de conexão ao salvar.');
    });
}
</script>
@endpush
