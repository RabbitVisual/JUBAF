@extends('layouts.app')

@section('title', 'Novo pedido')

@section('content')
<div class="mx-auto max-w-2xl space-y-8">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Novo pedido (rascunho)</h1>

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
</div>
@endsection
