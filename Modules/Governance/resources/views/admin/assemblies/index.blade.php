@extends('admin::components.layouts.master')

@section('title', 'Governança — Assembleias')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Assembleias</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Convocações, pautas e atas.</p>
            </div>
            @if(auth()->user()->canAccess('governance_manage'))
                <a href="{{ route('admin.governance.assemblies.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700">Nova assembleia</a>
            @endif
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800 text-sm">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Data</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Título</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Tipo</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Ata</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($assemblies as $a)
                        <tr>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $a->scheduled_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $a->title }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $a->type === 'extraordinaria' ? 'Extraordinária' : 'Ordinária' }}</td>
                            <td class="px-4 py-3">
                                @if($a->minute)
                                    <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-medium
                                        @class([
                                            'bg-gray-100 text-gray-700 dark:bg-slate-800 dark:text-gray-300' => $a->minute->status === 'draft',
                                            'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200' => $a->minute->status === 'approved',
                                            'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' => $a->minute->status === 'published',
                                        ])">{{ $a->minute->status }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.governance.assemblies.show', $a) }}" class="text-blue-600 dark:text-blue-400 font-medium hover:underline">Abrir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Nenhum registo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $assemblies->links() }}</div>
    </div>
@endsection
