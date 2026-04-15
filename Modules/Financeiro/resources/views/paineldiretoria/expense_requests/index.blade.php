@extends($layout)

@section('title', 'Reembolsos e despesas')

@section('content')
@php
    $labels = [
        'draft' => ['Rascunho', 'bg-gray-100 text-gray-800 ring-gray-200 dark:bg-slate-700 dark:text-gray-200 dark:ring-slate-600'],
        'submitted' => ['Submetido', 'bg-amber-100 text-amber-950 ring-amber-200 dark:bg-amber-900/40 dark:text-amber-100 dark:ring-amber-800/50'],
        'approved' => ['Aprovado', 'bg-sky-100 text-sky-950 ring-sky-200 dark:bg-sky-900/40 dark:text-sky-100 dark:ring-sky-800/50'],
        'rejected' => ['Recusado', 'bg-rose-100 text-rose-950 ring-rose-200 dark:bg-rose-900/40 dark:text-rose-100 dark:ring-rose-800/50'],
        'paid' => ['Pago', 'bg-emerald-100 text-emerald-950 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-100 dark:ring-emerald-800/50'],
    ];
    $filterClass = 'rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400';
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('financeiro::paineldiretoria.partials.subnav', ['active' => 'expense_requests'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-800 dark:text-amber-400">Fluxo diretoria</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-600/25">
                    <x-icon name="file-lines" class="h-5 w-5" style="duotone" />
                </span>
                Reembolsos
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                Rascunho → submissão → <span class="font-semibold text-gray-800 dark:text-gray-200">aprovação (presidente / vice)</span> → <span class="font-semibold text-gray-800 dark:text-gray-200">pagamento (tesouraria)</span>, com registo no livro de lançamentos.
            </p>
        </div>
        @can('create', \Modules\Financeiro\App\Models\FinExpenseRequest::class)
            <a href="{{ route('diretoria.financeiro.expense-requests.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900">
                <x-icon name="plus" class="h-4 w-4" style="solid" />
                Novo pedido
            </a>
        @endcan
    </div>

    <div class="rounded-2xl border border-amber-100/80 bg-gradient-to-br from-amber-50/50 to-white p-4 dark:border-amber-900/30 dark:from-amber-950/20 dark:to-slate-900 sm:p-5">
        <p class="text-xs font-bold uppercase tracking-wide text-amber-900/80 dark:text-amber-400/90">Legenda de estados</p>
        <div class="mt-3 flex flex-wrap gap-2">
            @foreach($labels as $key => $pair)
                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold ring-1 {{ $pair[1] }}">{{ $pair[0] }}</span>
            @endforeach
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem]">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Estado</label>
                <select name="status" class="w-full {{ $filterClass }}" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Rascunho</option>
                    <option value="submitted" @selected(($filters['status'] ?? '') === 'submitted')>Submetido</option>
                    <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Aprovado</option>
                    <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Recusado</option>
                    <option value="paid" @selected(($filters['status'] ?? '') === 'paid')>Pago</option>
                </select>
            </div>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($requests as $req)
            @php
                $st = $labels[$req->status] ?? ['—', 'bg-gray-100 text-gray-800 ring-gray-200 dark:bg-slate-700 dark:text-gray-200 dark:ring-slate-600'];
            @endphp
            <article class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500">#{{ $req->id }}</span>
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold ring-1 {{ $st[1] }}">{{ $st[0] }}</span>
                        </div>
                        <p class="mt-2 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">R$ {{ number_format((float) $req->amount, 2, ',', '.') }}</p>
                        <p class="mt-3 text-sm leading-relaxed text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($req->justification, 320) }}</p>
                        <p class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500 dark:text-gray-400">
                            <span class="inline-flex items-center gap-1">
                                <x-icon name="user" class="h-3.5 w-3.5 opacity-70" style="duotone" />
                                {{ $req->requester?->name ?? '—' }}
                            </span>
                            @if($req->attachment_path)
                                <a href="{{ asset('storage/'.$req->attachment_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-semibold text-emerald-700 hover:underline dark:text-emerald-400">
                                    <x-icon name="paperclip" class="h-3.5 w-3.5" style="duotone" />
                                    Anexo
                                </a>
                            @endif
                        </p>
                        @if($req->status === 'rejected' && $req->rejection_reason)
                            <p class="mt-3 rounded-xl border border-rose-200/80 bg-rose-50/80 px-3 py-2 text-xs text-rose-800 dark:border-rose-900/50 dark:bg-rose-950/30 dark:text-rose-200">
                                <span class="font-bold">Motivo da recusa:</span> {{ $req->rejection_reason }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col gap-3 border-t border-gray-100 bg-gray-50/60 px-5 py-4 dark:border-slate-700 dark:bg-slate-900/40">
                    <div class="flex flex-wrap gap-2">
                        @can('update', $req)
                            <a href="{{ route('diretoria.financeiro.expense-requests.edit', $req) }}" class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-bold text-gray-800 shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-emerald-800">Editar</a>
                        @endcan
                        @can('submit', $req)
                            <form action="{{ route('diretoria.financeiro.expense-requests.submit', $req) }}" method="post" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center rounded-xl bg-amber-600 px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-amber-700">Submeter</button>
                            </form>
                        @endcan
                        @can('approve', $req)
                            <form action="{{ route('diretoria.financeiro.expense-requests.approve', $req) }}" method="post" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center rounded-xl bg-sky-600 px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-sky-700">Aprovar</button>
                            </form>
                        @endcan
                        @can('pay', $req)
                            <form action="{{ route('diretoria.financeiro.expense-requests.pay', $req) }}" method="post" class="inline" onsubmit="return confirm('Registar pagamento no livro de lançamentos?');">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-700">
                                    <x-icon name="sack-dollar" class="h-3.5 w-3.5" style="duotone" />
                                    Registar pagamento
                                </button>
                            </form>
                        @endcan
                        @can('delete', $req)
                            <form action="{{ route('diretoria.financeiro.expense-requests.destroy', $req) }}" method="post" class="inline" onsubmit="return confirm('Eliminar rascunho?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center rounded-xl px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30">Excluir</button>
                            </form>
                        @endcan
                    </div>
                    @can('reject', $req)
                        <form action="{{ route('diretoria.financeiro.expense-requests.reject', $req) }}" method="post" class="flex flex-col gap-2 rounded-xl border border-rose-200/60 bg-white p-3 dark:border-rose-900/40 dark:bg-slate-800 sm:flex-row sm:items-center">
                            @csrf
                            <input type="text" name="rejection_reason" required placeholder="Motivo da recusa (visível ao solicitante)" class="min-w-0 flex-1 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs text-gray-900 shadow-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                            <button type="submit" class="shrink-0 rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-rose-700">Recusar</button>
                        </form>
                    @endcan
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50/50 px-6 py-16 text-center dark:border-slate-600 dark:bg-slate-900/40">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-gray-400 shadow-sm dark:bg-slate-800 dark:text-gray-500">
                    <x-icon name="file-lines" class="h-7 w-7" style="duotone" />
                </span>
                <p class="mt-4 font-semibold text-gray-900 dark:text-white">Nenhum pedido neste filtro</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Altere o estado ou crie um novo pedido de reembolso.</p>
                @can('create', \Modules\Financeiro\App\Models\FinExpenseRequest::class)
                    <a href="{{ route('diretoria.financeiro.expense-requests.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">
                        <x-icon name="plus" class="h-4 w-4" style="solid" />
                        Novo pedido
                    </a>
                @endcan
            </div>
        @endforelse
    </div>
    <div class="px-1">{{ $requests->links() }}</div>
</div>
@endsection
