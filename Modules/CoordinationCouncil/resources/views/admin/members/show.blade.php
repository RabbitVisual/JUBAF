@extends('admin::components.layouts.master')

@section('title', $member->full_name)

@section('content')
    <div class="max-w-xl space-y-4">
        <a href="{{ route('admin.council.members.index') }}" class="text-sm text-blue-600 hover:underline">← Lista</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $member->full_name }}</h1>
        <dl class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6 space-y-2 text-sm">
            <div><dt class="text-gray-500 inline">Email:</dt> <dd class="inline text-gray-900 dark:text-white">{{ $member->email ?: '—' }}</dd></div>
            <div><dt class="text-gray-500 inline">Telefone:</dt> <dd class="inline">{{ $member->phone ?: '—' }}</dd></div>
            <div><dt class="text-gray-500 inline">Tipo:</dt> <dd class="inline">{{ $member->kind === 'supplement' ? 'Suplente' : 'Efetivo' }}</dd></div>
            <div><dt class="text-gray-500 inline">Mandato:</dt> <dd class="inline">{{ $member->term_started_at?->format('d/m/Y') }} — {{ $member->term_ended_at?->format('d/m/Y') ?: '…' }}</dd></div>
            <div><dt class="text-gray-500 inline">Terço:</dt> <dd class="inline">{{ $member->mandate_third ?? '—' }}</dd></div>
            <div><dt class="text-gray-500 inline">Presenças registadas:</dt> <dd class="inline">{{ $member->attendances_count }}</dd></div>
        </dl>
        @if(auth()->user()->canAccess('council_manage'))
            <a href="{{ route('admin.council.members.edit', $member) }}" class="inline-block px-4 py-2 rounded-xl border text-sm">Editar</a>
        @endif
    </div>
@endsection
