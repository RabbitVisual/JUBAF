@extends($layout)

@section('title', $convocation->title)

@section('content')
<div class="max-w-4xl space-y-4">
    <a href="{{ route($namePrefix.'.convocatorias.index') }}" class="text-sm text-indigo-600">← Convocatórias</a>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $convocation->title }}</h1>
    <p class="text-sm text-slate-600">{{ $convocation->assembly_at->format('d/m/Y H:i') }}</p>
    <div class="prose dark:prose-invert border rounded-xl p-6 bg-white dark:bg-slate-800">{!! $convocation->body !!}</div>
</div>
@endsection
