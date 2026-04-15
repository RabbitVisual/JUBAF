@extends($layout)

@section('title', 'Gateway — contas PSP')

@section('content')
<div class="mx-auto max-w-6xl space-y-6 p-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Contas de gateway</h1>
        @can('create', \Modules\Gateway\App\Models\GatewayProviderAccount::class)
            <a href="{{ route('admin.gateway.accounts.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                Nova conta
            </a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow dark:border-slate-600 dark:bg-slate-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
            <thead class="bg-gray-50 dark:bg-slate-900/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-500">Nome</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-500">Driver</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-500">Activo</th>
                    <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-500">Padrão</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($accounts as $a)
                    <tr class="text-sm">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $a->name }}</td>
                        <td class="px-4 py-3">{{ $a->driver }}</td>
                        <td class="px-4 py-3">{{ $a->is_enabled ? 'Sim' : 'Não' }}</td>
                        <td class="px-4 py-3">{{ $a->is_default ? 'Sim' : 'Não' }}</td>
                        <td class="px-4 py-3 text-right">
                            @can('update', $a)
                                <a href="{{ route('admin.gateway.accounts.edit', $a) }}" class="text-indigo-600 hover:underline">Editar</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Nenhuma conta configurada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $accounts->links() }}</div>
</div>
@endsection
