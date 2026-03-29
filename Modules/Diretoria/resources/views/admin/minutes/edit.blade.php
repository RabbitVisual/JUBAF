@extends('admin::components.layouts.master')

@section('title', 'Editar ata — Diretoria')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <div>
            <a href="{{ route('admin.diretoria.minutes.index') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">← Voltar às atas</a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">Editar ata</h1>
        </div>

        <form method="post" action="{{ route('admin.diretoria.minutes.update', $minute) }}" enctype="multipart/form-data"
            class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
            @csrf
            @method('PUT')
            @include('diretoria::admin.minutes._form', ['minute' => $minute, 'tagLabels' => $tagLabels])
            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-slate-800 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.diretoria.minutes.index') }}" class="inline-flex justify-center rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-300 dark:hover:bg-slate-800">Cancelar</a>
                <button type="submit" class="inline-flex justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Atualizar</button>
            </div>
        </form>
    </div>
@endsection
