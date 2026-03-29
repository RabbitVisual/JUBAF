@extends('memberpanel::components.layouts.master')

@section('page-title', 'Nova visita')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Nova visita',
        'subtitle' => 'Registo de campo JUBAF.',
        'badge' => 'Campo',
    ])
        <a href="{{ route('memberpanel.field.visits.index') }}"
            class="inline-flex items-center text-sm font-semibold text-teal-600 dark:text-teal-400 hover:underline mb-4">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Visitas
        </a>

        <form method="post" action="{{ route('memberpanel.field.visits.store') }}"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 space-y-4 shadow-sm max-w-2xl">
            @csrf
            @include('fieldoutreach::admin.visits._form', ['visit' => null, 'churches' => $churches, 'users' => $users])
            <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold">Guardar</button>
            <a href="{{ route('memberpanel.field.visits.index') }}"
                class="ml-3 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:underline">Cancelar</a>
        </form>
    @endcomponent
@endsection
