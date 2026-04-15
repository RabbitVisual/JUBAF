@extends($layout)

@section('title', ($mode === 'create' ? 'Nova competência' : 'Editar competência'))

@section('content')
@php
    $fieldClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-violet-400';
@endphp
<div class="mx-auto max-w-lg space-y-8 pb-10">
    @include('talentos::paineldiretoria.partials.subnav', ['active' => 'taxonomy'])

    <div>
        <a href="{{ route('diretoria.talentos.competencias.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-violet-700 hover:underline dark:text-violet-400">
            <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
            Competências
        </a>
        <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $mode === 'create' ? 'Nova competência' : 'Editar competência' }}</h1>
    </div>

    <form method="post" action="{{ $mode === 'create' ? route('diretoria.talentos.competencias.store') : route('diretoria.talentos.competencias.update', $skill) }}" class="space-y-6 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        @csrf
        @if($mode === 'edit')
            @method('PUT')
        @endif

        <div>
            <label for="name" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Nome</label>
            <input type="text" id="name" name="name" value="{{ old('name', $skill->name) }}" required class="{{ $fieldClass }}" maxlength="160">
            @error('name')
                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-gray-100 pt-6 dark:border-slate-700 sm:flex-row sm:justify-end">
            <a href="{{ route('diretoria.talentos.competencias.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 dark:border-slate-600 dark:text-white dark:hover:bg-slate-700">Cancelar</a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/25 transition hover:bg-violet-700">
                <x-icon name="check" class="h-4 w-4" style="solid" />
                Guardar
            </button>
        </div>
    </form>
</div>
@endsection
