@extends('admin::layouts.admin')

@section('title', 'Segurança e acesso')

@section('content')
<div class="space-y-5 max-w-5xl font-sans">
    <div class="pb-3 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Segurança e acesso</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">RBAC (Spatie): utilizadores, roles e permissões — sem seeders no terminal.</p>
    </div>
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('admin.users.index') }}" class="block rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 hover:border-indigo-400 dark:hover:border-indigo-600 transition-colors">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Utilizadores</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">Contas e estado</p>
        </a>
        <a href="{{ route('admin.roles.index') }}" class="block rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 hover:border-indigo-400 dark:hover:border-indigo-600 transition-colors">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Funções (roles)</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">Criar níveis e marcar permissões</p>
        </a>
        <a href="{{ route('admin.permissions.index') }}" class="block rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 hover:border-indigo-400 dark:hover:border-indigo-600 transition-colors">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Permissões</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">CRUD de chaves (ex. módulo.ação)</p>
        </a>
        @if(\Illuminate\Support\Facades\Route::has('admin.ops.index'))
        <a href="{{ route('admin.ops.index') }}" class="block rounded-lg border border-rose-200 dark:border-rose-900/50 bg-rose-50/50 dark:bg-rose-950/20 p-4 hover:border-rose-400 transition-colors sm:col-span-2 lg:col-span-1">
            <h2 class="text-sm font-semibold text-rose-900 dark:text-rose-100">Ops (sistema)</h2>
            <p class="text-[11px] text-rose-700/80 dark:text-rose-300/80 mt-0.5">Filas, logs, backups — TI</p>
        </a>
        @endif
        @if(\Illuminate\Support\Facades\Route::has('admin.backup.index'))
        <a href="{{ route('admin.backup.index') }}" class="block rounded-lg border border-cyan-200 dark:border-cyan-900/50 bg-cyan-50/50 dark:bg-cyan-950/20 p-4 hover:border-cyan-400 transition-colors">
            <h2 class="text-sm font-semibold text-cyan-900 dark:text-cyan-100">Backups DB</h2>
            <p class="text-[11px] text-cyan-800/80 dark:text-cyan-300/80 mt-0.5">Criar / descarregar</p>
        </a>
        @endif
    </div>
</div>
@endsection
