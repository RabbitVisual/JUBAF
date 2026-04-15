@extends('admin::layouts.admin')

@section('title', $post->title . ' - Blog Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 pb-4 border-b border-gray-200 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <x-icon module="Blog" class="w-8 h-8 text-emerald-600 dark:text-emerald-500" />
                {{ $post->title }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Blog</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900 dark:text-gray-100 truncate">{{ Str::limit($post->title, 30) }}</li>
                </ol>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ blog_admin_route('index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" />
                Voltar
            </a>
            @if($post->status === 'published')
            <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/20">
                <x-icon name="eye" class="w-4 h-4 mr-2" />
                Ver Post
            </a>
            @endif
            <a href="{{ blog_admin_route('edit', $post->id) }}"
               class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold transition-all transform hover:-translate-y-0.5 shadow-lg shadow-emerald-500/20">
                <x-icon name="pen-to-square" class="w-4 h-4 mr-2" />
                Editar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Post Info -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações do Post</h3>

            <div class="space-y-4">
                <!-- Featured Image -->
                @if($post->featured_image)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imagem Destacada</label>
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}"
                         class="w-full max-w-md h-48 object-cover rounded-lg shadow-sm">
                </div>
                @endif

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium"
                              style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }}">
                            {{ $post->category->name }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        @if($post->status === 'published')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                            Publicado
                        </span>
                        @elseif($post->status === 'draft')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                            Rascunho
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                            Arquivado
                        </span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Autor</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $post->author->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Publicação</label>
                        <p class="text-gray-900 dark:text-gray-100">
                            {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Não publicado' }}
                        </p>
                    </div>
                </div>

                <!-- Excerpt -->
                @if($post->excerpt)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Resumo</label>
                    <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-slate-700 p-3 rounded-lg">
                        {{ $post->excerpt }}
                    </p>
                </div>
                @endif

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Conteúdo</label>
                    <div class="prose prose-sm max-w-none bg-gray-50 dark:bg-slate-700 p-4 rounded-lg">
                        {!! htmlspecialchars_decode($post->content) !!}
                    </div>
                </div>

                <!-- Tags -->
                @if($post->tags->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 dark:bg-slate-600 text-gray-700 dark:text-gray-300">
                            {{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        @if(isset($estatisticas))
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                <x-icon name="chart-simple" class="w-5 h-5 mr-2 text-emerald-600" />
                Estatísticas
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-100 dark:border-slate-700">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $estatisticas['total_views'] ?? 0 }}</div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Visualizações</div>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-100 dark:border-slate-700">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $estatisticas['total_comments'] ?? 0 }}</div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Comentários</div>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-100 dark:border-slate-700">
                    <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $estatisticas['pending_comments'] ?? 0 }}</div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Pendentes</div>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-100 dark:border-slate-700">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $post->reading_time ?? 0 }}</div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Min leitura</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Comments -->
        @if($comentariosRecentes && $comentariosRecentes->count() > 0)
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Comentários Recentes</h3>
                <a href="{{ blog_admin_route('comments.index') }}?post={{ $post->id }}"
                   class="text-sm text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                    Ver todos
                </a>
            </div>

            <div class="space-y-4">
                @foreach($comentariosRecentes as $comment)
                <div class="border border-gray-200 dark:border-slate-600 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-2">
                            <span class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $comment->author_display_name }}
                            </span>
                            @if($comment->status === 'pending')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                Pendente
                            </span>
                            @elseif($comment->status === 'approved')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                Aprovado
                            </span>
                            @endif
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $comment->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 text-sm">
                        {{ Str::limit($comment->content, 150) }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ações Rápidas</h3>
            <div class="space-y-3">
                @if($post->status === 'published')
                <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                   class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition-all shadow-md shadow-blue-500/20">
                    <x-icon name="eye" class="w-4 h-4 mr-2" />
                    Ver no Site
                </a>
                @endif

                <a href="{{ blog_admin_route('edit', $post->id) }}"
                   class="w-full flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold transition-all shadow-md shadow-emerald-500/20">
                    <x-icon name="pen-to-square" class="w-4 h-4 mr-2" />
                    Editar Post
                </a>

                @if($post->comments()->where('status', 'pending')->count() > 0)
                <a href="{{ blog_admin_route('comments.index') }}?post={{ $post->id }}&status=pending"
                   class="w-full flex items-center justify-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold transition-all shadow-md shadow-amber-500/20">
                    <x-icon name="comments" class="w-4 h-4 mr-2" />
                    Moderar Comentários
                </a>
                @endif

                <form action="{{ blog_admin_route('destroy', $post->id) }}" method="POST"
                      onsubmit="return confirm('Tem certeza que deseja excluir este post? Esta ação não pode ser desfeita.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition-all shadow-md shadow-red-500/20">
                        <x-icon name="trash" class="w-4 h-4 mr-2" />
                        Excluir Post
                    </button>
                </form>
            </div>
        </div>

        <!-- Post Meta -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Metadados</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Slug:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $post->slug }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Criado em:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $post->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Atualizado em:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $post->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($post->auto_generated_from)
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Gerado automaticamente de:</span>
                    <span class="text-blue-600 dark:text-blue-400">{{ $post->auto_generated_from }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
