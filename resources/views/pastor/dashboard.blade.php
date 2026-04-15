@extends('layouts.app')

@section('title', 'Início')

@section('content')
<div class="max-w-2xl mx-auto text-center space-y-6">
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-10 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Painel Pastor</h1>
        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-8">
            Área de supervisão pastoral e apoio às congregações JUBAF. Consulta as congregações registadas, contactos dos líderes e estatísticas de participação na plataforma.
        </p>
        @if(module_enabled('Igrejas') && Route::has('pastor.igrejas.index'))
            <a href="{{ route('pastor.igrejas.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-sky-600 text-white text-sm font-bold hover:bg-sky-700 transition-colors">
                <x-module-icon module="Igrejas" class="h-5 w-5 text-white" />
                Ver congregações JUBAF
            </a>
        @endif
    </div>
</div>
@endsection
