@extends('painellider::layouts.lideres')

@section('title', 'Documentos')

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
            title="Documentos"
            eyebrow="Painel de líderes · Secretaria"
            description="Documentos públicos disponíveis para consulta ou download." />

        @include('secretaria::painel-operacional.partials.lider-documents-index-inner')
    </x-ui.lideres::page-shell>
@endsection
