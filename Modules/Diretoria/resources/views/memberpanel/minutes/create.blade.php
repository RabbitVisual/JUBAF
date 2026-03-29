@extends('memberpanel::components.layouts.master')

@section('title', 'Nova ata — Diretoria')
@section('page-title', 'Carregar ata')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <div>
            <a href="{{ route('memberpanel.governance.diretoria.minutes.index') }}" class="text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400">← Voltar às atas</a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">Carregar ata (PDF)</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Apenas PDF, até 15&nbsp;MB. Visível para quem tem acesso a governança no painel.</p>
        </div>

        <form method="post" action="{{ route('memberpanel.governance.diretoria.minutes.store') }}" enctype="multipart/form-data"
            class="space-y-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
            @csrf
            @include('diretoria::admin.minutes._form', ['minute' => null, 'tagLabels' => $tagLabels])
            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-slate-800 sm:flex-row sm:justify-end">
                <a href="{{ route('memberpanel.governance.diretoria.minutes.index') }}" class="inline-flex justify-center rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-300 dark:hover:bg-slate-800">Cancelar</a>
                <button type="submit" class="inline-flex justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Guardar</button>
            </div>
        </form>
    </div>
@endsection
