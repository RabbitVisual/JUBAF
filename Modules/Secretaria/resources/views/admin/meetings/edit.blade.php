@extends('layouts.app')

@section('title', 'Editar reunião')

@section('content')
<div class="max-w-2xl space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar reunião</h1>
    <form action="{{ route($routePrefix.'.update', $meeting) }}" method="POST" class="space-y-4 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-6">
        @csrf @method('PUT')
        @include('secretaria::paineldiretoria.meetings._form', ['meeting' => $meeting])
        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold">Atualizar</button>
    </form>
</div>
@endsection
