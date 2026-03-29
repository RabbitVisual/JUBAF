<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Funil da caravana</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Membros da sua igreja local e inscrições no evento selecionado.
            </p>
        </div>
        <div class="w-full sm:w-80">
            <label for="event_id" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Evento</label>
            <select id="event_id" wire:model.live="selectedEventId"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                <option value="">— Selecionar —</option>
                @foreach ($events as $ev)
                    <option value="{{ $ev->id }}">{{ $ev->title }} — {{ $ev->start_date?->format('d/m/Y') }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if ($events->isEmpty())
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-900/40 dark:bg-amber-900/20 dark:text-amber-100">
            Não há eventos publicados nos próximos 90 dias para mostrar aqui.
        </div>
    @elseif (! $resolvedEventId)
        <div class="rounded-xl border border-gray-200 bg-white p-4 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
            Escolha um evento para ver o funil e a lista de membros.
        </div>
    @else
        @php
            $reg = max(1, $stats['registered']);
            $pctEnrolled = min(100, round(($stats['enrolled'] / $reg) * 100));
            $pctConfirmed = min(100, round(($stats['confirmed'] / $reg) * 100));
            $pctPaid = min(100, round(($stats['paid_ready'] / $reg) * 100));
        @endphp

        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Na caravana</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $stats['registered'] }}</p>
                <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">Membros ativos com esta igreja</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Inscritos</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-blue-600 dark:text-blue-400">{{ $stats['enrolled'] }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Confirmados</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-indigo-600 dark:text-indigo-400">{{ $stats['confirmed'] }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Prontos (pagamento)</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-emerald-600 dark:text-emerald-400">{{ $stats['paid_ready'] }}</p>
                <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">Confirmados e sem pendência de pagamento online</p>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <p class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Progresso (face aos membros da igreja)</p>
            <div class="space-y-3">
                <div>
                    <div class="mb-1 flex justify-between text-xs text-gray-600 dark:text-gray-400">
                        <span>Inscrição</span>
                        <span>{{ $pctEnrolled }}%</span>
                    </div>
                    <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                        <div class="h-2.5 rounded-full bg-blue-500 transition-all" style="width: {{ $pctEnrolled }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="mb-1 flex justify-between text-xs text-gray-600 dark:text-gray-400">
                        <span>Confirmados</span>
                        <span>{{ $pctConfirmed }}%</span>
                    </div>
                    <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                        <div class="h-2.5 rounded-full bg-indigo-500 transition-all" style="width: {{ $pctConfirmed }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="mb-1 flex justify-between text-xs text-gray-600 dark:text-gray-400">
                        <span>Pagamento ok / N/A</span>
                        <span>{{ $pctPaid }}%</span>
                    </div>
                    <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                        <div class="h-2.5 rounded-full bg-emerald-500 transition-all" style="width: {{ $pctPaid }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Membros</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Nome</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Inscrição</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Pagamento</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Check-in</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($members as $member)
                            @php
                                $regRow = $funnel->registrationForEvent($member, $resolvedEventId);
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $member->name }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $regRow ? $regRow->status_display : '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $funnel->paymentStatusLabel($regRow) }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-700 dark:text-gray-300">
                                    @if ($regRow?->checked_in_at)
                                        {{ $regRow->checked_in_at->format('d/m/Y H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Sem membros ativos nesta igreja.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($members->hasPages())
                <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-700">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
