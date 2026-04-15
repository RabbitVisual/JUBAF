@extends('admin::layouts.admin')

@section('title', 'Segurança e acesso')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Segurança e acesso</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Central de utilizadores, funções e permissões (módulo Permisao).</p>
    </div>
    <div class="grid gap-4 sm:grid-cols-3">
        <a href="{{ route('admin.users.index') }}" class="block rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
            <h2 class="font-semibold text-gray-900 dark:text-white">Utilizadores</h2>
            <p class="text-xs text-gray-500 mt-1">Contas e estado</p>
        </a>
        <a href="{{ route('admin.roles.index') }}" class="block rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
            <h2 class="font-semibold text-gray-900 dark:text-white">Funções</h2>
            <p class="text-xs text-gray-500 mt-1">Roles Spatie</p>
        </a>
        <a href="{{ route('admin.permissions.index') }}" class="block rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
            <h2 class="font-semibold text-gray-900 dark:text-white">Permissões</h2>
            <p class="text-xs text-gray-500 mt-1">Matriz por módulo</p>
        </a>
    </div>
</div>
@endsection
