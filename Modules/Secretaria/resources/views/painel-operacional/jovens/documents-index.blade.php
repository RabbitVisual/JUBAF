@extends('paineljovens::layouts.jovens')

@section('title', 'Documentos')

@section('jovens_content')
    <x-ui.jovens::page-shell class="space-y-8 md:space-y-10">
        <a
            href="{{ route($namePrefix.'.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 transition-all hover:gap-2.5 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
        >
            <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
            Voltar à secretaria
        </a>

        @include('secretaria::painel-operacional.partials.jovens-secretaria-hero', [
            'title' => 'Documentos',
            'description' => 'Documentos públicos disponíveis para consulta ou download.',
            'eyebrow' => 'Unijovem · Secretaria',
        ])

        @include('secretaria::painel-operacional.partials.documents-index-inner', ['jovensPanel' => true])
    </x-ui.jovens::page-shell>
@endsection
