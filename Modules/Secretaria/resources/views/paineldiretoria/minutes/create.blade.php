@extends($layout)

@section('title', 'Nova ata')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'atas'])

    <div class="border-b border-gray-200 pb-6 dark:border-slate-700">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Secretaria · Atas</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Nova ata</h1>
    </div>

    @if(isset($templates) && $templates->isNotEmpty())
        <div class="mx-auto max-w-3xl rounded-2xl border border-emerald-200/80 bg-emerald-50/60 p-4 text-sm dark:border-emerald-900/40 dark:bg-emerald-950/20">
            <p class="font-bold text-emerald-900 dark:text-emerald-200">Modelos de ata</p>
            <p class="mt-1 text-emerald-800/90 dark:text-emerald-100/80">Escolha um modelo: o texto aparece no <strong>editor visual</strong> abaixo — basta clicar e substituir os exemplos pelos dados reais. Não precisa de código.</p>
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach($templates as $tpl)
                    <a href="{{ route($routePrefix.'.create', ['template' => $tpl->slug]) }}" class="inline-flex rounded-lg border border-emerald-300 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-900 shadow-sm transition hover:bg-emerald-100 dark:border-emerald-700 dark:bg-slate-900 dark:text-emerald-100 dark:hover:bg-emerald-900/30">{{ $tpl->title }}</a>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route($routePrefix.'.store') }}" method="POST" class="mx-auto max-w-3xl space-y-6 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        @csrf
        @include('secretaria::paineldiretoria.minutes._form')
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">Criar rascunho</button>
    </form>
</div>
@endsection
