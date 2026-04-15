@extends($layout)

@section('title', 'Carregar documento')

@section('content')
@php
    $ic = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20';
    $lc = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'arquivo'])

    <div class="border-b border-gray-200 pb-6 dark:border-slate-700">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Secretaria · Arquivo</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Carregar documento</h1>
    </div>

    <form action="{{ route($routePrefix.'.store') }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-xl space-y-5 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        @csrf
        <div>
            <label class="{{ $lc }}">Título *</label>
            <input type="text" name="title" required class="{{ $ic }}">
        </div>
        <div>
            <label class="{{ $lc }}">Categoria *</label>
            <select name="category" class="{{ $ic }}">
                @foreach(['Estatuto', 'Ofício', 'Circular', 'Outros'] as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="{{ $lc }}">Visibilidade *</label>
            <select name="is_public" class="{{ $ic }}">
                <option value="0">Interno (diretoria/secretaria)</option>
                <option value="1">Público para pastor e líder</option>
            </select>
        </div>
        <div>
            <label class="{{ $lc }}">Igreja (opcional)</label>
            <select name="church_id" class="{{ $ic }}">
                <option value="">—</option>
                @foreach($churches as $ch)
                    <option value="{{ $ch->id }}">{{ $ch->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="{{ $lc }}">Ficheiro *</label>
            <input type="file" name="file" required class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-800 hover:file:bg-emerald-100 dark:text-gray-400 dark:file:bg-emerald-950/50 dark:file:text-emerald-200">
        </div>
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">Enviar</button>
    </form>
</div>
@endsection
