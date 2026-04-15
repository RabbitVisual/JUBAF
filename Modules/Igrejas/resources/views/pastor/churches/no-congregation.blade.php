@extends('layouts.app')

@section('title', 'Congregação')

@section('content')
<div class="max-w-lg mx-auto rounded-2xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/90 dark:bg-amber-950/30 p-8 text-center">
    <x-icon name="church" class="w-12 h-12 text-amber-600 dark:text-amber-400 mx-auto mb-4" />
    <h1 class="text-xl font-bold text-amber-950 dark:text-amber-100">Sem congregação vinculada</h1>
    <p class="text-sm text-amber-900/85 dark:text-amber-100/80 mt-3 leading-relaxed">
        O teu perfil de pastor ainda não está associado a uma igreja na JUBAF. Quando a secretaria definir a congregação, verás aqui apenas os dados dessa igreja (nunca de outras).
    </p>
    <p class="text-xs text-amber-800/70 dark:text-amber-200/60 mt-4">
        A listagem global de todas as congregações é exclusiva da diretoria e do super-admin.
    </p>
</div>
@endsection
