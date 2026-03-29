@extends('memberpanel::components.layouts.master')

@section('page-title', 'Reunião '.$meeting->scheduled_at?->format('d/m/Y'))

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => $meeting->scheduled_at?->translatedFormat('d \\d\\e F \\d\\e Y, H:i'),
        'subtitle' => ($meeting->meeting_type === 'extraordinary' ? 'Extraordinária' : 'Ordinária').' · Quórum: '.($meeting->quorum_actual ?? '—').' / '.$meeting->quorum_required,
        'badge' => 'Conselho',
    ])
        @slot('actions')
            @if (!empty($canManage) && $canManage)
                <a href="{{ route('memberpanel.council.meetings.edit', $meeting) }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-800 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800">
                    Editar dados
                </a>
            @endif
        @endslot

        @if (session('success'))
            <div
                class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm border border-emerald-100 dark:border-emerald-900/40 mb-6">
                {{ session('success') }}</div>
        @endif

        <a href="{{ route('memberpanel.council.meetings.index') }}"
            class="inline-flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline mb-6">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Reuniões
        </a>

        @if ($meeting->location)
            <p class="text-sm text-gray-500 mb-4">{{ $meeting->location }}</p>
        @endif

        @if ($meeting->minutes_notes)
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 mb-6 text-sm whitespace-pre-wrap shadow-sm">
                {{ $meeting->minutes_notes }}
            </div>
        @endif

        @if (!empty($canManage) && $canManage)
            <form method="post" action="{{ route('memberpanel.council.meetings.attendance', $meeting) }}"
                class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 space-y-4 shadow-sm mb-6">
                @csrf
                <h2 class="font-bold text-gray-900 dark:text-white text-sm uppercase tracking-wider">Presenças</h2>
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
                            @foreach ($members as $mem)
                                @php
                                    $att = $meeting->attendances->firstWhere('council_member_id', $mem->id);
                                    $st = old('attendance.'.$mem->id.'.status', $att?->status ?? 'present');
                                @endphp
                                <tr>
                                    <td class="py-2 pr-4 font-medium text-gray-900 dark:text-white">{{ $mem->full_name }}</td>
                                    <td class="py-2 pr-4">
                                        <select name="attendance[{{ $mem->id }}][status]"
                                            class="rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-xs">
                                            <option value="present" @selected($st === 'present')>Presente</option>
                                            <option value="absent" @selected($st === 'absent')>Falta</option>
                                            <option value="excused" @selected($st === 'excused')>Justificado</option>
                                        </select>
                                    </td>
                                    <td class="py-2">
                                        <input type="text" name="attendance[{{ $mem->id }}][justification]"
                                            value="{{ old('attendance.'.$mem->id.'.justification', $att?->justification) }}"
                                            class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-xs"
                                            placeholder="Opcional">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold">Guardar
                    presenças</button>
            </form>
        @elseif ($meeting->attendances->isNotEmpty())
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
                <h2 class="font-bold text-gray-900 dark:text-white mb-3 text-sm uppercase tracking-wider">Presenças</h2>
                <ul class="text-sm space-y-2">
                    @foreach ($meeting->attendances as $a)
                        <li class="flex justify-between gap-4 border-b border-gray-100 dark:border-slate-800 pb-2 last:border-0">
                            <span class="text-gray-900 dark:text-white">{{ $a->member?->full_name ?? '—' }}</span>
                            <span class="text-gray-500 shrink-0">
                                @if ($a->status === 'present')
                                    Presente
                                @elseif($a->status === 'absent')
                                    Falta
                                @else
                                    Justificado
                                @endif
                                @if ($a->justification)
                                    — {{ $a->justification }}
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (!empty($canManage) && $canManage)
            <form method="post" action="{{ route('memberpanel.council.meetings.destroy', $meeting) }}" class="mt-8"
                onsubmit="return confirm('Remover esta reunião?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:underline">Eliminar reunião</button>
            </form>
        @endif
    @endcomponent
@endsection
