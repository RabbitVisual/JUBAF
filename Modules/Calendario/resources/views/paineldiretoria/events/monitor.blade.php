@extends($layout)

@section('title', 'Monitor do evento')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase text-slate-500">Arrecadação total</p>
            <p class="mt-2 text-2xl font-bold text-emerald-600">R$ {{ number_format((float) $totalRevenue, 2, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase text-slate-500">Inscrições confirmadas</p>
            <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-50">{{ $confirmed }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase text-slate-500">Ocupação</p>
            <p class="mt-2 text-2xl font-bold text-violet-700">{{ $occupancyPercent === null ? 'Sem limite' : $occupancyPercent.'%' }}</p>
            @if($capacity)
                <div class="mt-3 h-2.5 rounded-full bg-slate-200 dark:bg-slate-700">
                    <div class="h-2.5 rounded-full bg-violet-600" style="width: {{ min((float) $occupancyPercent, 100) }}%"></div>
                </div>
            @endif
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
            <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Credenciamento (check-in)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-800/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nome</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Check-in</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($registrations as $registration)
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-200">{{ $registration->user?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $registration->status }}</td>
                            <td class="px-4 py-3 text-sm">{{ $registration->checked_in_at ? $registration->checked_in_at->format('d/m/Y H:i') : 'Pendente' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
