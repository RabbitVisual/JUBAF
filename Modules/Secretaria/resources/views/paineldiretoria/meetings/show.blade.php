@extends($layout)

@section('title', 'Reunião')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'reunioes'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Secretaria · Reuniões</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">{{ $meeting->title ?: $meeting->type }}</h1>
        </div>
        <a href="{{ route($routePrefix.'.index') }}" class="inline-flex shrink-0 text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-400">← Voltar à lista</a>
    </div>

    <dl class="mx-auto max-w-3xl space-y-4 rounded-2xl border border-gray-200/90 bg-white p-6 text-sm shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="border-b border-gray-100 pb-3 dark:border-slate-700"><dt class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Início</dt><dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $meeting->starts_at->format('d/m/Y H:i') }}</dd></div>
        <div class="border-b border-gray-100 pb-3 dark:border-slate-700"><dt class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Estado</dt><dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $meeting->status }}</dd></div>
        @if($meeting->location)
            <div><dt class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Local</dt><dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $meeting->location }}</dd></div>
        @endif
    </dl>
    @can('update', $meeting)
        <div class="mx-auto max-w-3xl">
            <a href="{{ route($routePrefix.'.edit', $meeting) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">Editar</a>
        </div>
    @endcan
</div>
@endsection
