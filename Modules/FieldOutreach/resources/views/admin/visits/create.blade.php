@extends('admin::components.layouts.master')

@section('title', 'Nova visita')

@section('content')
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova visita</h1>
        <form method="post" action="{{ route('admin.field.visits.store') }}" class="bg-white dark:bg-slate-900 rounded-2xl border p-6 space-y-4">
            @csrf
            @include('fieldoutreach::admin.visits._form', ['visit' => null, 'churches' => $churches, 'users' => $users])
            <button type="submit" class="px-4 py-2 rounded-xl bg-teal-600 text-white text-sm font-medium">Guardar</button>
            <a href="{{ route('admin.field.visits.index') }}" class="ml-2 text-sm">Cancelar</a>
        </form>
    </div>
@endsection
