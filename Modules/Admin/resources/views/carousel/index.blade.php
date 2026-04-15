@extends($layout)

@section('title', 'Gerenciar Carousel')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="photo" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Carousel</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route(user_can_access_admin_panel() ? 'admin.dashboard' : 'diretoria.dashboard') }}" class="hover:text-pink-600 dark:hover:text-pink-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Mídia e Destaques</span>
            </nav>
        </div>

        <div class="flex items-center gap-3">
            <!-- Toggle Carousel -->
            <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status:</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="carouselToggle" class="sr-only peer" {{ $isEnabled ? 'checked' : '' }} data-toggle-route="{{ route($routePrefix.'.toggle') }}">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 dark:peer-focus:ring-pink-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-pink-600"></div>
                    <span class="ml-2 text-xs font-bold text-gray-700 dark:text-gray-300 min-w-[60px]" id="carouselStatus">{{ $isEnabled ? 'ATIVADO' : 'DESATIVADO' }}</span>
                </label>
            </div>

            <a href="{{ route($routePrefix.'.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 focus:ring-4 focus:ring-pink-200 dark:focus:ring-pink-900">
                <x-icon name="plus" class="w-5 h-5" style="solid" />
                Novo Slide
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <!-- Total Slides -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/60 relative overflow-hidden group hover:border-pink-200 dark:hover:border-pink-800 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <x-icon name="photo" class="w-24 h-24 text-pink-600" style="duotone" />
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Total de Slides</p>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">
                    {{ $slides->count() }}
                </h3>
                <p class="text-xs font-bold text-pink-600 dark:text-pink-400 mt-2 flex items-center gap-1">
                    <x-icon name="check-circle" class="w-4 h-4" style="solid" />
                    Cadastrados
                </p>
            </div>
        </div>

        <!-- Active Slides -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/60 relative overflow-hidden group hover:border-pink-200 dark:hover:border-pink-800 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <x-icon name="eye" class="w-24 h-24 text-pink-600" style="duotone" />
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Slides Ativos</p>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">
                    {{ $slides->where('is_active', true)->count() }}
                </h3>
                <p class="text-xs font-bold text-slate-500 mt-2 flex items-center gap-1">
                    <x-icon name="eye" class="w-4 h-4" style="solid" />
                    Visíveis na Home
                </p>
            </div>
        </div>

        <!-- With Images -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/60 relative overflow-hidden group hover:border-pink-200 dark:hover:border-pink-800 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <x-icon name="camera" class="w-24 h-24 text-pink-600" style="duotone" />
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Com Imagens</p>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">
                    {{ $slides->where('show_image', true)->whereNotNull('image')->count() }}
                </h3>
                <p class="text-xs font-bold text-slate-500 mt-2 flex items-center gap-1">
                    <x-icon name="photo" class="w-4 h-4" style="solid" />
                    Configurados com mídia
                </p>
            </div>
        </div>
    </div>

    <!-- Slides List -->
    @if($slides->count() > 0)
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden font-sans">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Galeria de Slides</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Arraste para reordenar a exibição</p>
                </div>
                <div class="bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                    {{ $slides->count() }} Items
                </div>
            </div>

            <div id="slidesList" class="divide-y divide-gray-100 dark:divide-slate-700" data-reorder-route="{{ route($routePrefix.'.reorder') }}">
                @foreach($slides as $slide)
                    <div class="slide-item p-4 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group" data-id="{{ $slide->id }}">
                        <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                            <!-- Drag Handle -->
                            <div class="hidden md:flex flex-shrink-0 cursor-move drag-handle p-2 rounded-lg text-slate-300 hover:text-pink-500 hover:bg-pink-50 dark:hover:bg-pink-900/20 transition-colors">
                                <x-icon name="bars" class="w-6 h-6" />
                            </div>

                            <!-- Image Preview -->
                            <div class="relative flex-shrink-0 w-full md:w-48 h-32 md:h-28 rounded-xl overflow-hidden border border-gray-200 dark:border-slate-700 bg-gray-100 dark:bg-slate-900 shadow-sm group-hover:shadow-md transition-all">
                                @if($slide->image && $slide->show_image)
                                    <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                        <x-icon name="photo" class="w-8 h-8 mb-2" style="duotone" />
                                        <span class="text-[10px] uppercase font-bold tracking-wider">Sem Imagem</span>
                                    </div>
                                @endif

                                <div class="absolute top-2 right-2 flex flex-col gap-1">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-white/90 dark:bg-slate-800/90 backdrop-blur text-xs font-bold text-slate-600 dark:text-slate-300 shadow-sm">
                                        {{ $slide->order }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0 py-1">
                                <div class="flex items-start justify-between gap-4 mb-2">
                                    <div>
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1 line-clamp-1">
                                            @if($slide->title)
                                                {{ Str::limit(strip_tags($slide->title), 80) }}
                                            @else
                                                <span class="italic text-slate-400">Sem título definido</span>
                                            @endif
                                        </h4>
                                        @if($slide->description)
                                            <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                                {{ Str::limit(strip_tags($slide->description), 120) }}
                                            </p>
                                        @else
                                            <p class="text-xs text-slate-400 italic">Sem descrição adicional.</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3 mt-3">
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $slide->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400' }}">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $slide->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></div>
                                        {{ $slide->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>

                                    @if($slide->link)
                                        <span class="flex items-center gap-1 text-xs text-pink-600 dark:text-pink-400 font-medium bg-pink-50 dark:bg-pink-900/20 px-2.5 py-1 rounded-md">
                                            <x-icon name="link" class="w-3 h-3" />
                                            Link Externo
                                        </span>
                                    @endif

                                    @if($slide->title && strip_tags($slide->title) !== $slide->title)
                                        <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-slate-800 px-2.5 py-1 rounded-md">
                                            <x-icon name="code-bracket" class="w-3 h-3" />
                                            HTML Rico
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 flex-shrink-0 border-l border-gray-100 dark:border-slate-700 pl-4 md:pl-6 md:h-20">
                                <a href="{{ route($routePrefix.'.edit', $slide) }}"
                                   class="p-2 text-slate-500 hover:text-pink-600 dark:text-slate-400 dark:hover:text-pink-400 hover:bg-pink-50 dark:hover:bg-pink-900/20 rounded-lg transition-colors"
                                   title="Editar">
                                    <x-icon name="pencil-square" class="w-5 h-5" style="duotone" />
                                </a>

                                <button type="button"
                                        onclick="confirmDelete('{{ route($routePrefix.'.destroy', $slide) }}')"
                                        class="p-2 text-slate-500 hover:text-red-600 dark:text-slate-400 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                        title="Excluir">
                                    <x-icon name="trash" class="w-5 h-5" style="duotone" />
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-12 text-center">
            <div class="w-20 h-20 bg-pink-50 dark:bg-pink-900/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <x-icon name="photo" class="w-10 h-10 text-pink-400" style="duotone" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Nenhum slide cadastrado</h3>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8">
                Adicione slides ao carousel para exibir destaques na página inicial do seu site.
            </p>
            <a href="{{ route($routePrefix.'.create') }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-pink-600 hover:bg-pink-700 rounded-xl transition-colors shadow-lg shadow-pink-200 dark:shadow-none">
                <x-icon name="plus" class="w-5 h-5" style="solid" />
                Criar Primeiro Slide
            </a>
        </div>
    @endif
</div>

<!-- Scripts for Delete Modal & Drag/Drop -->
@push('scripts')
@vite(['resources/js/carousel-admin.js'])
<script>
    function confirmDelete(url) {
        if (confirm('Tem certeza que deseja excluir este slide?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection
