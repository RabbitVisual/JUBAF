@extends('memberpanel::components.layouts.master')

@section('page-title', $member->full_name)

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => $member->full_name,
        'subtitle' => $member->kind === 'supplement' ? 'Suplente' : 'Efetivo',
        'badge' => 'Conselho',
    ])
        @slot('actions')
            @if (!empty($canManage) && $canManage)
                <a href="{{ route('memberpanel.council.members.edit', $member) }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold">
                    Editar
                </a>
            @endif
        @endslot

        <a href="{{ route('memberpanel.council.members.index') }}"
            class="inline-flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline mb-6">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Membros
        </a>

        <div
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 space-y-3 text-sm shadow-sm">
            <p><span class="text-gray-500 font-medium">Email:</span> {{ $member->email ?: '—' }}</p>
            <p><span class="text-gray-500 font-medium">Telefone:</span> {{ $member->phone ?: '—' }}</p>
            <p><span class="text-gray-500 font-medium">Mandato:</span>
                {{ $member->term_started_at?->format('d/m/Y') ?? '—' }} — {{ $member->term_ended_at?->format('d/m/Y') ?? '—' }}</p>
            @if ($member->mandate_third)
                <p><span class="text-gray-500 font-medium">Terço:</span> {{ $member->mandate_third }}</p>
            @endif
            <p><span class="text-gray-500 font-medium">Presenças registadas:</span> {{ $member->attendances_count }}</p>
        </div>

        @if (!empty($canManage) && $canManage)
            <form method="post" action="{{ route('memberpanel.council.members.destroy', $member) }}" class="mt-8"
                onsubmit="return confirm('Remover este membro?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:underline">Eliminar membro</button>
            </form>
        @endif
    @endcomponent
@endsection
