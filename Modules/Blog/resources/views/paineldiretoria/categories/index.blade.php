@extends('layouts.app')

@section('title', 'Categorias - Blog Diretoria')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
        @include('blog::paineldiretoria.partials.subnav', ['active' => 'categories'])

        @include('blog::paineldiretoria.partials.page-hero', [
            'kicker' => 'Taxonomia',
            'title' => 'Categorias do blog',
            'lead' =>
                'Agrupe posts por temas, defina cores e ordem de exibição. Categorias inativas não deixam de existir, apenas deixam de ser sugeridas em novos posts.',
            'iconName' => 'folder-tree',
            'crumbs' => [
                ['label' => 'Diretoria', 'url' => route('diretoria.dashboard')],
                ['label' => 'Blog', 'url' => blog_admin_route('index')],
                ['label' => 'Categorias', 'url' => null],
            ],
            'actions' => view('blog::paineldiretoria.partials.hero-actions-categories-index')->render(),
        ])

        <div
            class="flex gap-4 rounded-2xl border border-sky-200/80 bg-sky-50/90 p-4 dark:border-sky-900/40 dark:bg-sky-950/30 md:items-center md:p-5">
            <span
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-200/80 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200">
                <x-icon name="circle-info" class="h-5 w-5" style="duotone" />
            </span>
            <div class="min-w-0 text-sm text-sky-950/90 dark:text-sky-100/90">
                <p class="font-semibold text-sky-900 dark:text-sky-100">Dica</p>
                <p class="mt-1 leading-relaxed text-sky-900/85 dark:text-sky-200/85">
                    Só é possível excluir categorias sem posts vinculados. Use o interruptor para ativar ou desativar sem
                    apagar o histórico.
                </p>
            </div>
        </div>

        @if (isset($estatisticas))
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-blue-200/80 dark:border-slate-700 dark:bg-slate-800/80">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Total</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                        {{ $estatisticas['total_categories'] ?? 0 }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Cadastradas</p>
                </div>
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-emerald-200/80 dark:border-slate-700 dark:bg-slate-800/80">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Ativas</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                        {{ $estatisticas['active_categories'] ?? 0 }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Disponíveis para novos posts</p>
                </div>
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-slate-200/80 dark:border-slate-700 dark:bg-slate-800/80">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Inativas
                    </p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                        {{ $estatisticas['inactive_categories'] ?? 0 }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Ocultas na seleção</p>
                </div>
            </div>
        @endif

        <section
            class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
            <div
                class="flex flex-col gap-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Lista de categorias</h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Cor, slug, posts e status</p>
                </div>
                <span
                    class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-200">
                    {{ $categories->total() }} {{ $categories->total() === 1 ? 'item' : 'itens' }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/80">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Categoria</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Descrição</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Posts</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Ordem</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-slate-700 dark:bg-slate-800/80">
                        @forelse ($categories as $category)
                            <tr class="transition hover:bg-gray-50/80 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="mr-4 flex h-10 w-10 items-center justify-center rounded-xl"
                                            style="background-color: {{ $category->color }}20">
                                            <div class="h-4 w-4 rounded-full" style="background-color: {{ $category->color }}">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <a href="{{ blog_admin_route('categories.show', $category->id) }}"
                                                    class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                                    {{ $category->name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $category->slug }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ Str::limit($category->description, 60) }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $category->posts_count }}
                                        posts</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($category->is_active)
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                            Ativa
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                                            Inativa
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $category->sort_order }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-1">
                                        <button type="button"
                                            onclick="toggleStatus({{ $category->id }}, {{ $category->is_active ? 'false' : 'true' }})"
                                            class="rounded-xl p-2 text-slate-500 transition hover:bg-blue-50 hover:text-blue-600 dark:text-slate-400 dark:hover:bg-blue-950/40 dark:hover:text-blue-400"
                                            title="{{ $category->is_active ? 'Desativar' : 'Ativar' }}">
                                            @if ($category->is_active)
                                                <x-icon name="toggle-on" class="h-4 w-4" />
                                            @else
                                                <x-icon name="toggle-off" class="h-4 w-4" />
                                            @endif
                                        </button>
                                        <a href="{{ blog_admin_route('categories.edit', $category->id) }}"
                                            class="rounded-xl p-2 text-slate-500 transition hover:bg-emerald-50 hover:text-emerald-600 dark:text-slate-400 dark:hover:bg-emerald-950/40 dark:hover:text-emerald-400"
                                            title="Editar">
                                            <x-icon name="pen-to-square" class="h-4 w-4" />
                                        </a>
                                        @if ($category->posts_count == 0)
                                            <form action="{{ blog_admin_route('categories.destroy', $category->id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-xl p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-600 dark:text-slate-400 dark:hover:bg-red-950/40 dark:hover:text-red-400"
                                                    title="Excluir">
                                                    <x-icon name="trash" class="h-4 w-4" />
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <x-icon name="folder-tree" class="mx-auto h-12 w-12 text-gray-300 dark:text-slate-600" />
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma categoria
                                        encontrada</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Crie a primeira categoria para
                                        classificar os posts.</p>
                                    <div class="mt-6">
                                        <a href="{{ blog_admin_route('categories.create') }}"
                                            class="inline-flex items-center rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:from-emerald-700 hover:to-emerald-800">
                                            <x-icon name="plus" class="mr-2 h-4 w-4" />
                                            Nova categoria
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 dark:border-slate-700">
                    {{ $categories->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        async function toggleStatus(categoryId, newStatus) {
            try {
                const url = @json(url('/diretoria/blog/categorias')) + '/' + categoryId + '/toggle-status';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        is_active: newStatus
                    })
                });

                const data = await response.json();

                if (data.success) {
                    location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            } catch (error) {
                alert('Erro ao alterar status: ' + error.message);
            }
        }
    </script>
@endpush
