@extends($layout)

@section('title', 'Atas')

@section('content')
<div class="max-w-4xl space-y-4">
    <div class="flex justify-between"><h1 class="text-2xl font-bold text-slate-900 dark:text-white">Atas publicadas</h1><a href="{{ route($namePrefix.'.index') }}" class="text-sm text-indigo-600">Secretaria</a></div>
    <ul class="space-y-2">@foreach($minutes as $m)<li class="border dark:border-slate-700 rounded-lg p-3"><a href="{{ route($namePrefix.'.atas.show', $m) }}" class="font-medium text-indigo-600">{{ $m->title }}</a><span class="text-xs text-slate-500 ml-2">{{ $m->published_at?->format('d/m/Y') }}</span></li>@endforeach</ul>
    {{ $minutes->links() }}
</div>
@endsection
