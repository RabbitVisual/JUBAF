@extends('painellider::layouts.lideres')

@section('title', 'Novo pedido')

@section('lideres_content')
<x-ui.lideres::page-shell class="mx-auto max-w-2xl space-y-8">
    <x-ui.lideres::hero
        variant="surface"
        eyebrow="Igrejas"
        title="Novo pedido (rascunho)"
        description="Preencha os dados; pode guardar como rascunho e enviar depois para a diretoria." />

    <form method="post" action="{{ route('lideres.igrejas.requests.store') }}" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
        @csrf
        @include('igrejas::painellider.requests.partials.payload-fields', [
            'formMode' => 'create',
            'changeRequest' => null,
            'types' => $types,
            'churches' => $churches,
            'leadershipUsers' => $leadershipUsers,
        ])
        <button type="submit" class="w-full rounded-xl bg-emerald-600 py-3 text-sm font-bold text-white hover:bg-emerald-700">Guardar rascunho</button>
    </form>
</x-ui.lideres::page-shell>
@endsection
