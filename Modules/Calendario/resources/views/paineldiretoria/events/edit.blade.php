@extends($layout)

@section('title', 'Editar evento')

@section('content')
@php
    $regStatus = [
        'confirmed' => ['Confirmada', 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100'],
        'waitlist' => ['Lista de espera', 'bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-100'],
        'pending_payment' => ['Pagamento pendente', 'bg-sky-100 text-sky-900 dark:bg-sky-900/40 dark:text-sky-100'],
        'cancelled' => ['Cancelada', 'bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-gray-200'],
    ];
@endphp
<div class="mx-auto max-w-4xl space-y-8 pb-10">
    @include('calendario::paineldiretoria.partials.subnav', ['active' => 'events'])

    <div>
        <a href="{{ route('diretoria.calendario.events.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:gap-2 dark:text-emerald-400">
            <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
            Voltar aos eventos
        </a>
        <h1 class="mt-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Editar evento</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $event->title }}</p>
        <p class="mt-1 max-w-2xl text-xs text-gray-500 dark:text-gray-400">Mesmo assistente em 4 passos que na criação — altere o que precisar e use <strong>Guardar alterações</strong> no fim do cartão.</p>
        <div class="mt-3 flex flex-wrap gap-2">
            @if(Route::has('eventos.show'))
                <a href="{{ route('eventos.show', $event->slug) }}{{ $event->status !== \Modules\Calendario\App\Models\CalendarEvent::STATUS_PUBLISHED ? '?preview='.$event->preview_token : '' }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-800 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-200">
                    <x-icon name="eye" class="h-3.5 w-3.5" style="duotone" />
                    Pré-visualizar página pública
                </a>
            @endif
            @can('manageRegistrations', $event)
                <a href="{{ route('diretoria.calendario.events.registrations.export', $event) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-800 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-white">CSV inscrições</a>
                <a href="{{ route('diretoria.calendario.events.registrations.badges', $event) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-800 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-white">PDF credenciais</a>
            @endcan
        </div>
    </div>

    <form action="{{ route('diretoria.calendario.events.update', $event) }}" method="post" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="border-b border-gray-100 px-6 py-4 dark:border-slate-700">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Dados do evento</h2>
                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Início agendado: {{ $event->starts_at?->format('d/m/Y H:i') ?? '—' }} · navegue pelos passos com <em>Anterior</em> / <em>Seguinte</em></p>
            </div>
            <div class="p-6">
                @include('calendario::paineldiretoria.events._form', ['event' => $event, 'churches' => $churches, 'discountRule' => $discountRule ?? null])
            </div>
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 bg-gray-50/80 px-6 py-4 dark:border-slate-700 dark:bg-slate-900/40">
                <a href="{{ route('diretoria.calendario.events.index') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200/80 dark:text-gray-300 dark:hover:bg-slate-700">Cancelar</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700">
                    <x-icon name="check" class="h-4 w-4" style="solid" />
                    Guardar alterações
                </button>
            </div>
        </div>
    </form>

    @can('manageRegistrations', $event)
        <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex flex-col gap-1 border-b border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">
                <div>
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white">Inscrições e check-in</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Últimas 50 inscrições neste evento</p>
                </div>
                @can('calendario.registrations.view')
                    <a href="{{ route('diretoria.calendario.registrations.index', ['event_id' => $event->id]) }}" class="text-xs font-bold text-sky-700 hover:underline dark:text-sky-400">Ver na lista global</a>
                @endcan
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/90 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/90 dark:text-gray-400">
                            <th class="px-5 py-3.5">Nome</th>
                            <th class="px-5 py-3.5">Estado</th>
                            <th class="px-5 py-3.5">Check-in</th>
                            <th class="px-5 py-3.5 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($registrations as $r)
                            @php
                                $rs = $regStatus[$r->status] ?? [$r->status, 'bg-gray-100 text-gray-800 dark:bg-slate-700'];
                            @endphp
                            <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-900/50">
                                <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $r->user?->name ?? '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold {{ $rs[1] }}">{{ $rs[0] }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ $r->checked_in_at?->format('d/m H:i') ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    @if($r->checked_in_at)
                                        <form action="{{ route('diretoria.calendario.events.registrations.check-in', [$event, $r]) }}" method="post" class="inline">
                                            @csrf
                                            <input type="hidden" name="undo" value="1">
                                            <button type="submit" class="text-xs font-bold text-amber-700 hover:underline dark:text-amber-400">Desfazer</button>
                                        </form>
                                    @else
                                        <form action="{{ route('diretoria.calendario.events.registrations.check-in', [$event, $r]) }}" method="post" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Check-in</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-500 dark:text-gray-400">Ainda sem inscrições neste evento.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endcan
</div>
@endsection
