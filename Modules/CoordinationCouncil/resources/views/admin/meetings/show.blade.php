@extends('admin::components.layouts.master')

@section('title', 'Reunião '.$meeting->scheduled_at?->format('d/m/Y'))

@section('content')
    <div class="space-y-6 max-w-4xl">
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif
        <div class="flex flex-wrap justify-between gap-4">
            <div>
                <a href="{{ route('admin.council.meetings.index') }}" class="text-sm text-blue-600 hover:underline">← Reuniões</a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $meeting->scheduled_at?->format('d/m/Y H:i') }}</h1>
                <p class="text-gray-600 text-sm">{{ $meeting->meeting_type === 'extraordinary' ? 'Extraordinária' : 'Ordinária' }} · Quórum: {{ $meeting->quorum_actual ?? '—' }} / {{ $meeting->quorum_required }}</p>
                @if($meeting->location)
                    <p class="text-sm text-gray-500">{{ $meeting->location }}</p>
                @endif
            </div>
            @if(auth()->user()->canAccess('council_manage'))
                <a href="{{ route('admin.council.meetings.edit', $meeting) }}" class="self-start px-3 py-2 rounded-xl border text-sm">Editar dados</a>
            @endif
        </div>

        @if($meeting->minutes_notes)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border p-6 text-sm whitespace-pre-wrap">{{ $meeting->minutes_notes }}</div>
        @endif

        @if(auth()->user()->canAccess('council_manage'))
            <form method="post" action="{{ route('admin.council.meetings.attendance', $meeting) }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6 space-y-4">
                @csrf
                <h2 class="font-semibold text-gray-900 dark:text-white">Presenças</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b dark:border-slate-700">
                                <th class="py-2 pr-4">Membro</th>
                                <th class="py-2 pr-4">Estado</th>
                                <th class="py-2">Justificação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                            @foreach($members as $mem)
                                @php
                                    $att = $meeting->attendances->firstWhere('council_member_id', $mem->id);
                                    $st = old('attendance.'.$mem->id.'.status', $att?->status ?? 'present');
                                @endphp
                                <tr>
                                    <td class="py-2 pr-4 font-medium">{{ $mem->full_name }}</td>
                                    <td class="py-2 pr-4">
                                        <select name="attendance[{{ $mem->id }}][status]" class="rounded-lg border-gray-300 dark:border-slate-700 dark:bg-slate-800 text-xs">
                                            <option value="present" @selected($st === 'present')>Presente</option>
                                            <option value="absent" @selected($st === 'absent')>Falta</option>
                                            <option value="excused" @selected($st === 'excused')>Justificado</option>
                                        </select>
                                    </td>
                                    <td class="py-2">
                                        <input type="text" name="attendance[{{ $mem->id }}][justification]" value="{{ old('attendance.'.$mem->id.'.justification', $att?->justification) }}" class="w-full rounded-lg border-gray-300 dark:border-slate-700 dark:bg-slate-800 text-xs" placeholder="Opcional">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium">Guardar presenças</button>
            </form>
        @else
            <div class="bg-white dark:bg-slate-900 rounded-2xl border p-6">
                <h2 class="font-semibold mb-3">Presenças (leitura)</h2>
                <ul class="text-sm space-y-1">
                    @foreach($meeting->attendances as $a)
                        <li>{{ $a->member?->full_name }}: {{ $a->status }} @if($a->justification) — {{ $a->justification }} @endif</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(auth()->user()->canAccess('council_manage'))
            <form method="post" action="{{ route('admin.council.meetings.destroy', $meeting) }}" onsubmit="return confirm('Remover?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:underline">Eliminar reunião</button>
            </form>
        @endif
    </div>
@endsection
