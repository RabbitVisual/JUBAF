@extends('layouts.app')

@section('title', 'Dashboard do Lider')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 px-4 pb-8 pt-4 sm:px-6 lg:px-8">
        <x-ui.card padding="dense" class="border-slate-200 dark:border-slate-700">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Painel local</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">Controle da minha igreja</h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        Visao consolidada de contribuicoes, talentos pendentes e agenda regional.
                    </p>
                </div>
                @if ($user->church)
                    <x-ui.badge tone="success" class="!rounded-full !px-3 !py-1">{{ $user->church->name }}</x-ui.badge>
                @endif
            </div>
        </x-ui.card>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Total de obrigacoes</p>
                <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $financialStatus['total'] ?? 0 }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Em atraso</p>
                <p class="mt-2 text-2xl font-bold text-rose-700 dark:text-rose-400">{{ $financialStatus['overdue'] ?? 0 }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Pendentes validacao talentos</p>
                <p class="mt-2 text-2xl font-bold text-indigo-700 dark:text-indigo-400">{{ $talentPendingProfiles->count() }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Jovens cadastrados</p>
                <p class="mt-2 text-2xl font-bold text-emerald-700 dark:text-emerald-400">{{ $jovensCount }}</p>
            </article>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Status financeiro da igreja</h2>
                    @if (Route::has('lideres.financeiro.minhas-contas'))
                        <x-ui.button variant="ghost" size="sm" href="{{ route('lideres.financeiro.minhas-contas') }}" class="!px-0 !py-0 !text-xs !font-semibold !text-indigo-700 hover:!underline dark:!text-indigo-300">Ver contas</x-ui.button>
                    @endif
                </div>
                <x-ui.table class="!rounded-xl !border-0 !shadow-none dark:!bg-slate-900">
                    <x-slot name="head">
                        <th class="px-5 py-3.5">Mes</th>
                        <th class="px-5 py-3.5">Igreja</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Valor</th>
                    </x-slot>
                    @forelse($recentInvoices as $invoice)
                        <tr class="odd:bg-white even:bg-gray-50/60 dark:odd:bg-slate-900 dark:even:bg-slate-800/50">
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">{{ optional($invoice->billing_month)->format('m/Y') }}</td>
                            <td class="px-5 py-3.5 text-slate-900 dark:text-slate-100">{{ $invoice->church?->name ?: '-' }}</td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">{{ strtoupper((string) $invoice->status) }}</td>
                            <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-slate-900 dark:text-slate-100">R$ {{ number_format((float) $invoice->amount, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-500 dark:text-slate-400">Sem faturas recentes.</td>
                        </tr>
                    @endforelse
                </x-ui.table>
            </div>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Talentos pendentes de validacao</h2>
                    @if (Route::has('lideres.talentos.validation.index'))
                        <a href="{{ route('lideres.talentos.validation.index') }}" class="text-xs font-semibold text-indigo-700 hover:underline dark:text-indigo-300">Validar agora</a>
                    @endif
                </div>
                <div class="space-y-3">
                    @forelse($talentPendingProfiles as $profile)
                        <div class="rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $profile->user?->name ?: 'Perfil sem utilizador' }}</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $profile->user?->church?->name ?: 'Sem igreja vinculada' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400">Nao existem talentos pendentes para validacao no momento.</p>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Proximos eventos regionais</h2>
                @if (Route::has('lideres.calendario.index'))
                    <a href="{{ route('lideres.calendario.index') }}" class="text-xs font-semibold text-indigo-700 hover:underline dark:text-indigo-300">Calendario completo</a>
                @endif
            </div>
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                @forelse($upcomingEvents as $event)
                    <article class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $event->title }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ optional($event->starts_at)->format('d/m/Y H:i') }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $event->location ?: 'Local a definir' }}</p>
                    </article>
                @empty
                    <p class="text-sm text-slate-500 dark:text-slate-400">Nao ha eventos regionais publicados para os proximos dias.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
