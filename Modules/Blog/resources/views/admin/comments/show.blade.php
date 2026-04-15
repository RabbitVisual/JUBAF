@extends('layouts.app')

@section('title', 'Comentário - Blog Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 pb-4 border-b border-gray-200 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <x-icon name="comments" class="w-8 h-8 text-emerald-600 dark:text-emerald-500" />
                Comentário
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Blog</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('comments.index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Comentários</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900 dark:text-gray-100">Ver</li>
                </ol>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ blog_admin_route('comments.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" />
                Voltar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Comment Details -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Detalhes do Comentário</h3>

            <div class="space-y-6">
                <!-- Comment Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Conteúdo</label>
                    <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4 border border-gray-200 dark:border-slate-600">
                        <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $comment->content }}</p>
                    </div>
                </div>

                <!-- Comment Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    @if($comment->status === 'approved')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                        <x-icon name="circle-check" class="w-4 h-4 mr-2" />
                        Aprovado
                    </span>
                    @elseif($comment->status === 'pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                        <x-icon name="clock" class="w-4 h-4 mr-2" />
                        Pendente
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                        <x-icon name="circle-xmark" class="w-4 h-4 mr-2" />
                        Rejeitado
                    </span>
                    @endif
                </div>

                <!-- Author Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Autor</label>
                        <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4">
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $comment->author_display_name }}</p>
                            @if($comment->author_email)
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $comment->author_email }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Post Relacionado</label>
                        <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4">
                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                <a href="{{ blog_admin_route('show', $comment->post->id) }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                    {{ Str::limit($comment->post->title, 50) }}
                                </a>
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Status: {{ $comment->post->status === 'published' ? 'Publicado' : 'Rascunho' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data de Criação</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $comment->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Última Atualização</label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $comment->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ações Rápidas</h3>
            <div class="space-y-3">
                @if($comment->status === 'pending')
                <button onclick="approveComment({{ $comment->id }})"
                        class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                    <x-icon name="circle-check" class="w-4 h-4 mr-2" />
                    Aprovar Comentário
                </button>
                <button onclick="rejectComment({{ $comment->id }})"
                        class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                    <x-icon name="circle-xmark" class="w-4 h-4 mr-2" />
                    Rejeitar Comentário
                </button>
                @endif

                <a href="{{ blog_admin_route('show', $comment->post->id) }}"
                   class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    <x-icon name="eye" class="w-4 h-4 mr-2" />
                    Ver Post Relacionado
                </a>

                <form action="{{ blog_admin_route('comments.destroy', $comment->id) }}" method="POST"
                      onsubmit="return confirm('Tem certeza que deseja excluir este comentário? Esta ação não pode ser desfeita.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                        <x-icon name="trash" class="w-4 h-4 mr-2" />
                        Excluir Comentário
                    </button>
                </form>
            </div>
        </div>

        <!-- Comment Meta -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Metadados</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">ID:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $comment->id }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">IP do Autor:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $comment->author_ip ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">User Agent:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $comment->user_agent ? Str::limit($comment->user_agent, 50) : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function approveComment(commentId) {
    if (!confirm('Tem certeza que deseja aprovar este comentário?')) return;

    try {
        const response = await fetch(`/admin/blog/comentarios/${commentId}/aprovar`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    } catch (error) {
        alert('Erro ao aprovar comentário: ' + error.message);
    }
}

async function rejectComment(commentId) {
    if (!confirm('Tem certeza que deseja rejeitar este comentário?')) return;

    try {
        const response = await fetch(`/admin/blog/comentarios/${commentId}/rejeitar`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    } catch (error) {
        alert('Erro ao rejeitar comentário: ' + error.message);
    }
}
</script>
@endpush
