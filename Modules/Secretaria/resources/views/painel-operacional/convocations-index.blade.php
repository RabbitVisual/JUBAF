@extends('painellider::layouts.lideres')

@section('title', 'Convocatórias')

@section('lideres_content')
    <x-ui.lideres::page-shell class="space-y-8 md:space-y-10">
        <a
            href="{{ route($namePrefix.'.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition-all hover:gap-2.5 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300"
        >
            <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
            Voltar à secretaria
        </a>

        <x-ui.lideres::hero
            title="Convocatórias"
            eyebrow="Painel de líderes · Secretaria"
            description="Assembleias e convocatórias publicadas pela secretaria." />

        @include('secretaria::painel-operacional.partials.lider-convocations-index-inner')
    </x-ui.lideres::page-shell>
@endsection
