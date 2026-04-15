@extends('layouts.public-site')

@section('title', 'Publicações da Secretaria')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10">
    @if(config('secretaria.integrations.homepage_public_secretaria_cta', true))
        <div class="mb-8 rounded-2xl border border-emerald-200/80 bg-emerald-50/80 p-5 dark:border-emerald-900/40 dark:bg-emerald-950/30">
            <p class="text-sm font-bold uppercase tracking-wide text-emerald-800 dark:text-emerald-200">JUBAF · Transparência</p>
            <h1 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">Publicações institucionais</h1>
            <p class="mt-2 text-sm text-emerald-900/90 dark:text-emerald-100/85">Atas publicadas e convocatórias oficiais. Líderes e jovens com conta podem ver conteúdo adicional nos respetivos painéis.</p>
        </div>
    @else
        <h1 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">Publicações institucionais</h1>
    @endif

    <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Atas</h2>
        <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-300">
            @forelse($minutes as $m)
                <li class="flex flex-wrap justify-between gap-2 border-b border-gray-100 pb-2 last:border-0 dark:border-slate-700">
                    <span>{{ $m->title }}</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $m->published_at?->format('d/m/Y') }}</span>
                </li>
            @empty
                <li class="text-gray-500 dark:text-gray-400">Nenhuma publicada.</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-6 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Convocatórias</h2>
        <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-300">
            @forelse($convocations as $c)
                <li class="flex flex-wrap justify-between gap-2 border-b border-gray-100 pb-2 last:border-0 dark:border-slate-700">
                    <span>{{ $c->title }}</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $c->assembly_at->format('d/m/Y H:i') }}</span>
                </li>
            @empty
                <li class="text-gray-500 dark:text-gray-400">Nenhuma.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
