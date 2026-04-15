@extends($layout)

@section('title', 'Diretório de talentos')

@section('content')
@php
    $filterClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-violet-400';
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('talentos::paineldiretoria.partials.subnav', ['active' => 'directory'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-violet-800 dark:text-violet-400">Diretoria · Talentos</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-600/25">
                    <x-module-icon module="Talentos" class="h-7 w-7" />
                </span>
                Diretório de talentos
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                Perfis com conta na plataforma — filtre por igreja, competência ou visibilidade no diretório interno.
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            @can('talentos.directory.export')
                <a href="{{ route('diretoria.talentos.directory.export.csv', request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 shadow-sm transition hover:border-violet-200 hover:bg-violet-50/40 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-violet-700">
                    <x-icon name="download" class="h-4 w-4 text-violet-600 dark:text-violet-400" style="duotone" />
                    Exportar CSV
                </a>
            @endcan
            @can('create', \Modules\Talentos\App\Models\TalentAssignment::class)
                <a href="{{ route('diretoria.talentos.assignments.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/25 transition hover:bg-violet-700">
                    <x-icon name="plus" class="h-4 w-4" style="solid" />
                    Nova atribuição
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem] flex-1 sm:max-w-md">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Nome ou e-mail</label>
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Pesquisar…" autocomplete="off" class="{{ $filterClass }}">
            </div>
            @if($churches->isNotEmpty())
                <div class="min-w-[12rem] flex-1 sm:max-w-xs">
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Igreja</label>
                    <select name="church_id" class="{{ $filterClass }}">
                        <option value="">Todas</option>
                        @foreach($churches as $c)
                            <option value="{{ $c->id }}" @selected(($filters['church_id'] ?? '') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="min-w-[12rem] flex-1 sm:max-w-xs">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Competência</label>
                <select name="skill_id" class="{{ $filterClass }}">
                    <option value="">Todas</option>
                    @foreach($skills as $s)
                        <option value="{{ $s->id }}" @selected(($filters['skill_id'] ?? '') == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[12rem] flex-1 sm:max-w-xs">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Área de serviço</label>
                <select name="area_id" class="{{ $filterClass }}">
                    <option value="">Todas</option>
                    @foreach($areas as $ar)
                        <option value="{{ $ar->id }}" @selected(($filters['area_id'] ?? '') == $ar->id)>{{ $ar->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 pb-2.5">
                <input type="hidden" name="searchable_only" value="0">
                <input type="checkbox" name="searchable_only" value="1" id="searchable_only" class="h-4 w-4 rounded border-gray-300 text-violet-600 focus:ring-violet-500 dark:border-slate-600 dark:bg-slate-900" @checked($filters['searchable_only'] ?? false)>
                <label for="searchable_only" class="text-sm font-medium text-gray-700 dark:text-gray-300">Só pesquisáveis</label>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/20 transition hover:bg-violet-700">
                    <x-icon name="filter" class="h-4 w-4" style="solid" />
                    Aplicar
                </button>
                <a href="{{ route('diretoria.talentos.directory.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-slate-600 dark:text-gray-300 dark:hover:bg-slate-700">Limpar</a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/80 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3.5">Nome</th>
                        <th class="px-4 py-3.5">Igreja</th>
                        <th class="px-4 py-3.5">Competências</th>
                        <th class="px-4 py-3.5">Pesquisável</th>
                        <th class="px-4 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($profiles as $profile)
                        <tr class="transition hover:bg-violet-50/40 dark:hover:bg-slate-900/50">
                            <td class="px-4 py-3.5 font-semibold text-gray-900 dark:text-white">{{ $profile->user?->name }}</td>
                            <td class="px-4 py-3.5 text-gray-600 dark:text-gray-300">{{ $profile->user?->church?->name ?? '—' }}</td>
                            <td class="max-w-xs px-4 py-3.5 text-gray-600 dark:text-gray-300">{{ $profile->skills->pluck('name')->take(4)->implode(', ') }}{{ $profile->skills->count() > 4 ? '…' : '' }}</td>
                            <td class="px-4 py-3.5">
                                @if($profile->is_searchable)
                                    <span class="inline-flex rounded-lg bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-900 ring-1 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-100 dark:ring-emerald-800/50">Sim</span>
                                @else
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Não</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <a href="{{ route('diretoria.talentos.directory.show', $profile->user_id) }}" class="text-sm font-bold text-violet-700 hover:underline dark:text-violet-400">Ficha</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <x-icon name="users" class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" style="duotone" />
                                <p class="mt-4 font-semibold text-gray-900 dark:text-white">Nenhum perfil com estes filtros</p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ajuste igreja, competência ou marque “só pesquisáveis”.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $profiles->links() }}</div>
</div>
@endsection
