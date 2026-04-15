@extends('layouts.app')

@section('title', $church->name)

@section('content')
<div class="space-y-8 max-w-4xl">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <a href="{{ route('pastor.igrejas.index') }}" class="text-sm text-sky-600 dark:text-sky-400 font-medium hover:underline mb-2 inline-block">← Todas as congregações</a>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $church->name }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $church->city ?? 'Sem cidade' }} · {{ $church->slug }}</p>
        </div>
        @if($church->is_active)
            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200 shrink-0">Ativa</span>
        @else
            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300 shrink-0">Inativa</span>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
            <p class="text-xs font-bold uppercase text-slate-500">Líderes</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $church->leaders_count }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
            <p class="text-xs font-bold uppercase text-slate-500">Jovens</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $church->jovens_members_count }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
            <p class="text-xs font-bold uppercase text-slate-500">Total contas</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $church->users_count }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-4 text-sm">
        <h2 class="font-bold text-slate-900 dark:text-white">Contactos institucionais</h2>
        @if($church->address)<p><span class="font-medium text-slate-600 dark:text-slate-400">Endereço:</span> {{ $church->address }}</p>@endif
        @if($church->phone)<p><span class="font-medium text-slate-600 dark:text-slate-400">Telefone:</span> <a href="tel:{{ preg_replace('/\s+/', '', $church->phone) }}" class="text-sky-600 dark:text-sky-400 font-medium">{{ $church->phone }}</a></p>@endif
        @if($church->email)<p><span class="font-medium text-slate-600 dark:text-slate-400">E-mail:</span> <a href="mailto:{{ $church->email }}" class="text-sky-600 dark:text-sky-400 font-medium break-all">{{ $church->email }}</a></p>@endif
        @if($church->joined_at)<p><span class="font-medium text-slate-600 dark:text-slate-400">Filiação:</span> {{ $church->joined_at->format('d/m/Y') }}</p>@endif
        @if($church->asbaf_notes)
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <p class="font-medium text-slate-600 dark:text-slate-400 mb-1">Notas</p>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $church->asbaf_notes }}</p>
            </div>
        @endif
    </div>

    @if($leaders->isNotEmpty())
        <div>
            <h2 class="font-bold text-slate-900 dark:text-white mb-3">Líderes de jovens (contacto)</h2>
            <ul class="space-y-3">
                @foreach($leaders as $l)
                    <li class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 flex flex-col sm:flex-row sm:justify-between gap-2">
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $l->name }}</span>
                        <div class="text-sm space-y-1 sm:text-right">
                            @if($l->email)<a href="mailto:{{ $l->email }}" class="block text-sky-600 dark:text-sky-400 hover:underline">{{ $l->email }}</a>@endif
                            @if($l->phone)<a href="tel:{{ preg_replace('/\s+/', '', $l->phone) }}" class="block text-slate-600 dark:text-slate-400">{{ $l->phone }}</a>@endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
