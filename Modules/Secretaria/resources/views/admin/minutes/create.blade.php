@extends('admin::layouts.admin')

@section('title', 'Nova ata')

@section('content')
<div class="max-w-3xl space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova ata</h1>
    @if(isset($templates) && $templates->isNotEmpty())
        <div class="rounded-xl border border-violet-200/80 bg-violet-50/70 p-4 text-sm dark:border-violet-900/40 dark:bg-violet-950/25">
            <p class="font-semibold text-violet-900 dark:text-violet-200">Modelos de ata (editor visual)</p>
            <p class="mt-1 text-xs text-violet-800/90 dark:text-violet-100/80">O modelo preenche o editor abaixo; basta substituir os textos a cinzento/itálico pelos dados reais.</p>
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach($templates as $tpl)
                    <a href="{{ route($routePrefix.'.create', ['template' => $tpl->slug]) }}" class="rounded-lg border border-violet-300 bg-white px-3 py-1 text-xs font-semibold text-violet-900 hover:bg-violet-100 dark:border-violet-700 dark:bg-slate-900 dark:text-violet-100">{{ $tpl->title }}</a>
                @endforeach
            </div>
        </div>
    @endif
    <form action="{{ route($routePrefix.'.store') }}" method="POST" class="space-y-4 bg-white dark:bg-slate-800 border rounded-xl p-6">@csrf
        @include('secretaria::paineldiretoria.minutes._form')
        <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold">Criar rascunho</button>
    </form>
</div>
@endsection
