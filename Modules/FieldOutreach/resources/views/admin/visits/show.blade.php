@extends('admin::components.layouts.master')

@section('title', 'Visita')

@section('content')
    <div class="max-w-2xl space-y-6">
        <a href="{{ route('admin.field.visits.index') }}" class="text-sm text-blue-600 hover:underline">← Lista</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $visit->church?->name }}</h1>
        <p class="text-gray-600">{{ $visit->visited_at?->format('d/m/Y') }} · {{ $visit->creator?->name }}</p>
        @if(auth()->user()->canAccess('field_manage'))
            <a href="{{ route('admin.field.visits.edit', $visit) }}" class="inline-block px-3 py-2 rounded-xl border text-sm">Editar</a>
        @endif
        <div class="bg-white dark:bg-slate-900 rounded-2xl border p-6 space-y-4 text-sm">
            @if($visit->notes)
                <div>
                    <h2 class="font-semibold text-gray-900 dark:text-white mb-1">Notas</h2>
                    <div class="whitespace-pre-wrap text-gray-700 dark:text-gray-300">{{ $visit->notes }}</div>
                </div>
            @endif
            @if($visit->next_steps)
                <div>
                    <h2 class="font-semibold text-gray-900 dark:text-white mb-1">Próximos passos</h2>
                    <div class="whitespace-pre-wrap text-gray-700 dark:text-gray-300">{{ $visit->next_steps }}</div>
                </div>
            @endif
            @if($visit->attendees->isNotEmpty())
                <div>
                    <h2 class="font-semibold mb-1">Participantes</h2>
                    <ul class="list-disc list-inside text-gray-600">
                        @foreach($visit->attendees as $u)
                            <li>{{ $u->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        @if(auth()->user()->canAccess('field_manage'))
            <form method="post" action="{{ route('admin.field.visits.destroy', $visit) }}" onsubmit="return confirm('Remover?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:underline">Eliminar</button>
            </form>
        @endif
    </div>
@endsection
