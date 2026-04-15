@extends($layout)

@section('title', 'Cobranças gateway')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('gateway::paineldiretoria.partials.subnav', ['active' => 'payments'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cobranças</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Pagamentos iniciados via PSP (referência técnica).</p>
        </div>
        <a href="{{ route('diretoria.gateway.payments.export.csv', request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-gray-800 shadow-sm hover:bg-emerald-50/50 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
            <x-icon name="download" class="h-4 w-4 text-emerald-600" style="duotone" />
            Exportar CSV
        </a>
    </div>

    <form method="get" class="flex flex-wrap gap-2">
        <select name="status" class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800">
            <option value="">Todos os estados</option>
            <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pendente</option>
            <option value="paid" @selected(($filters['status'] ?? '') === 'paid')>Pago</option>
            <option value="failed" @selected(($filters['status'] ?? '') === 'failed')>Falhou</option>
        </select>
        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">Filtrar</button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
            <thead class="bg-gray-50 dark:bg-slate-900/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-500">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-500">Valor</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-500">Driver</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-500">Estado</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-500">Pago em</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($payments as $p)
                    <tr class="text-sm">
                        <td class="px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-300">#{{ $p->id }}</td>
                        <td class="px-4 py-3 tabular-nums font-semibold">R$ {{ number_format((float) $p->amount, 2, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $p->driver }}</td>
                        <td class="px-4 py-3">{{ $p->statusLabel() }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $p->paid_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('diretoria.gateway.payments.show', $p) }}" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-1">{{ $payments->links() }}</div>
</div>
@endsection
