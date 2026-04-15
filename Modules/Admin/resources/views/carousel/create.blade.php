@extends($layout)

@section('title', 'Criar Slide do Carousel')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="plus" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Novo Slide</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route(user_can_access_admin_panel() ? 'admin.dashboard' : 'diretoria.dashboard') }}" class="hover:text-pink-600 dark:hover:text-pink-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <a href="{{ route($routePrefix.'.index') }}" class="hover:text-pink-600 dark:hover:text-pink-400 transition-colors italic">Carousel</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Criar</span>
            </nav>
        </div>

        <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 focus:ring-4 focus:ring-gray-100 dark:focus:ring-slate-700 transition-all">
            <x-icon name="arrow-left" class="w-5 h-5" style="solid" />
            Voltar
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <form action="{{ route($routePrefix.'.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 md:p-8 space-y-8">
                <!-- Main Info -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Título do Slide <span class="text-xs font-normal text-pink-500 ml-1">* (HTML Suportado)</span>
                            </label>
                            <div id="quill-title" class="quill-editor-wrapper bg-gray-50 dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-700 focus-within:border-pink-500 dark:focus-within:border-pink-500 focus-within:ring-1 focus-within:ring-pink-500 transition-all"></div>
                            <textarea id="title" name="title" class="hidden">{{ old('title') }}</textarea>
                            @error('title')
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Descrição / Texto <span class="text-xs font-normal text-slate-400 ml-1">(Opcional)</span>
                            </label>
                            <div id="quill-description" class="quill-editor-wrapper bg-gray-50 dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-700 focus-within:border-pink-500 dark:focus-within:border-pink-500 focus-within:ring-1 focus-within:ring-pink-500 transition-all min-h-[120px]"></div>
                            <textarea id="description" name="description" class="hidden">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="order" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Ordem de Exibição
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-icon name="list-bullet" class="w-5 h-5 text-gray-400" />
                                </div>
                                <input type="number"
                                       id="order"
                                       name="order"
                                       value="{{ old('order', \App\Models\CarouselSlide::max('order') + 1) }}"
                                       min="0"
                                       class="pl-10 w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg text-gray-900 dark:text-white focus:ring-pink-500 focus:border-pink-500 block p-2.5 transition-colors"
                                       placeholder="Ex: 1">
                            </div>
                            <p class="mt-1.5 text-xs text-slate-500">Menor número aparece primeiro.</p>
                            @error('order')
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="link" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Link de Destino
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-icon name="link" class="w-5 h-5 text-gray-400" />
                                    </div>
                                    <input type="url"
                                           id="link"
                                           name="link"
                                           value="{{ old('link') }}"
                                           class="pl-10 w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg text-gray-900 dark:text-white focus:ring-pink-500 focus:border-pink-500 block p-2.5 transition-colors"
                                           placeholder="https://...">
                                </div>
                            </div>
                            <div>
                                <label for="link_text" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Texto do Botão
                                </label>
                                <input type="text"
                                       id="link_text"
                                       name="link_text"
                                       value="{{ old('link_text', 'Saiba Mais') }}"
                                       class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg text-gray-900 dark:text-white focus:ring-pink-500 focus:border-pink-500 block p-2.5 transition-colors"
                                       placeholder="Ex: Ver Detalhes">
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="p-4 bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-dashed border-gray-300 dark:border-slate-700 hover:border-pink-400 dark:hover:border-pink-600 transition-colors group">
                            <label for="image" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors cursor-pointer">
                                Imagem de Fundo / Destaque
                            </label>

                            <input type="file"
                                   id="image"
                                   name="image"
                                   accept="image/*"
                                   onchange="previewImage(this)"
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 dark:file:bg-pink-900/30 dark:file:text-pink-300 transition-all cursor-pointer">

                            <div id="imagePreview" class="hidden mt-4 relative rounded-lg overflow-hidden border border-gray-200 dark:border-slate-700 shadow-sm">
                                <img id="previewImg" src="" alt="Preview" class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end p-2">
                                    <p class="text-white text-xs font-bold">Preview da Imagem</p>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><x-icon name="exclamation-circle" class="w-4 h-4" /> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Toggles -->
                <div class="flex flex-col sm:flex-row gap-6 pt-6 border-t border-gray-100 dark:border-slate-700">
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Publicar Imediatamente</span>
                    </label>

                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="show_image" value="1" class="sr-only peer" {{ old('show_image', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 dark:peer-focus:ring-pink-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-pink-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">Exibir Imagem no Slide</span>
                    </label>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-slate-900/50 border-t border-gray-100 dark:border-slate-700 flex items-center justify-end gap-3">
                <a href="{{ route($routePrefix.'.index') }}" class="px-5 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 focus:ring-4 focus:ring-pink-200 dark:focus:ring-pink-900">
                    <x-icon name="check" class="w-5 h-5" style="solid" />
                    Criar Slide
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
@vite(['resources/js/quill-editor.js'])
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endpush
@endsection
