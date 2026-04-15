@extends($layout)

@section('title', 'Editar pedido')

@section('content')
@php
    $in = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400';
    $lb = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
@endphp
<div class="mx-auto max-w-2xl space-y-8 pb-10">
    @include('financeiro::paineldiretoria.partials.subnav', ['active' => 'expense_requests'])

    <div>
        <a href="{{ route('diretoria.financeiro.expense-requests.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:gap-2 dark:text-emerald-400">
            <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
            Voltar aos reembolsos
        </a>
        <h1 class="mt-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Editar pedido #{{ $expenseRequest->id }}</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Actualize valor, texto ou anexo enquanto o pedido permitir edição.</p>
    </div>

    <form action="{{ route('diretoria.financeiro.expense-requests.update', $expenseRequest) }}" method="post" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="border-b border-gray-100 px-6 py-4 dark:border-slate-700">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Dados do pedido</h2>
            </div>
            <div class="space-y-5 p-6">
                <div>
                    <label class="{{ $lb }}">Valor (R$)</label>
                    <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount', $expenseRequest->amount) }}" required class="{{ $in }} tabular-nums" inputmode="decimal">
                    @error('amount')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $lb }}">Justificação</label>
                    <textarea name="justification" rows="5" required class="{{ $in }} min-h-[8rem]">{{ old('justification', $expenseRequest->justification) }}</textarea>
                    @error('justification')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $lb }}">Novo anexo (opcional)</label>
                    <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.webp" class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-emerald-800 hover:file:bg-emerald-100 dark:text-gray-400 dark:file:bg-emerald-950/50 dark:file:text-emerald-200">
                    @if($expenseRequest->attachment_path)
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Ficheiro actual: <a href="{{ asset('storage/'.$expenseRequest->attachment_path) }}" class="font-semibold text-emerald-700 hover:underline dark:text-emerald-400" target="_blank" rel="noopener noreferrer">abrir</a></p>
                    @endif
                    @error('attachment')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 bg-gray-50/80 px-6 py-4 dark:border-slate-700 dark:bg-slate-900/40">
                <a href="{{ route('diretoria.financeiro.expense-requests.index') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200/80 dark:text-gray-300 dark:hover:bg-slate-700">Cancelar</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700">
                    <x-icon name="check" class="h-4 w-4" style="solid" />
                    Actualizar
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
