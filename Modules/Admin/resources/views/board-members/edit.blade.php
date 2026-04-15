@extends('layouts.app')

@section('title', 'Editar membro da diretoria')

@section('content')
<div class="space-y-6 md:space-y-8 pb-12 max-w-5xl">
    <div class="pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar membro</h1>
        <nav class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.board-members.index') }}" class="hover:text-indigo-600">Diretoria</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white">{{ $boardMember->full_name }}</span>
        </nav>
    </div>

    <form action="{{ route('admin.board-members.update', $boardMember) }}" method="post" enctype="multipart/form-data" class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-6 md:p-8 space-y-8">
        @csrf
        @method('PUT')
        @include('admin::board-members._form', ['routePrefix' => 'admin.board-members', 'boardMember' => $boardMember, 'users' => $users])

        <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-100 dark:border-slate-700">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">Atualizar</button>
            <a href="{{ route('admin.board-members.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-slate-700">Cancelar</a>
        </div>
    </form>
</div>
@endsection
