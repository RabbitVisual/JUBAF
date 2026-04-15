@extends($layout)

@section('title', 'Categorias financeiras')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 pb-10">
        @include('financeiro::paineldiretoria.partials.subnav', ['active' => 'categories'])

        <div
            class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Plano de
                    contas (simplificado)</p>
                <h1
                    class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                        <x-icon name="tags" class="h-5 w-5" style="duotone" />
                    </span>
                    Categorias
                </h1>
                <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                    Códigos estáveis para relatórios, assembleia e alinhamento com o Gateway (inscrições online). Grupos
                    ajudam a ler o balancete por natureza económica.
                </p>
            </div>
            @can('create', \Modules\Financeiro\App\Models\FinCategory::class)
                <a href="{{ route('diretoria.financeiro.categories.create') }}"
                    class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">
                    <x-icon name="plus" class="h-4 w-4" style="solid" />
                    Nova categoria
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <div
            class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr
                            class="bg-gray-50/90 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/90 dark:text-gray-400">
                            <th class="px-5 py-3.5">Ordem</th>
                            <th class="px-5 py-3.5">Nome</th>
                            <th class="px-5 py-3.5">Código</th>
                            <th class="px-5 py-3.5">Grupo</th>
                            <th class="px-5 py-3.5">Tipo</th>
                            <th class="px-5 py-3.5 text-right">Lançamentos</th>
                            <th class="px-5 py-3.5">Estado</th>
                            <th class="px-5 py-3.5 text-right w-40"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($categories as $c)
                            <tr class="transition hover:bg-emerald-50/40 dark:hover:bg-slate-900/50">
                                <td class="whitespace-nowrap px-5 py-3.5 tabular-nums text-gray-600 dark:text-gray-400">
                                    {{ $c->sort_order }}</td>
                                <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">
                                    {{ $c->name }}
                                    @if ($c->is_system)
                                        <span
                                            class="ml-1 inline-flex rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-bold uppercase text-slate-600 dark:bg-slate-900 dark:text-slate-300">Sistema</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-mono text-xs text-gray-700 dark:text-gray-300">
                                    {{ $c->code ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">
                                    {{ \Modules\Financeiro\App\Models\FinCategory::groupLabel($c->group_key) }}</td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold {{ $c->direction === 'in' ? 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100' : 'bg-rose-100 text-rose-900 dark:bg-rose-900/40 dark:text-rose-100' }}">
                                        {{ $c->direction === 'in' ? 'Receita' : 'Despesa' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right tabular-nums text-gray-700 dark:text-gray-300">
                                    {{ $c->transactions_count }}</td>
                                <td class="px-5 py-3.5">
                                    @if ($c->is_active)
                                        <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Ativa</span>
                                    @else
                                        <span class="text-xs font-bold text-gray-500">Inactiva</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        @can('update', $c)
                                            <a href="{{ route('diretoria.financeiro.categories.edit', $c) }}"
                                                class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Editar</a>
                                        @endcan
                                        @can('delete', $c)
                                            <form action="{{ route('diretoria.financeiro.categories.destroy', $c) }}"
                                                method="post" class="inline"
                                                onsubmit="return confirm('Remover esta categoria?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs font-bold text-rose-600 hover:underline dark:text-rose-400">Excluir</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Sem categorias.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
