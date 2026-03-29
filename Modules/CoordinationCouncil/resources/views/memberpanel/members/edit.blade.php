@extends('memberpanel::components.layouts.master')

@section('page-title', 'Editar membro')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Editar membro',
        'subtitle' => $member->full_name,
        'badge' => 'Conselho',
    ])
        <a href="{{ route('memberpanel.council.members.index') }}"
            class="inline-flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline mb-4">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Membros
        </a>

        <form method="post" action="{{ route('memberpanel.council.members.update', $member) }}"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 space-y-4 shadow-sm max-w-xl">
            @csrf
            @method('PUT')
            @include('coordinationcouncil::admin.members._form', ['member' => $member])
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold">Guardar</button>
                <a href="{{ route('memberpanel.council.members.show', $member) }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-sm font-semibold">Cancelar</a>
            </div>
        </form>
    @endcomponent
@endsection
