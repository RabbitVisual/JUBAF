@extends('painellider::layouts.lideres')

@section('title', 'Notificação')

@section('lideres_content')
<x-ui.lideres::page-shell class="max-w-3xl space-y-6 pb-8">
    <x-ui.lideres::hero
        variant="surface"
        eyebrow="JUBAF — Painel de líderes"
        title="{{ $notification->title ?? ($notification->data['title'] ?? 'Notificação') }}"
        description="{{ $notification->created_at->translatedFormat('d \d\e F \d\e Y, H:i') }}">
        <x-slot name="actions">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-md dark:bg-emerald-500">
                <x-icon name="{{ ($notification->data['icon'] ?? null) ?: 'bell' }}" class="h-6 w-6" />
            </span>
        </x-slot>
    </x-ui.lideres::hero>

    <div class="rounded-2xl border border-slate-200 bg-white px-6 py-8 shadow-sm dark:border-slate-800 dark:bg-slate-900 md:px-8">
        <div class="prose prose-slate max-w-none dark:prose-invert">
            <p class="whitespace-pre-wrap leading-relaxed text-slate-700 dark:text-slate-300">{{ $notification->message ?? ($notification->data['message'] ?? 'Sem conteúdo adicional.') }}</p>
            @if(!empty($notification->action_url))
                <p class="mt-8">
                    <a href="{{ $notification->action_url }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition-colors hover:bg-emerald-700">
                        <x-icon name="arrow-up-right-from-square" class="h-4 w-4" />
                        Abrir link
                    </a>
                </p>
            @endif
        </div>
    </div>

    <a href="{{ route('lideres.notificacoes.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:underline dark:text-emerald-400">
        <x-icon name="arrow-left" class="h-4 w-4" />
        Voltar à lista
    </a>
</x-ui.lideres::page-shell>
@endsection
