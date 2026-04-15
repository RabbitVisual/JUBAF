@extends($layout)

@section('title', 'Secretaria')

@section('content')
<div class="space-y-8 max-w-4xl">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Documentação JUBAF</h1>
        <a href="{{ route($homeRoute) }}" class="text-sm text-slate-600 dark:text-slate-400">Início</a>
    </div>
    <div class="grid sm:grid-cols-3 gap-3">
        <a href="{{ route($namePrefix.'.atas.index') }}" class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-sm font-medium hover:border-indigo-400">Atas publicadas</a>
        <a href="{{ route($namePrefix.'.convocatorias.index') }}" class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-sm font-medium hover:border-indigo-400">Convocatórias</a>
        <a href="{{ route($namePrefix.'.documentos.index') }}" class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-sm font-medium hover:border-indigo-400">Documentos</a>
    </div>
    <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-5 bg-white dark:bg-slate-800">
        <h2 class="font-semibold mb-2">Últimas atas</h2>
        <ul class="text-sm space-y-1">@forelse($minutes as $m)<li><a href="{{ route($namePrefix.'.atas.show', $m) }}" class="text-indigo-600">{{ $m->title }}</a></li>@empty<li class="text-slate-500">Nenhuma.</li>@endforelse</ul>
    </div>
    <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-5 bg-white dark:bg-slate-800">
        <h2 class="font-semibold mb-2">Próximas assembleias</h2>
        <ul class="text-sm space-y-1">@forelse($convocations as $c)<li><a href="{{ route($namePrefix.'.convocatorias.show', $c) }}" class="text-indigo-600">{{ $c->title }}</a> — {{ $c->assembly_at->format('d/m/Y H:i') }}</li>@empty<li class="text-slate-500">Nenhuma.</li>@endforelse</ul>
    </div>
</div>
@endsection
