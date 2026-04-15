@extends('layouts.app')

@section('title', 'Pedidos à diretoria')

@section('content')
<div class="mx-auto max-w-5xl space-y-8">
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pedidos (Igrejas)</h1>
        @can('create', \Modules\Igrejas\App\Models\ChurchChangeRequest::class)
            <a href="{{ route('lideres.igrejas.requests.create') }}" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Novo rascunho</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-bold uppercase text-slate-500 dark:bg-slate-900 dark:text-slate-400">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($requests as $r)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $r->id }}</td>
                        <td class="px-4 py-3">{{ $r->type }}</td>
                        <td class="px-4 py-3">{{ $r->status }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('lideres.igrejas.requests.show', $r) }}" class="font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Ver</a>
                            @can('update', $r)
                                <a href="{{ route('lideres.igrejas.requests.edit', $r) }}" class="font-semibold text-slate-600 hover:underline dark:text-slate-400">Editar</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-slate-500">Sem pedidos ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $requests->links() }}</div>
</div>
@endsection
