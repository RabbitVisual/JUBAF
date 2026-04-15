@extends($layout)

@section('title', 'Nova reunião')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'reunioes'])

    <div class="border-b border-gray-200 pb-6 dark:border-slate-700">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Secretaria · Reuniões</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Nova reunião</h1>
    </div>

    <form action="{{ route($routePrefix.'.store') }}" method="POST" class="mx-auto max-w-2xl space-y-6 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        @csrf
        @include('secretaria::paineldiretoria.meetings._form', ['meeting' => $meeting])
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">Guardar</button>
    </form>
</div>
@endsection
