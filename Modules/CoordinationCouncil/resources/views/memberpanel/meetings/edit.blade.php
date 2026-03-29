@extends('memberpanel::components.layouts.master')

@section('page-title', 'Editar reunião')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Editar reunião',
        'subtitle' => $meeting->scheduled_at?->format('d/m/Y H:i'),
        'badge' => 'Conselho',
    ])
        <a href="{{ route('memberpanel.council.meetings.show', $meeting) }}"
            class="inline-flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline mb-4">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Voltar
        </a>

        <form method="post" action="{{ route('memberpanel.council.meetings.update', $meeting) }}"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 space-y-4 shadow-sm max-w-xl">
            @csrf
            @method('PUT')
            @include('coordinationcouncil::admin.meetings._form', ['meeting' => $meeting, 'members' => collect()])
            <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold">Guardar</button>
            <a href="{{ route('memberpanel.council.meetings.show', $meeting) }}"
                class="ml-3 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:underline">Cancelar</a>
        </form>
    @endcomponent
@endsection
