@extends('layouts.app')

@section('title', 'Editar igreja')

@section('content')
<div class="space-y-6 max-w-3xl">
    <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar {{ $church->name }}</h1>
        <a href="{{ route($routePrefix.'.show', $church) }}" class="text-sm font-semibold text-cyan-700 hover:underline dark:text-cyan-400">Ficha</a>
    </div>

    <form action="{{ route($routePrefix.'.update', $church) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800 sm:p-8">
            @include('igrejas::paineldiretoria.churches._form', ['church' => $church])
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route($routePrefix.'.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 dark:border-slate-600 dark:text-white dark:hover:bg-slate-700">Cancelar</a>
            <button type="submit" class="rounded-xl bg-cyan-600 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">Guardar</button>
        </div>
    </form>

    @can('delete', $church)
        <form action="{{ route($routePrefix.'.destroy', $church) }}" method="POST" onsubmit="return confirm('Remover esta igreja?');" class="pt-4 border-t border-gray-200 dark:border-slate-700">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg dark:border-red-900/50">Eliminar congregação</button>
        </form>
    @endcan
</div>
@endsection
