@extends('painellider::layouts.lideres')

@section('title', 'Editar pedido #'.$req->id)

@section('lideres_content')
<x-ui.lideres::page-shell class="mx-auto max-w-2xl space-y-8">
    <x-ui.lideres::hero
        variant="surface"
        eyebrow="Igrejas"
        title="Editar rascunho #{{ $req->id }}"
        description="Atualize os dados e guarde, ou envie para análise quando estiver pronto." />

    <form method="post" action="{{ route('lideres.igrejas.requests.update', $req) }}" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
        @csrf
        @method('PUT')
        @include('igrejas::painellider.requests.partials.payload-fields', [
            'formMode' => 'edit',
            'changeRequest' => $req,
            'types' => $types,
            'churches' => $churches,
            'leadershipUsers' => $leadershipUsers,
        ])
        <button type="submit" class="w-full rounded-xl bg-slate-800 py-3 text-sm font-bold text-white hover:bg-slate-900 dark:bg-slate-600 dark:hover:bg-slate-500">Guardar alterações</button>
    </form>

    @can('submit', $req)
        <form method="post" action="{{ route('lideres.igrejas.requests.submit', $req) }}" onsubmit="return confirm('Enviar este pedido à diretoria?');">
            @csrf
            <button type="submit" class="w-full rounded-xl bg-emerald-600 py-3 text-sm font-bold text-white hover:bg-emerald-700">Enviar para análise</button>
        </form>
    @endcan
</x-ui.lideres::page-shell>
@endsection
