@extends($layout)

@section('title', $minute->title)

@section('content')
<div class="max-w-4xl space-y-4">
    <a href="{{ route($namePrefix.'.atas.index') }}" class="text-sm text-indigo-600">← Atas</a>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $minute->title }}</h1>
    <div class="prose dark:prose-invert border rounded-xl p-6 bg-white dark:bg-slate-800">{!! $minute->body !!}</div>
    @if($minute->attachments->isNotEmpty())
        <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Anexos</h2>
            <ul class="mt-2 space-y-2 text-sm">
                @foreach($minute->attachments as $att)
                    <li class="flex justify-between gap-2">
                        <span>{{ $att->original_name ?? 'Anexo' }} <span class="text-slate-500">({{ str_replace('_', ' ', $att->kind) }})</span></span>
                        <a href="{{ route($namePrefix.'.atas.attachments.download', [$minute, $att]) }}" class="font-semibold text-indigo-600 dark:text-indigo-400">Descarregar</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
