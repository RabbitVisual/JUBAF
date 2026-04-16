@extends('paineljovens::layouts.jovens')

@section('title', $minute->title)

@section('jovens_content')
    <x-ui.jovens::page-shell class="space-y-6 pb-8 md:space-y-8">
        @include('secretaria::painel-operacional.partials.minute-show-inner', ['jovensPanel' => true])
    </x-ui.jovens::page-shell>
@endsection
