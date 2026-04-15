@extends('layouts.app')

@section('title', $post->title . ' - Blog Admin')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    @include('blog::paineldiretoria.partials.subnav', ['active' => 'posts'])

    @include('blog::paineldiretoria.partials.page-hero', [
        'kicker' => 'Detalhes do post',
        'title' => Str::limit($post->title, 72),
        'lead' => 'Resumo administrativo: categoria, autor, status e atalhos para o site.',
        'iconName' => 'newspaper',
        'crumbs' => [
            ['label' => 'Diretoria', 'url' => route('diretoria.dashboard')],
            ['label' => 'Blog', 'url' => blog_admin_route('index')],
            ['label' => Str::limit($post->title, 40), 'url' => null],
        ],
        'actions' => view('blog::paineldiretoria.partials.hero-actions-post-show', ['post' => $post])->render(),
    ])

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 mb-3">Resumo administrativo</p>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium"
                      style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }}">
                    {{ $post->category->name }}
                </span>
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
                <span class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $post->author->name }}
                </span>
                <span class="text-slate-300 dark:text-slate-600">·</span>
                <span class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Não publicado' }}
                </span>
            </div>
            @if($post->tags->count() > 0)
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 dark:bg-slate-600 text-gray-700 dark:text-gray-300">
                    {{ $tag->name }}
                </span>
                @endforeach
            </div>
            @endif
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/50 p-6 shadow-sm">
            @include('blog::public.partials.panel-post-read', [
                'post' => $post,
                'publicUrl' => route('blog.show', $post->slug),
            ])
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
</div>
@endsection
