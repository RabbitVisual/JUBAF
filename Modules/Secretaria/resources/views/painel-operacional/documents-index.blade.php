@extends($layout)

@section('title', 'Documentos')

@section('content')
<div class="max-w-4xl space-y-4">
    <div class="flex justify-between"><h1 class="text-2xl font-bold text-slate-900 dark:text-white">Documentos</h1><a href="{{ route($namePrefix.'.index') }}" class="text-sm text-indigo-600">Secretaria</a></div>
    <ul class="space-y-2">@forelse($documents as $d)<li class="flex justify-between border dark:border-slate-700 rounded-lg p-3"><span>{{ $d->title }}</span>@can('download', $d)<a href="{{ route($namePrefix.'.documentos.download', $d) }}" class="text-indigo-600 text-sm">Download</a>@endcan</li>@empty<li class="text-slate-500">Nenhum documento.</li>@endforelse</ul>
    {{ $documents->links() }}
</div>
@endsection
