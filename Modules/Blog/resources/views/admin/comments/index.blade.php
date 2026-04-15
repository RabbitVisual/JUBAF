@extends('admin::layouts.admin')

@section('title', 'Comentários - Blog Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 pb-4 border-b border-gray-200 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <x-icon name="comments" class="w-8 h-8 text-emerald-600 dark:text-emerald-500" />
                Comentários do Blog
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ blog_admin_route('index') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">Blog</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900 dark:text-gray-100">Comentários</li>
                </ol>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ blog_admin_route('index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" />
                Voltar ao Blog
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="mb-6">
    <form method="GET" class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select id="status" name="status"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovado</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                </select>
            </div>

            <div>
                <label for="post" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Post</label>
                <select id="post" name="post"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todos os posts</option>
                    @foreach($posts ?? [] as $post)
                    <option value="{{ $post->id }}" {{ request('post') == $post->id ? 'selected' : '' }}>
                        {{ Str::limit($post->title, 40) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors">
                    Filtrar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Comments Table -->
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
            <thead class="bg-gray-50 dark:bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Comentário</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Post</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Autor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                @forelse($comments ?? [] as $comment)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            {{ Str::limit($comment->content, 100) }}
                        </div>
                        @if(strlen($comment->content) > 100)
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Continua...</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            <a href="{{ blog_admin_route('show', $comment->post->id) }}" class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                {{ Str::limit($comment->post->title, 40) }}
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $comment->author_display_name }}</div>
                        @if($comment->author_email)
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->author_email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($comment->status === 'approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                            Aprovado
                        </span>
                        @elseif($comment->status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                            Pendente
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                            Rejeitado
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $comment->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ blog_admin_route('comments.show', $comment->id) }}"
                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Ver">
                                <x-icon name="eye" class="w-4 h-4" />
                            </a>

                            @if($comment->status === 'pending')
                            <button onclick="approveComment({{ $comment->id }})"
                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Aprovar">
                                <x-icon name="circle-check" class="w-4 h-4" />
                            </button>
                            <button onclick="rejectComment({{ $comment->id }})"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Rejeitar">
                                <x-icon name="circle-xmark" class="w-4 h-4" />
                            </button>
                            @endif

                            <form action="{{ blog_admin_route('comments.destroy', $comment->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Tem certeza que deseja excluir este comentário?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Excluir">
                                    <x-icon name="trash" class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <x-icon name="comments" class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum comentário encontrado</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Os comentários aparecerão aqui quando forem enviados nos posts.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(isset($comments) && $comments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
        {{ $comments->links() }}
    </div>
    @endif
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
