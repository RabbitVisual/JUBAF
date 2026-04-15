@extends('paineldiretoria::components.layouts.app')

@section('title', 'Tags - Blog Diretoria')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
        @include('blog::paineldiretoria.partials.subnav', ['active' => 'tags'])

        @include('blog::paineldiretoria.partials.page-hero', [
            'kicker' => 'Taxonomia',
            'title' => 'Tags do blog',
            'lead' =>
                'Palavras-chave para cruzar assuntos entre posts. Remova tags órfãs com segurança ou limpe as não utilizadas em lote.',
            'iconName' => 'tags',
            'crumbs' => [
                ['label' => 'Diretoria', 'url' => route('diretoria.dashboard')],
                ['label' => 'Blog', 'url' => blog_admin_route('index')],
                ['label' => 'Tags', 'url' => null],
            ],
            'actions' => view('blog::paineldiretoria.partials.hero-actions-tags-index')->render(),
        ])

        <div
            class="flex gap-4 rounded-2xl border border-sky-200/80 bg-sky-50/90 p-4 dark:border-sky-900/40 dark:bg-sky-950/30 md:items-center md:p-5">
            <span
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-200/80 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200">
                <x-icon name="circle-info" class="h-5 w-5" style="duotone" />
            </span>
            <div class="min-w-0 text-sm text-sky-950/90 dark:text-sky-100/90">
                <p class="font-semibold text-sky-900 dark:text-sky-100">Limpeza</p>
                <p class="mt-1 leading-relaxed text-sky-900/85 dark:text-sky-200/85">
                    Só é possível excluir tags sem posts. Use o botão de limpeza em lote no fim da página quando houver tags
                    órfãs.
                </p>
            </div>
        </div>

        @if (isset($estatisticas))
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Total</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                        {{ $estatisticas['total_tags'] ?? 0 }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Cadastradas</p>
                </div>
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Em uso</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                        {{ $estatisticas['used_tags'] ?? 0 }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Associadas a posts</p>
                </div>
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Não usadas
                    </p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                        {{ $estatisticas['unused_tags'] ?? 0 }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Candidatas à limpeza</p>
                </div>
            </div>
        @endif

        <section
            class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
            <div
                class="flex flex-col gap-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Lista de tags</h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Slug, uso e data de criação</p>
                </div>
                <span
                    class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-200">
                    {{ $tags->total() }} {{ $tags->total() === 1 ? 'item' : 'itens' }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/80">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Tag</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Posts</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Criada em</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-slate-700 dark:bg-slate-800/80">
                        @forelse ($tags as $tag)
                            <tr class="transition hover:bg-gray-50/80 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="mr-4 flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/40">
                                            <x-icon name="tag" class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <a href="{{ blog_admin_route('tags.show', $tag->id) }}"
                                                    class="hover:text-emerald-600 dark:hover:text-emerald-400">
                                                    {{ $tag->name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $tag->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $tag->posts_count }} posts
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $tag->created_at->format('d/m/Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-1">
                                        <a href="{{ blog_admin_route('tags.edit', $tag->id) }}"
                                            class="rounded-xl p-2 text-slate-500 transition hover:bg-emerald-50 hover:text-emerald-600 dark:text-slate-400 dark:hover:bg-emerald-950/40 dark:hover:text-emerald-400"
                                            title="Editar">
                                            <x-icon name="pen-to-square" class="h-4 w-4" />
                                        </a>
                                        @if ($tag->posts_count == 0)
                                            <form action="{{ blog_admin_route('tags.destroy', $tag->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Tem certeza que deseja excluir esta tag?')">
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
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <x-icon name="tags" class="mx-auto h-12 w-12 text-gray-300 dark:text-slate-600" />
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma tag
                                        encontrada</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Crie tags para classificar
                                        transversalmente os posts.</p>
                                    <div class="mt-6">
                                        <a href="{{ blog_admin_route('tags.create') }}"
                                            class="inline-flex items-center rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:from-emerald-700 hover:to-emerald-800">
                                            <x-icon name="plus" class="mr-2 h-4 w-4" />
                                            Nova tag
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($tags->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 dark:border-slate-700">
                    {{ $tags->links() }}
                </div>
            @endif
        </section>

        @if (isset($estatisticas) && ($estatisticas['unused_tags'] ?? 0) > 0)
            <div
                class="overflow-hidden rounded-3xl border border-red-200/80 bg-red-50/50 p-6 dark:border-red-900/40 dark:bg-red-950/20">
                <form action="{{ blog_admin_route('tags.clean-unused') }}" method="POST"
                    onsubmit="return confirm('Tem certeza que deseja excluir todas as tags não utilizadas? Esta ação não pode ser desfeita.')">
                    @csrf
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-red-900 dark:text-red-200">Limpeza em lote</h3>
                            <p class="mt-1 text-sm text-red-800/90 dark:text-red-300/90">Existem
                                {{ $estatisticas['unused_tags'] }} tags sem nenhum post vinculado.</p>
                        </div>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-red-500/20 transition hover:from-red-700 hover:to-red-800">
                            <x-icon name="trash-can-arrow-up" class="h-4 w-4" />
                            Limpar não utilizadas
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
@endsection
