@extends($layout)

@section('title', 'Convocatórias')

@section('content')
<div class="max-w-4xl space-y-4">
    <div class="flex justify-between"><h1 class="text-2xl font-bold text-slate-900 dark:text-white">Convocatórias</h1><a href="{{ route($namePrefix.'.index') }}" class="text-sm text-indigo-600">Secretaria</a></div>
    <ul class="space-y-2">@foreach($convocations as $c)<li class="border dark:border-slate-700 rounded-lg p-3"><a href="{{ route($namePrefix.'.convocatorias.show', $c) }}" class="font-medium text-indigo-600">{{ $c->title }}</a><span class="text-sm text-slate-500 ml-2">{{ $c->assembly_at->format('d/m/Y H:i') }}</span></li>@endforeach</ul>
    {{ $convocations->links() }}
</div>
@endsection
