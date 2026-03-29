@extends('memberpanel::components.layouts.master')

@section('page-title', $communication->title)

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => $communication->title,
        'subtitle' => $communication->published_at
            ? $communication->published_at->translatedFormat('d \\d\\e F \\d\\e Y')
            : 'Rascunho',
        'badge' => 'Governança',
    ])
        @slot('actions')
            @if (!empty($canManage) && $canManage)
                <a href="{{ route('memberpanel.governance.communications.edit', $communication) }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold">
                    <x-icon name="pen-to-square" style="duotone" class="w-4 h-4 mr-2" />
                    Editar
                </a>
            @endif
        @endslot

        <a href="{{ route('memberpanel.governance.communications.index') }}"
            class="inline-flex items-center text-sm font-semibold text-violet-600 dark:text-violet-400 hover:underline mb-6">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Comunicados
        </a>

        <article
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 shadow-sm">
            @if ($communication->summary)
                <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg">{{ $communication->summary }}</p>
            @endif
            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 whitespace-pre-wrap">
                {{ $communication->body }}</div>

            @if ($communication->is_published && $communication->published_at)
                <a href="{{ route('public.transparency.communication', $communication) }}" target="_blank"
                    class="inline-flex items-center mt-8 text-sm font-bold text-violet-600 dark:text-violet-400 hover:underline">
                    <x-icon name="arrow-up-right" class="w-4 h-4 mr-1" /> Abrir página pública
                </a>
            @endif
        </article>

        @if (!empty($canManage) && $canManage)
            <form method="post" action="{{ route('memberpanel.governance.communications.destroy', $communication) }}" class="mt-8"
                onsubmit="return confirm('Remover este comunicado?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:underline">Eliminar comunicado</button>
            </form>
        @endif
    @endcomponent
@endsection
