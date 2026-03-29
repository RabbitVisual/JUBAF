@extends('memberpanel::components.layouts.master')

@section('page-title', 'Nova reunião')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Nova reunião',
        'subtitle' => 'Conselho de coordenação.',
        'badge' => 'Conselho',
    ])
        <a href="{{ route('memberpanel.council.meetings.index') }}"
            class="inline-flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline mb-4">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Reuniões
        </a>

        <form method="post" action="{{ route('memberpanel.council.meetings.store') }}"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 space-y-4 shadow-sm max-w-xl">
            @csrf
            @include('coordinationcouncil::admin.meetings._form', ['meeting' => null, 'members' => $members])
            <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold">Criar</button>
            <a href="{{ route('memberpanel.council.meetings.index') }}"
                class="ml-3 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:underline">Cancelar</a>
        </form>
    @endcomponent
@endsection
