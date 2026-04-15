@extends('layouts.app')

@section('title', 'Comentários - Blog Diretoria')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
        @include('blog::paineldiretoria.partials.subnav', ['active' => 'comments'])

        @include('blog::paineldiretoria.partials.page-hero', [
            'kicker' => 'Moderação',
            'title' => 'Comentários do blog',
            'lead' =>
                'Aprove ou rejeite mensagens enviadas pelos leitores. Filtre por status ou por post para focar na fila de pendências.',
            'iconName' => 'comments',
            'crumbs' => [
                ['label' => 'Diretoria', 'url' => route('diretoria.dashboard')],
                ['label' => 'Blog', 'url' => blog_admin_route('index')],
                ['label' => 'Comentários', 'url' => null],
            ],
            'actions' => view('blog::paineldiretoria.partials.hero-actions-comments-index')->render(),
        ])

        <div
            class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
            <form method="GET">
                <div
                    class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-6 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Filtros</h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Status do comentário e post de origem</p>
                </div>
                <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-3 md:items-end">
                    <div>
                        <label for="status"
                            class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="status" name="status"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-100">
                            <option value="">Todos</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente
                            </option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovado
                            </option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitado
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="post" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Post</label>
                        <select id="post" name="post"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-100">
                            <option value="">Todos os posts</option>
                            @foreach ($posts ?? [] as $post)
                                <option value="{{ $post->id }}" {{ request('post') == $post->id ? 'selected' : '' }}>
                                    {{ Str::limit($post->title, 40) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:from-emerald-700 hover:to-emerald-800">
                            Aplicar filtros
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <section
            class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
            <div
                class="flex flex-col gap-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Fila de comentários</h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Pré-visualização e ações rápidas</p>
                </div>
                @if (isset($comments))
                    <span
                        class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-200">
                        {{ $comments->total() }} {{ $comments->total() === 1 ? 'registro' : 'registros' }}
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/80">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Comentário</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Post</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Autor</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Data</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-slate-700 dark:bg-slate-800/80">
                        @forelse ($comments ?? [] as $comment)
                            <tr class="transition hover:bg-gray-50/80 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ Str::limit($comment->content, 100) }}
                                    </div>
                                    @if (strlen($comment->content) > 100)
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Continua...</div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        <a href="{{ blog_admin_route('show', $comment->post->id) }}"
                                            class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                            {{ Str::limit($comment->post->title, 40) }}
                                        </a>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $comment->author_display_name }}</div>
                                    @if ($comment->author_email)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->author_email }}
                                        </div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($comment->status === 'approved')
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                            Aprovado
                                        </span>
                                    @elseif ($comment->status === 'pending')
                                        <span
                                            class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                            Pendente
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                                            Rejeitado
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $comment->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-1">
                                        <a href="{{ blog_admin_route('comments.show', $comment->id) }}"
                                            class="rounded-xl p-2 text-slate-500 transition hover:bg-blue-50 hover:text-blue-600 dark:text-slate-400 dark:hover:bg-blue-950/40 dark:hover:text-blue-400"
                                            title="Ver">
                                            <x-icon name="eye" class="h-4 w-4" />
                                        </a>

                                        @if ($comment->status === 'pending')
                                            <button type="button" onclick="approveComment({{ $comment->id }})"
                                                class="rounded-xl p-2 text-slate-500 transition hover:bg-emerald-50 hover:text-emerald-600 dark:text-slate-400 dark:hover:bg-emerald-950/40 dark:hover:text-emerald-400"
                                                title="Aprovar">
                                                <x-icon name="circle-check" class="h-4 w-4" />
                                            </button>
                                            <button type="button" onclick="rejectComment({{ $comment->id }})"
                                                class="rounded-xl p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-600 dark:text-slate-400 dark:hover:bg-red-950/40 dark:hover:text-red-400"
                                                title="Rejeitar">
                                                <x-icon name="circle-xmark" class="h-4 w-4" />
                                            </button>
                                        @endif

                                        <form action="{{ blog_admin_route('comments.destroy', $comment->id) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este comentário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-xl p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-600 dark:text-slate-400 dark:hover:bg-red-950/40 dark:hover:text-red-400"
                                                title="Excluir">
                                                <x-icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <x-icon name="comments" class="mx-auto h-12 w-12 text-gray-300 dark:text-slate-600" />
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum comentário
                                        encontrado</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Os comentários aparecerão aqui
                                        quando forem enviados nos posts.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($comments) && $comments->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 dark:border-slate-700">
                    {{ $comments->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        const commentsBaseUrl = @json(url('/diretoria/blog/comentarios'));

        async function approveComment(commentId) {
            if (!confirm('Tem certeza que deseja aprovar este comentário?')) return;

            try {
                const response = await fetch(commentsBaseUrl + '/' + commentId + '/aprovar', {
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
                const response = await fetch(commentsBaseUrl + '/' + commentId + '/rejeitar', {
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
