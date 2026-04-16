@extends('painellider::layouts.lideres')

@section('title', 'Secretaria')

@section('lideres_content')
    <x-ui.lideres::page-shell class="space-y-8 md:space-y-10">
        <x-ui.lideres::hero
            title="Documentação JUBAF"
            eyebrow="Painel de líderes · Secretaria"
            description="Consulta atas publicadas, convocatórias e documentos disponíveis para a tua igreja.">
            <x-slot name="actions">
                <a href="{{ route('lideres.dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-3.5 text-sm font-bold text-emerald-900 shadow-lg transition-all hover:bg-emerald-50 active:scale-[0.98]">
                    <x-icon name="house" class="h-4 w-4" style="duotone" />
                    Início do painel
                </a>
            </x-slot>
        </x-ui.lideres::hero>

        @include('secretaria::painel-operacional.partials.lider-index-inner')
    </x-ui.lideres::page-shell>
@endsection
