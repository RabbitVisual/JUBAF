@extends('admin::components.layouts.master')

@section('title', 'Conselho — Reuniões')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reuniões do conselho</h1>
            </div>
            @if(auth()->user()->canAccess('council_manage'))
                <a href="{{ route('admin.council.meetings.create') }}" class="inline-flex px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium">Nova reunião</a>
            @endif
        </div>
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 overflow-hidden">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-slate-800">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Data</th>
                        <th class="px-4 py-3 text-left font-semibold">Tipo</th>
                        <th class="px-4 py-3 text-left font-semibold">Quórum</th>
                        <th class="px-4 py-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($meetings as $mtg)
                        <tr>
                            <td class="px-4 py-3">{{ $mtg->scheduled_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">{{ $mtg->meeting_type === 'extraordinary' ? 'Extraordinária' : 'Ordinária' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $mtg->quorum_actual ?? '—' }} / {{ $mtg->quorum_required }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.council.meetings.show', $mtg) }}" class="text-blue-600 hover:underline">Abrir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Sem reuniões.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $meetings->links() }}</div>
    </div>
@endsection
