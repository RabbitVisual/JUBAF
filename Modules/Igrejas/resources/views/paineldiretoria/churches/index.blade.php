@extends($layout)

@section('title', 'Congregações — lista')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('igrejas::paineldiretoria.partials.subnav', ['active' => 'list'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 dark:text-indigo-400">Diretoria · Cadastro</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/25">
                    <x-module-icon module="Igrejas" class="h-7 w-7" />
                </span>
                Congregações
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                Lista completa com pesquisa, cidade e estado — exportação CSV para secretaria e assembleia.
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            @can('export', \Modules\Igrejas\App\Models\Church::class)
                <x-ui.button variant="secondary" size="md" href="{{ route($routePrefix.'.export.csv', request()->query()) }}">
                    <x-icon name="download" class="h-4 w-4 text-indigo-600 dark:text-indigo-400" style="duotone" />
                    Exportar CSV
                </x-ui.button>
            @endcan
            @can('create', \Modules\Igrejas\App\Models\Church::class)
                <x-ui.button variant="primary" size="md" href="{{ route($routePrefix.'.create') }}">
                    <x-icon name="plus" class="h-4 w-4" style="solid" />
                    Nova congregação
                </x-ui.button>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <x-ui.card title="Filtros">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem] flex-1 sm:max-w-xs">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Pesquisar</label>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nome, cidade, e-mail…"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-indigo-400"
                    @input.debounce.600ms="$el.form.requestSubmit()">
            </div>
            <div class="min-w-[10rem] flex-1 sm:max-w-[12rem]">
                <x-ui.input label="Cidade" name="city" :value="$filters['city'] ?? ''" placeholder="Filtrar…" />
            </div>
            <div class="min-w-[10rem]">
                <x-ui.select label="Ativa (sistema)" name="active" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    <option value="1" @selected(($filters['active'] ?? '') === '1')>Ativas</option>
                    <option value="0" @selected(($filters['active'] ?? '') === '0')>Inativas</option>
                </x-ui.select>
            </div>
            <div class="min-w-[10rem]">
                <x-ui.select label="CRM" name="crm_status" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach(\Modules\Igrejas\App\Models\Church::crmStatuses() as $st)
                        <option value="{{ $st }}" @selected(($filters['crm_status'] ?? '') === $st)>{{ $st }}</option>
                    @endforeach
                </x-ui.select>
            </div>
            @if(isset($jubafSectors) && $jubafSectors->isNotEmpty())
                <div class="min-w-[10rem]">
                    <x-ui.select label="Setor (ERP)" name="jubaf_sector_id" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        @foreach($jubafSectors as $s)
                            <option value="{{ $s->id }}" @selected(($filters['jubaf_sector_id'] ?? '') == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
            @endif
            <div class="min-w-[10rem] flex-1 sm:max-w-[12rem]">
                <x-ui.input label="Setor (texto)" name="sector" :value="$filters['sector'] ?? ''" placeholder="Filtrar…" />
            </div>
            <div class="min-w-[10rem]">
                <x-ui.select label="Cooperação" name="cooperation_status" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    @foreach(\Modules\Igrejas\App\Models\Church::cooperationStatuses() as $st)
                        <option value="{{ $st }}" @selected(($filters['cooperation_status'] ?? '') === $st)>{{ $st }}</option>
                    @endforeach
                </x-ui.select>
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="primary" size="md">
                    <x-icon name="filter" class="h-4 w-4" style="solid" />
                    Aplicar
                </x-ui.button>
                <x-ui.button variant="secondary" size="md" href="{{ route($routePrefix.'.index') }}">Limpar</x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.table>
        <x-slot name="head">
            <th class="px-5 py-3.5">Nome</th>
            <th class="px-5 py-3.5">Setor</th>
            <th class="px-5 py-3.5">Cidade</th>
            <th class="px-5 py-3.5">Líderes</th>
            <th class="px-5 py-3.5">Jovens</th>
            <th class="px-5 py-3.5">CRM</th>
            <th class="px-5 py-3.5 text-right">Ações</th>
        </x-slot>

        @forelse($churches as $c)
            <tr class="transition odd:bg-white even:bg-gray-50/60 hover:bg-indigo-50/50 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 dark:hover:bg-slate-900/60">
                <td class="px-5 py-3.5 font-semibold text-gray-900 dark:text-white">{{ $c->name }}</td>
                <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300">{{ $c->sector ?? '—' }}</td>
                <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300">{{ $c->city ?? '—' }}</td>
                <td class="px-5 py-3.5 tabular-nums text-gray-700 dark:text-gray-300">{{ $c->leaders_count }}</td>
                <td class="px-5 py-3.5 tabular-nums text-gray-700 dark:text-gray-300">{{ $c->jovens_members_count }}</td>
                <td class="px-5 py-3.5">
                    @php $crm = $c->crm_status ?? ($c->is_active ? \Modules\Igrejas\App\Models\Church::CRM_ATIVA : \Modules\Igrejas\App\Models\Church::CRM_INATIVA); @endphp
                    @if($crm === \Modules\Igrejas\App\Models\Church::CRM_ATIVA)
                        <x-ui.badge tone="success">ativa</x-ui.badge>
                    @elseif($crm === \Modules\Igrejas\App\Models\Church::CRM_INADIMPLENTE)
                        <x-ui.badge tone="warning">inadimplente</x-ui.badge>
                    @else
                        <x-ui.badge tone="neutral">inativa</x-ui.badge>
                    @endif
                </td>
                <td class="px-5 py-3.5 text-right space-x-2">
                    @can('view', $c)
                        <a href="{{ route($routePrefix.'.show', $c) }}" class="text-sm font-bold text-indigo-700 hover:underline dark:text-indigo-400">Ver</a>
                    @endcan
                    @can('update', $c)
                        <a href="{{ route($routePrefix.'.edit', $c) }}" class="text-sm font-semibold text-gray-600 hover:text-indigo-700 dark:text-gray-400 dark:hover:text-indigo-400">Editar</a>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-5 py-16 text-center">
                    <x-icon name="church" class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" style="duotone" />
                    <p class="mt-4 font-semibold text-gray-900 dark:text-white">Nenhuma congregação encontrada</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ajuste os filtros ou registe uma nova igreja.</p>
                </td>
            </tr>
        @endforelse
    </x-ui.table>

    <div>{{ $churches->links() }}</div>
</div>
@endsection
