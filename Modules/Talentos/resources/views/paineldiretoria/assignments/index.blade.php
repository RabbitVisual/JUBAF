@extends($layout)

@section('title', 'Atribuições de talentos')

@section('content')
@php
    use Modules\Talentos\App\Models\TalentAssignment;
    $filterClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-violet-400';
    $statusLabels = [
        TalentAssignment::STATUS_INVITED => ['Convidado', 'bg-amber-100 text-amber-950 ring-amber-200 dark:bg-amber-900/40 dark:text-amber-100 dark:ring-amber-800/50'],
        TalentAssignment::STATUS_CONFIRMED => ['Confirmado', 'bg-emerald-100 text-emerald-950 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-100 dark:ring-emerald-800/50'],
        TalentAssignment::STATUS_DECLINED => ['Declinou', 'bg-slate-200 text-slate-900 ring-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:ring-slate-600'],
    ];
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('talentos::paineldiretoria.partials.subnav', ['active' => 'assignments'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-violet-800 dark:text-violet-400">Diretoria · Talentos</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-600/25">
                    <x-icon name="clipboard-list" class="h-6 w-6" style="duotone" />
                </span>
                Atribuições
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                Convites a funções em eventos do calendário ou missões gerais — filtre por estado.
            </p>
        </div>
        @can('create', \Modules\Talentos\App\Models\TalentAssignment::class)
            <a href="{{ route('diretoria.talentos.assignments.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/25 transition hover:bg-violet-700">
                <x-icon name="plus" class="h-4 w-4" style="solid" />
                Nova atribuição
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="rounded-2xl border border-violet-100/80 bg-gradient-to-br from-violet-50/50 to-white p-4 dark:border-violet-900/30 dark:from-violet-950/20 dark:to-slate-900 sm:p-5">
        <p class="text-xs font-bold uppercase tracking-wide text-violet-900/80 dark:text-violet-400/90">Estados</p>
        <div class="mt-3 flex flex-wrap gap-2">
            @foreach($statusLabels as $pair)
                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold ring-1 {{ $pair[1] }}">{{ $pair[0] }}</span>
            @endforeach
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem] sm:max-w-xs">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Estado</label>
                <select name="status" class="{{ $filterClass }}" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="{{ TalentAssignment::STATUS_INVITED }}" @selected(($filters['status'] ?? '') === TalentAssignment::STATUS_INVITED)>Convidado</option>
                    <option value="{{ TalentAssignment::STATUS_CONFIRMED }}" @selected(($filters['status'] ?? '') === TalentAssignment::STATUS_CONFIRMED)>Confirmado</option>
                    <option value="{{ TalentAssignment::STATUS_DECLINED }}" @selected(($filters['status'] ?? '') === TalentAssignment::STATUS_DECLINED)>Declinou</option>
                </select>
            </div>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($assignments as $row)
            @php
                $st = $statusLabels[$row->status] ?? ['—', 'bg-gray-100 text-gray-800 ring-gray-200 dark:bg-slate-700 dark:text-gray-200 dark:ring-slate-600'];
            @endphp
            <article class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500">#{{ $row->id }}</span>
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold ring-1 {{ $st[1] }}">{{ $st[0] }}</span>
                        </div>
                        <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white">{{ $row->user?->name ?? '—' }}</p>
                        <p class="mt-1 text-sm font-semibold text-violet-800 dark:text-violet-300">{{ $row->role_label }}</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <x-icon name="calendar-days" class="mr-1 inline h-4 w-4 text-violet-500 dark:text-violet-400" style="duotone" />
                            {{ $row->calendarEvent?->title ?? 'Sem evento ligado' }}
                        </p>
                        @if(filled($row->notes))
                            <p class="mt-3 text-xs leading-relaxed text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($row->notes, 280) }}</p>
                        @endif
                    </div>
                    <div class="flex shrink-0 flex-wrap items-center gap-2 sm:flex-col sm:items-stretch">
                        @can('update', $row)
                            <a href="{{ route('diretoria.talentos.assignments.edit', $row) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-gray-200 px-4 py-2 text-sm font-bold text-gray-800 transition hover:border-violet-300 hover:bg-violet-50/50 dark:border-slate-600 dark:text-white dark:hover:border-violet-700">
                                <x-icon name="pen-to-square" class="h-4 w-4" style="duotone" />
                                Editar
                            </a>
                        @endcan
                        @can('delete', $row)
                            <form action="{{ route('diretoria.talentos.assignments.destroy', $row) }}" method="post" onsubmit="return confirm('Remover esta atribuição?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-red-200 bg-red-50/80 px-4 py-2 text-sm font-bold text-red-800 transition hover:bg-red-100 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-200">
                                    <x-icon name="trash" class="h-4 w-4" style="duotone" />
                                    Remover
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white/60 px-6 py-16 text-center dark:border-slate-600 dark:bg-slate-800/40">
                <x-icon name="clipboard-list" class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" style="duotone" />
                <p class="mt-4 font-semibold text-gray-900 dark:text-white">Sem atribuições</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Crie um convite ou altere o filtro de estado.</p>
                @can('create', \Modules\Talentos\App\Models\TalentAssignment::class)
                    <a href="{{ route('diretoria.talentos.assignments.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/25 hover:bg-violet-700">
                        <x-icon name="plus" class="h-4 w-4" style="solid" />
                        Nova atribuição
                    </a>
                @endcan
            </div>
        @endforelse
    </div>

    <div>{{ $assignments->links() }}</div>
</div>
@endsection
