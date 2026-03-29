@extends('memberpanel::components.layouts.master')

@section('page-title', 'Membros do conselho')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Membros do conselho',
        'subtitle' => 'Efetivos e suplentes.',
        'badge' => 'Conselho',
    ])
        @slot('actions')
            @if (auth()->user()->canAccess('council_manage'))
                <a href="{{ route('memberpanel.council.members.create') }}"
                    class="inline-flex items-center px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-lg shadow-indigo-500/20">
                    <x-icon name="plus" style="duotone" class="w-4 h-4 mr-2" />
                    Novo membro
                </a>
            @endif
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.council.members.index') }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">
                    Admin técnico
                </a>
            @endif
        @endslot

        <a href="{{ route('memberpanel.council.dashboard') }}"
            class="inline-flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline mb-6">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Visão geral
        </a>

        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm">
            <ul class="divide-y divide-gray-100 dark:divide-slate-800">
                @forelse($members as $m)
                    <li>
                        <a href="{{ route('memberpanel.council.members.show', $m) }}"
                            class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $m->full_name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $m->kind === 'supplement' ? 'Suplente' : 'Efetivo' }}
                                    @if (!$m->is_active)
                                        · inativo
                                    @endif
                                </p>
                            </div>
                            <x-icon name="chevron-right" class="w-4 h-4 text-gray-400 shrink-0" />
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-16 text-center text-gray-500 text-sm">Sem membros registados.</li>
                @endforelse
            </ul>
        </div>
        <div class="mt-6">{{ $members->links() }}</div>
    @endcomponent
@endsection
