@extends('layouts.app')

@section('title', 'Editar jovem')

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <a href="{{ route('lideres.congregacao.index') }}" class="text-emerald-700 dark:text-emerald-300 hover:underline">Congregação</a>
    <span class="text-slate-400">/</span>
    <span class="text-emerald-700 dark:text-emerald-300">Editar</span>
@endsection

@section('content')
<div class="mx-auto max-w-2xl space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Editar jovem</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ $youth->name }} — conta continua associada à tua igreja.</p>
    </div>

    <form method="post" action="{{ route('lideres.congregacao.jovens.update', $youth) }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="first_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nome</label>
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $youth->first_name) }}" required
                    class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
                @error('first_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Apelido</label>
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $youth->last_name) }}"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
                @error('last_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email', $youth->email) }}" required
                class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Telemóvel (opcional)</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone', $youth->phone) }}"
                class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="active" value="1" class="rounded border-slate-300 text-emerald-600"
                    @checked(old('active', $youth->active))>
                <span class="text-sm font-medium text-slate-800 dark:text-slate-200">Conta activa</span>
            </label>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-emerald-700">
                Guardar alterações
            </button>
            <a href="{{ route('lideres.congregacao.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">Voltar</a>
        </div>
    </form>
</div>
@endsection
