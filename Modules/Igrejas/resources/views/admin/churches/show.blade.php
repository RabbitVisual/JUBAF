@extends('admin::layouts.admin')

@section('title', $church->name)

@section('content')
@php
    $leadersRoster = \App\Models\User::query()->role('lider')->where('church_id', $church->id)->orderBy('name')->limit(20)->get(['name', 'email', 'phone', 'active']);
    $jovensRoster = \App\Models\User::query()->role('jovens')->where('church_id', $church->id)->orderBy('name')->limit(20)->get(['name', 'email', 'active']);
@endphp
<div class="space-y-8 max-w-5xl">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $church->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $church->city ?? 'Sem cidade' }} · {{ $church->slug }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route($routePrefix.'.index') }}" class="text-sm font-semibold text-gray-600 hover:text-cyan-700 dark:text-gray-400 dark:hover:text-cyan-400">Lista</a>
            @can('update', $church)
                <a href="{{ route($routePrefix.'.edit', $church) }}" class="rounded-xl bg-cyan-600 px-4 py-2 text-sm font-bold text-white shadow-md shadow-cyan-600/25 hover:bg-cyan-700">Editar</a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
            <p class="text-xs font-semibold uppercase text-gray-500">Líderes</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $church->leaders_count }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
            <p class="text-xs font-semibold uppercase text-gray-500">Jovens</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $church->jovens_members_count }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4">
            <p class="text-xs font-semibold uppercase text-gray-500">Estado</p>
            <p class="mt-1">
                @if($church->is_active)
                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Ativa</span>
                @else
                    <span class="text-slate-600 dark:text-slate-400 font-semibold">Inativa</span>
                @endif
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 space-y-3 text-sm">
        @if($church->address)
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Endereço:</span> {{ $church->address }}</p>
        @endif
        @if($church->phone)
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Telefone:</span> {{ $church->phone }}</p>
        @endif
        @if($church->email)
            <p><span class="font-medium text-gray-700 dark:text-gray-300">E-mail:</span> {{ $church->email }}</p>
        @endif
        @if($church->joined_at)
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Filiação:</span> {{ $church->joined_at->format('d/m/Y') }}</p>
        @endif
        @if($church->asbaf_notes)
            <div class="pt-2 border-t border-gray-100 dark:border-slate-700">
                <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">Notas</p>
                <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $church->asbaf_notes }}</p>
            </div>
        @endif
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Líderes (amostra)</h2>
            </div>
            <ul class="divide-y divide-gray-100 dark:divide-slate-700 max-h-80 overflow-y-auto text-sm">
                @forelse($leadersRoster as $u)
                    <li class="px-4 py-3">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $u->name }}</p>
                        <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $u->email }}</p>
                    </li>
                @empty
                    <li class="px-4 py-6 text-center text-gray-500 text-sm">Nenhum líder vinculado.</li>
                @endforelse
            </ul>
        </div>
        <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Jovens (amostra)</h2>
            </div>
            <ul class="divide-y divide-gray-100 dark:divide-slate-700 max-h-80 overflow-y-auto text-sm">
                @forelse($jovensRoster as $u)
                    <li class="px-4 py-3">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $u->name }}</p>
                        <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $u->email }}</p>
                    </li>
                @empty
                    <li class="px-4 py-6 text-center text-gray-500 text-sm">Nenhum jovem vinculado.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <p class="text-xs text-gray-500 dark:text-gray-400">Para gestão completa de utilizadores, use <a href="{{ route('admin.users.index') }}" class="font-semibold text-cyan-700 hover:underline dark:text-cyan-400">Utilizadores</a> no admin.</p>
</div>
@endsection
