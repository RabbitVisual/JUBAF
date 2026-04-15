@extends('layouts.app')

@section('title', 'Pedido #'.$req->id)

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <a href="{{ route('lideres.igrejas.requests.index') }}" class="text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-400">← Voltar</a>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pedido #{{ $req->id }}</h1>
    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $req->type }} · {{ $req->status }}</p>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
        <pre class="max-h-96 overflow-auto text-xs">{{ json_encode($req->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>

    @if($req->review_notes)
        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase text-slate-500">Resposta da diretoria</p>
            <p class="mt-2 whitespace-pre-wrap text-sm">{{ $req->review_notes }}</p>
        </div>
    @endif

    <div class="flex flex-wrap gap-2">
        @can('update', $req)
            <a href="{{ route('lideres.igrejas.requests.edit', $req) }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-slate-600">Editar rascunho</a>
        @endcan
        @can('submit', $req)
            <form method="post" action="{{ route('lideres.igrejas.requests.submit', $req) }}" class="inline" onsubmit="return confirm('Enviar à diretoria?');">
                @csrf
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">Enviar</button>
            </form>
        @endcan
    </div>
</div>
@endsection
