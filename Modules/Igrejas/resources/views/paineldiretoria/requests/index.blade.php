@extends($layout)

@section('title', 'Pedidos — Igrejas')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('igrejas::paineldiretoria.partials.subnav', ['active' => 'requests'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="flex flex-wrap items-center gap-3 text-2xl font-bold text-gray-900 dark:text-white">
                <x-module-icon module="Igrejas" class="h-9 w-9 shrink-0" />
                Pedidos de alteração
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Rascunhos e pedidos enviados pelos líderes; a diretoria analisa e aprova ou recusa.
            </p>
        </div>
        @if($isDirectorate)
            <form method="get" class="flex flex-wrap items-end gap-2">
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase text-gray-500">Estado</label>
                    <select name="status" class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Rascunho</option>
                        <option value="submitted" @selected(($filters['status'] ?? '') === 'submitted')>Enviados</option>
                        <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Aprovados</option>
                        <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Recusados</option>
                    </select>
                </div>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs font-bold uppercase text-gray-500 dark:bg-slate-900/80 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Igreja</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Remetente</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($requests as $r)
                    <tr class="hover:bg-cyan-50/30 dark:hover:bg-slate-900/40">
                        <td class="px-4 py-3 font-mono text-xs">{{ $r->id }}</td>
                        <td class="px-4 py-3">{{ $r->type }}</td>
                        <td class="px-4 py-3">{{ $r->church?->name ?? '— (nova)' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-lg bg-slate-100 px-2 py-0.5 text-xs font-bold dark:bg-slate-700">{{ $r->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $r->submitter?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route($routePrefix.'.requests.show', $r) }}" class="text-sm font-bold text-cyan-700 hover:underline dark:text-cyan-400">Abrir</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Nenhum pedido encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $requests->links() }}</div>
</div>
@endsection
