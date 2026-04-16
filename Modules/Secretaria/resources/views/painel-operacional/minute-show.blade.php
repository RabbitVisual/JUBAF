@extends('painellider::layouts.lideres')

@section('title', $minute->title)

@section('lideres_content')
    <x-ui.lideres::page-shell class="space-y-6 pb-8 md:space-y-8">
        @include('secretaria::painel-operacional.partials.lider-minute-show-inner')
    </x-ui.lideres::page-shell>
@endsection
