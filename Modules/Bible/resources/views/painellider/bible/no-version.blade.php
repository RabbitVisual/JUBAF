@extends('painellider::layouts.lideres')

@section('title', 'Bíblia Digital')

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <span class="text-slate-600 dark:text-slate-300">Bíblia</span>
@endsection

@section('lideres_content')
<x-ui.lideres::page-shell class="flex min-h-[60vh] flex-col items-center justify-center">
    <x-ui.lideres::hero
        variant="surface"
        eyebrow="Sistema indisponível"
        title="Bíblia digital"
        description="Nenhuma versão da Bíblia (NVI, ACF, etc.) foi importada para o sistema ainda. Contacte a administração.">
        <x-slot name="actions">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                <x-icon name="triangle-exclamation" class="h-6 w-6" />
            </span>
        </x-slot>
    </x-ui.lideres::hero>

    <div class="w-full max-w-lg rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-xl dark:border-slate-800 dark:bg-slate-900">
        <a href="{{ route('lideres.dashboard') }}"
            class="inline-flex w-full items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-gray-700 shadow-sm transition-all hover:border-gray-300 hover:bg-gray-50 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700">
            <x-icon name="house" class="mr-2 h-4 w-4" />
            Voltar ao dashboard
        </a>
    </div>
</x-ui.lideres::page-shell>
@endsection
