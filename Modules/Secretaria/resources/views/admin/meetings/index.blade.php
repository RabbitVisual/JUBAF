@extends('layouts.app')

@section('title', 'Reuniões')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reuniões</h1>
        @can('create', \Modules\Secretaria\App\Models\Meeting::class)
            <a href="{{ route($routePrefix.'.create') }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg">Nova</a>
        @endcan
    </div>
    @if(session('success'))<p class="text-sm text-emerald-600">{{ session('success') }}</p>@endif
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-slate-900 text-left text-xs font-semibold uppercase text-gray-500"><tr><th class="px-4 py-3">Tipo / Título</th><th class="px-4 py-3">Início</th><th class="px-4 py-3">Estado</th><th class="px-4 py-3"></th></tr></thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($meetings as $m)
                    <tr>
                        <td class="px-4 py-3">{{ $m->title ?: str_replace('_', ' ', $m->type) }}</td>
                        <td class="px-4 py-3">{{ $m->starts_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $m->status }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route($routePrefix.'.show', $m) }}" class="text-indigo-600">Ver</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $meetings->links() }}
</div>
@endsection
