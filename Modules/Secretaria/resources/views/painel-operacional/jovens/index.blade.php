@extends('paineljovens::layouts.jovens')

@section('title', 'Secretaria')

@section('jovens_content')
    <x-ui.jovens::page-shell class="space-y-8 md:space-y-10">
        @include('secretaria::painel-operacional.partials.jovens-secretaria-hero', [
            'title' => 'Documentação JUBAF',
            'description' => 'Consulta atas publicadas, convocatórias e documentos disponíveis para a tua realidade.',
            'showHomeLink' => true,
        ])
        @include('secretaria::painel-operacional.partials.index-inner', ['jovensPanel' => true])
    </x-ui.jovens::page-shell>
@endsection
