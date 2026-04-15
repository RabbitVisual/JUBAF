@extends('paineldiretoria::components.layouts.app')

@section('title', 'Blog - Diretoria')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
        @include('blog::paineldiretoria.partials.subnav', ['active' => 'posts'])

        @include('blog::paineldiretoria.partials.page-hero', [
            'kicker' => 'Conteúdo institucional',
            'title' => 'Gerenciar blog',
            'lead' =>
                'Publique notícias, edite rascunhos, organize por categoria e acompanhe métricas. O que estiver publicado aparece no blog público do site.',
            'iconName' => 'newspaper',
            'crumbs' => [
                ['label' => 'Diretoria', 'url' => route('diretoria.dashboard')],
                ['label' => 'Blog', 'url' => null],
            ],
            'actions' => view('blog::paineldiretoria.partials.hero-actions-posts-index')->render(),
        ])

        <div
            class="flex gap-4 rounded-2xl border border-sky-200/80 bg-sky-50/90 p-4 dark:border-sky-900/40 dark:bg-sky-950/30 md:items-center md:p-5">
            <span
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-200/80 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200">
                <x-icon name="circle-info" class="h-5 w-5" style="duotone" />
            </span>
            <div class="min-w-0 text-sm text-sky-950/90 dark:text-sky-100/90">
                <p class="font-semibold text-sky-900 dark:text-sky-100">Fluxo sugerido</p>
                <p class="mt-1 leading-relaxed text-sky-900/85 dark:text-sky-200/85">
                    Use os filtros para achar rascunhos ou arquivados. Posts publicados podem ser abertos no site pelo ícone de olho.
                    Categorias, tags e comentários têm áreas próprias no menu acima.
                </p>
            </div>
        </div>

        @if (isset($estatisticas))
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-blue-200/80 dark:border-slate-700 dark:bg-slate-800/80 dark:hover:border-blue-800/60">
                    <div
                        class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/10 blur-2xl transition group-hover:bg-blue-500/15">
                    </div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Total de posts
                    </p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                        {{ $estatisticas['total_posts'] ?? 0 }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Cadastrados no painel</p>
                </div>
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-emerald-200/80 dark:border-slate-700 dark:bg-slate-800/80 dark:hover:border-emerald-800/60">
                    <div
                        class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10 blur-2xl transition group-hover:bg-emerald-500/15">
                    </div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Publicados</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                        {{ $estatisticas['published_posts'] ?? 0 }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Visíveis no blog público</p>
                </div>
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-amber-200/80 dark:border-slate-700 dark:bg-slate-800/80 dark:hover:border-amber-800/60">
                    <div
                        class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-500/10 blur-2xl transition group-hover:bg-amber-500/15">
                    </div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Rascunhos</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                        {{ $estatisticas['draft_posts'] ?? 0 }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Ainda não publicados</p>
                </div>
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-violet-200/80 dark:border-slate-700 dark:bg-slate-800/80 dark:hover:border-violet-800/60">
                    <div
                        class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-violet-500/10 blur-2xl transition group-hover:bg-violet-500/15">
                    </div>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Visualizações
                    </p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                        {{ number_format($estatisticas['total_views'] ?? 0) }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Soma registrada nos posts</p>
                </div>
            </div>
        @endif

        @if (isset($estatisticas))
            <div
                class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div
                    class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-6 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Atalhos</h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Taxonomia, moderação e relatório mensal</p>
                </div>
                <div class="grid grid-cols-1 gap-3 p-6 md:grid-cols-2 lg:grid-cols-4">
                    <a href="{{ blog_admin_route('categories.index') }}"
                        class="flex items-center gap-3 rounded-2xl border border-gray-100 bg-gray-50/80 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-slate-600 dark:bg-slate-900/40 dark:hover:border-emerald-800/50">
                        <x-icon name="folder-tree" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                        <div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">Categorias</div>
                            <div class="text-xs text-gray-500 dark:text-slate-400">{{ $estatisticas['total_categories'] ?? 0 }}
                                cadastradas</div>
                        </div>
                    </a>
                    <a href="{{ blog_admin_route('tags.index') }}"
                        class="flex items-center gap-3 rounded-2xl border border-gray-100 bg-gray-50/80 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-slate-600 dark:bg-slate-900/40 dark:hover:border-emerald-800/50">
                        <x-icon name="tags" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                        <div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">Tags</div>
                            <div class="text-xs text-gray-500 dark:text-slate-400">{{ $estatisticas['total_tags'] ?? 0 }} no
                                sistema</div>
                        </div>
                    </a>
                    <a href="{{ blog_admin_route('comments.index') }}"
                        class="flex items-center gap-3 rounded-2xl border border-gray-100 bg-gray-50/80 p-4 transition hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-slate-600 dark:bg-slate-900/40 dark:hover:border-emerald-800/50">
                        <x-icon name="comments" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                        <div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">Comentários</div>
                            <div class="text-xs text-gray-500 dark:text-slate-400">{{ $estatisticas['pending_comments'] ?? 0 }}
                                pendentes</div>
                        </div>
                    </a>
                    <button type="button" onclick="generateMonthlyReport()"
                        class="flex w-full items-center gap-3 rounded-2xl border border-emerald-100 bg-emerald-50/80 p-4 text-left transition hover:bg-emerald-100 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:hover:bg-emerald-950/50">
                        <x-icon name="file-chart-column" class="h-5 w-5 text-emerald-700 dark:text-emerald-300" />
                        <div>
                            <div class="text-sm font-semibold text-emerald-900 dark:text-emerald-200">Relatório mensal</div>
                            <div class="text-xs text-emerald-700 dark:text-emerald-400">Gerar boletim automático</div>
                        </div>
                    </button>
                </div>
            </div>
        @endif

        <div
            class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
            <form method="GET">
                <div
                    class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-6 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Filtros</h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Refine por texto, status ou categoria</p>
                </div>
                <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-4 md:items-end">
                    <div>
                        <label for="search"
                            class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar</label>
                        <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}"
                            placeholder="Título, conteúdo..."
                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="status"
                            class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="status" name="status"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-100">
                            <option value="">Todos</option>
                            <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Rascunho
                            </option>
                            <option value="published" {{ ($filters['status'] ?? '') === 'published' ? 'selected' : '' }}>
                                Publicado</option>
                            <option value="archived" {{ ($filters['status'] ?? '') === 'archived' ? 'selected' : '' }}>
                                Arquivado</option>
                        </select>
                    </div>
                    <div>
                        <label for="category"
                            class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                        <select id="category" name="category"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 dark:border-slate-600 dark:bg-slate-700 dark:text-gray-100">
                            <option value="">Todas</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ ($filters['category'] ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
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
            class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80"
            aria-labelledby="posts-list-heading">
            <div
                class="flex flex-col gap-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 id="posts-list-heading" class="text-base font-semibold text-gray-900 dark:text-white">Posts</h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Listagem com ações rápidas</p>
                </div>
                <span
                    class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-200">
                    {{ $posts->total() }} {{ $posts->total() === 1 ? 'registro' : 'registros' }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/80">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Post</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Categoria</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Autor</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Estatísticas</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Data</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-slate-700 dark:bg-slate-800/80">
                        @forelse ($posts as $post)
                            <tr class="transition hover:bg-gray-50/80 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($post->featured_image)
                                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}"
                                                class="mr-4 h-12 w-12 rounded-xl object-cover ring-1 ring-gray-200 dark:ring-slate-600">
                                        @else
                                            <div
                                                class="mr-4 flex h-12 w-12 items-center justify-center rounded-xl border border-gray-200 bg-gray-100 dark:border-slate-600 dark:bg-slate-700">
                                                <x-icon name="image" class="h-6 w-6 text-gray-400" />
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <a href="{{ blog_admin_route('show', $post->id) }}"
                                                    class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                                    {{ Str::limit($post->title, 50) }}
                                                </a>
                                            </div>
                                            @if ($post->is_featured)
                                                <span
                                                    class="mt-1 inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/20 dark:text-amber-400">
                                                    Destaque
                                                </span>
                                            @endif
                                            @if ($post->auto_generated_from)
                                                <span
                                                    class="mt-1 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                                    Auto
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                        style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }}">
                                        {{ $post->category->name }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $post->author->name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($post->status === 'published')
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                            Publicado
                                        </span>
                                    @elseif ($post->status === 'draft')
                                        <span
                                            class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                            Rascunho
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                                            Arquivado
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex space-x-4">
                                        <span class="flex items-center" title="Visualizações">
                                            <x-icon name="eye" class="mr-1 h-4 w-4 text-gray-400" />
                                            {{ $post->views()->count() }}
                                        </span>
                                        <span class="flex items-center" title="Comentários">
                                            <x-icon name="comments" class="mr-1 h-4 w-4 text-gray-400" />
                                            {{ $post->comments->count() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-1">
                                        @if ($post->status === 'published')
                                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener"
                                                class="rounded-xl p-2 text-slate-500 transition hover:bg-emerald-50 hover:text-emerald-600 dark:text-slate-400 dark:hover:bg-emerald-950/40 dark:hover:text-emerald-400"
                                                title="Ver post">
                                                <x-icon name="eye" class="h-4 w-4" />
                                            </a>
                                        @endif
                                        <a href="{{ blog_admin_route('edit', $post->id) }}"
                                            class="rounded-xl p-2 text-slate-500 transition hover:bg-emerald-50 hover:text-emerald-600 dark:text-slate-400 dark:hover:bg-emerald-950/40 dark:hover:text-emerald-400"
                                            title="Editar">
                                            <x-icon name="pen-to-square" class="h-4 w-4" />
                                        </a>
                                        <button type="button"
                                            onclick="confirmDelete('{{ blog_admin_route('destroy', $post->id) }}', @json($post->title))"
                                            class="rounded-xl p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-600 dark:text-slate-400 dark:hover:bg-red-950/40 dark:hover:text-red-400"
                                            title="Excluir">
                                            <x-icon name="trash" class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <x-icon name="newspaper" class="mx-auto h-12 w-12 text-gray-300 dark:text-slate-600" />
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum post
                                        encontrado</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ajuste os filtros ou crie um novo
                                        post.</p>
                                    <div class="mt-6">
                                        <a href="{{ blog_admin_route('create') }}"
                                            class="inline-flex items-center rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:from-emerald-700 hover:to-emerald-800">
                                            <x-icon name="plus" class="mr-2 h-4 w-4" />
                                            Novo post
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($posts->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 dark:border-slate-700">
                    {{ $posts->links() }}
                </div>
            @endif
        </section>

        <div id="delete-modal"
            class="fixed inset-0 z-50 hidden h-full w-full overflow-y-auto bg-gray-600 bg-opacity-50"
            aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
            <div class="relative top-20 mx-auto w-96 rounded-lg border bg-white p-5 shadow-lg dark:bg-slate-800">
                <div class="mt-3">
                    <div class="mb-4 flex items-center justify-center">
                        <div
                            class="mx-auto mb-4 flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/40">
                            <x-icon name="triangle-exclamation" class="h-8 w-8 text-red-600 dark:text-red-400" />
                        </div>
                    </div>
                    <h3 class="mb-2 text-center text-lg font-medium text-gray-900 dark:text-white" id="delete-modal-title">
                        Confirmar exclusão
                    </h3>
                    <p class="mb-4 text-center text-sm text-gray-500 dark:text-gray-400" id="delete-modal-message">
                        Tem certeza que deseja excluir este post? Esta ação não pode ser desfeita.
                    </p>
                    <div class="flex justify-center space-x-4">
                        <button type="button" id="cancel-delete"
                            class="rounded-lg bg-gray-300 px-4 py-2 text-gray-800 transition-colors hover:bg-gray-400 dark:bg-slate-600 dark:text-gray-200 dark:hover:bg-slate-500">
                            Cancelar
                        </button>
                        <button type="button" id="confirm-delete"
                            class="rounded-lg bg-red-600 px-4 py-2 text-white transition-colors hover:bg-red-700">
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let pendingDeleteUrl = '';

        async function generateMonthlyReport() {
            if (!confirm('Deseja gerar o relatório mensal automático?')) return;

            try {
                const response = await fetch(@json(blog_admin_route('generate-monthly-report')), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('Relatório mensal gerado com sucesso!');
                    window.location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            } catch (error) {
                alert('Erro ao gerar relatório: ' + error.message);
            }
        }

        function confirmDelete(url, title) {
            pendingDeleteUrl = url;
            const msg = document.getElementById('delete-modal-message');
            if (msg) {
                msg.textContent = 'Tem certeza que deseja excluir o post "' + title +
                    '"? Esta ação não pode ser desfeita.';
            }
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('delete-modal');
            const cancel = document.getElementById('cancel-delete');
            const confirmBtn = document.getElementById('confirm-delete');

            if (cancel) {
                cancel.addEventListener('click', function() {
                    modal.classList.add('hidden');
                    pendingDeleteUrl = '';
                });
            }
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    if (!pendingDeleteUrl) return;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = pendingDeleteUrl;
                    const csrf = document.querySelector('meta[name="csrf-token"]').content;
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrf;
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                });
            }
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        pendingDeleteUrl = '';
                    }
                });
            }
        });
    </script>
@endpush
