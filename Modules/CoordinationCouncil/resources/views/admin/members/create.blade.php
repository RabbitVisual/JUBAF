@extends('admin::components.layouts.master')

@section('title', 'Novo membro do conselho')

@section('content')
    <div class="max-w-xl space-y-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Novo membro</h1>
        <form method="post" action="{{ route('admin.council.members.store') }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6 space-y-4">
            @csrf
            @include('coordinationcouncil::admin.members._form', ['member' => null])
            <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium">Guardar</button>
            <a href="{{ route('admin.council.members.index') }}" class="ml-2 text-sm text-gray-600 hover:underline">Cancelar</a>
        </form>
    </div>
@endsection
