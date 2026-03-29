@extends('admin::components.layouts.master')

@section('title', 'Campo — Visitas')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Visitas de campo</h1>
            </div>
            @if(auth()->user()->canAccess('field_manage'))
                <a href="{{ route('admin.field.visits.create') }}" class="inline-flex px-4 py-2 rounded-xl bg-teal-600 text-white text-sm font-medium">Registar visita</a>
            @endif
        </div>

        <form method="get" class="flex flex-wrap gap-3 items-end bg-white dark:bg-slate-900 rounded-xl border p-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Igreja</label>
                <select name="church_id" class="rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 text-sm" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    @foreach($churches as $c)
                        <option value="{{ $c->id }}" @selected(request('church_id') == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-2xl border overflow-hidden">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-slate-800">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Data</th>
                        <th class="px-4 py-3 text-left font-semibold">Igreja</th>
                        <th class="px-4 py-3 text-left font-semibold">Registado por</th>
                        <th class="px-4 py-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($visits as $v)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $v->visited_at?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $v->church?->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $v->creator?->name }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.field.visits.show', $v) }}" class="text-blue-600 hover:underline">Ver</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Sem visitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $visits->links() }}</div>
    </div>
@endsection
