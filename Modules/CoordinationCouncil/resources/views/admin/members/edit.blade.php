@extends('admin::components.layouts.master')

@section('title', 'Editar membro')

@section('content')
    <div class="max-w-xl space-y-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar membro</h1>
        <form method="post" action="{{ route('admin.council.members.update', $member) }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6 space-y-4">
            @csrf
            @method('PUT')
            @include('coordinationcouncil::admin.members._form', ['member' => $member])
            <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium">Guardar</button>
            <a href="{{ route('admin.council.members.index') }}" class="ml-2 text-sm">Cancelar</a>
        </form>
        <form method="post" action="{{ route('admin.council.members.destroy', $member) }}" onsubmit="return confirm('Remover membro?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:underline">Eliminar</button>
        </form>
    </div>
@endsection
