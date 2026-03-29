@extends('memberpanel::components.layouts.master')

@section('page-title', 'Visita — '.$visit->church?->name)

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => $visit->church?->name ?? 'Visita',
        'subtitle' => $visit->visited_at?->translatedFormat('d \\d\\e F \\d\\e Y').' · '.($visit->creator?->name ?? ''),
        'badge' => 'Campo',
    ])
        @slot('actions')
            @if (!empty($canManage) && $canManage)
                <a href="{{ route('memberpanel.field.visits.edit', $visit) }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold">
                    Editar
                </a>
            @endif
        @endslot

        <a href="{{ route('memberpanel.field.visits.index') }}"
            class="inline-flex items-center text-sm font-semibold text-teal-600 dark:text-teal-400 hover:underline mb-6">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Visitas
        </a>

        <div
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 space-y-6 text-sm shadow-sm">
            @if ($visit->notes)
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Notas</h2>
                    <div class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $visit->notes }}</div>
                </div>
            @endif
            @if ($visit->next_steps)
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Próximos passos</h2>
                    <div class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $visit->next_steps }}</div>
                </div>
            @endif
            @if ($visit->attendees->isNotEmpty())
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Participantes</h2>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
                        @foreach ($visit->attendees as $u)
                            <li>{{ $u->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @if (!empty($canManage) && $canManage)
            <form method="post" action="{{ route('memberpanel.field.visits.destroy', $visit) }}" class="mt-8"
                onsubmit="return confirm('Remover esta visita?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:underline">Eliminar visita</button>
            </form>
        @endif
    @endcomponent
@endsection
