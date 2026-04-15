@extends('pastor.layouts.app')

@section('title', 'Congregações JUBAF')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
            <x-module-icon module="Igrejas" class="h-9 w-9 shrink-0" />
            Supervisão — congregações
        </h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-2xl">
            Leitura das igrejas vinculadas à JUBAF/ASBAF. Utiliza a pesquisa para localizar congregações e contactar líderes no terreno.
        </p>
    </div>

    @if($myChurch)
        <div class="rounded-2xl border border-sky-200 dark:border-sky-900/50 bg-sky-50/90 dark:bg-sky-950/30 p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-sky-700 dark:text-sky-300">A tua igreja (perfil)</p>
                <p class="text-lg font-bold text-slate-900 dark:text-white mt-1">{{ $myChurch->name }}</p>
                @if($myChurch->city)<p class="text-sm text-slate-600 dark:text-slate-400">{{ $myChurch->city }}</p>@endif
            </div>
            <a href="{{ route('pastor.igrejas.show', $myChurch) }}" class="inline-flex justify-center px-4 py-2 rounded-xl bg-sky-600 text-white text-sm font-semibold hover:bg-sky-700 shrink-0">Ver ficha</a>
        </div>
    @endif

    <form method="get" class="flex flex-wrap gap-3 items-end bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4">
        <div>
            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Pesquisar</label>
            <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nome, cidade, e-mail…"
                class="rounded-xl border border-slate-300 dark:border-slate-600 dark:bg-slate-800 px-3 py-2 text-sm w-64 max-w-full">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Cidade</label>
            <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="Filtrar por cidade…"
                class="rounded-xl border border-slate-300 dark:border-slate-600 dark:bg-slate-800 px-3 py-2 text-sm w-48 max-w-full">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Estado</label>
            <select name="active" class="rounded-xl border border-slate-300 dark:border-slate-600 dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">Todas</option>
                <option value="1" @selected(($filters['active'] ?? '') === '1')>Ativas</option>
                <option value="0" @selected(($filters['active'] ?? '') === '0')>Inativas</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 text-sm font-semibold text-slate-800 dark:text-slate-100">Filtrar</button>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800/80 text-left text-xs font-bold uppercase text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="px-4 py-3">Nome</th>
                    <th class="px-4 py-3">Cidade</th>
                    <th class="px-4 py-3">Líderes</th>
                    <th class="px-4 py-3">Jovens</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($churches as $c)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                        <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $c->name }}</td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $c->city ?? '—' }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ $c->leaders_count }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ $c->jovens_members_count }}</td>
                        <td class="px-4 py-3">
                            @if($c->is_active)
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Ativa</span>
                            @else
                                <span class="text-xs font-bold text-slate-500">Inativa</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('pastor.igrejas.show', $c) }}" class="text-sky-600 dark:text-sky-400 font-semibold hover:underline">Ficha</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-slate-500">Nenhuma congregação encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $churches->links() }}</div>
</div>
@endsection
