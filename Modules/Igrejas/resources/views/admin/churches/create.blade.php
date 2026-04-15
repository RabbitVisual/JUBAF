@extends('layouts.app')

@section('title', 'Nova igreja')

@section('content')
<div class="space-y-6 max-w-3xl">
    <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova congregação</h1>
        <a href="{{ route($routePrefix.'.index') }}" class="text-sm font-semibold text-cyan-700 hover:underline dark:text-cyan-400">Voltar</a>
    </div>

    <form action="{{ route($routePrefix.'.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800 sm:p-8">
            @include('igrejas::paineldiretoria.churches._form', ['church' => $church])
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route($routePrefix.'.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 dark:border-slate-600 dark:text-white dark:hover:bg-slate-700">Cancelar</a>
            <button type="submit" class="rounded-xl bg-cyan-600 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">Guardar</button>
        </div>
    </form>
</div>
@endsection
