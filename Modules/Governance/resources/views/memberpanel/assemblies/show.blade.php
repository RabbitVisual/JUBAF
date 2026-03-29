@extends('memberpanel::components.layouts.master')

@section('page-title', $assembly->title)

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => $assembly->title,
        'subtitle' => $assembly->scheduled_at?->translatedFormat('d \\d\\e F \\d\\e Y, H:i') . ($assembly->location ? ' · ' . $assembly->location : ''),
        'badge' => 'Governança',
    ])
        @slot('actions')
            @if (!empty($canManage) && $canManage)
                <a href="{{ route('memberpanel.governance.assemblies.edit', $assembly) }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-sm font-bold text-gray-800 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800">
                    Editar dados
                </a>
                <a href="{{ route('memberpanel.governance.assemblies.minute.edit', $assembly) }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-lg shadow-violet-500/20">
                    <x-icon name="pen-to-square" style="duotone" class="w-4 h-4 mr-2" />
                    Ata
                </a>
            @endif
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.governance.assemblies.show', $assembly) }}"
                    class="text-xs font-bold text-gray-500 hover:text-violet-600 px-2">Admin</a>
            @endif
        @endslot

        @if (session('success'))
            <div
                class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm border border-emerald-100 dark:border-emerald-900/40 mb-6">
                {{ session('success') }}</div>
        @endif

        <a href="{{ route('memberpanel.governance.assemblies.index') }}"
            class="inline-flex items-center text-sm font-semibold text-violet-600 dark:text-violet-400 hover:underline mb-6">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Assembleias
        </a>

        @if ($assembly->convocation_notes)
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 mb-6 shadow-sm">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Convocação</h2>
                <div class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $assembly->convocation_notes }}</div>
            </div>
        @endif

        @if ($assembly->agendaItems->isNotEmpty())
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 mb-6 shadow-sm">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Pauta</h2>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    @foreach ($assembly->agendaItems as $item)
                        <li>
                            <span class="font-medium">{{ $item->title }}</span>
                            @if ($item->description)
                                <p class="ml-6 text-gray-500 text-xs mt-0.5">{{ $item->description }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        @endif

        @if ($assembly->minute)
            @php
                $m = $assembly->minute;
                $showBody = $canSeeDraftMinute || in_array($m->status, ['approved', 'published'], true);
            @endphp
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500">Ata</h2>
                    @if (!empty($canManage) && $canManage)
                        <a href="{{ route('memberpanel.governance.assemblies.minute.edit', $assembly) }}"
                            class="text-sm font-bold text-violet-600 dark:text-violet-400 hover:underline">Editar ata</a>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mb-3">Estado: <strong>{{ $m->status }}</strong>
                    @if ($m->published_at)
                        · Publicada em {{ $m->published_at->format('d/m/Y') }}
                    @endif
                </p>
                @if ($showBody)
                    <div
                        class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap border-t border-gray-100 dark:border-slate-800 pt-4">
                        {{ $m->body }}</div>
                    @if ($m->isPublished())
                        <a href="{{ route('public.transparency.minute', $m) }}" target="_blank"
                            class="inline-flex items-center mt-4 text-sm font-bold text-violet-600 dark:text-violet-400 hover:underline">
                            <x-icon name="arrow-up-right" class="w-4 h-4 mr-1" /> Ver no site
                        </a>
                    @endif
                @else
                    <p class="text-sm text-gray-500">O texto da ata ainda não está disponível para o seu nível de acesso.</p>
                @endif
            </div>
        @elseif (!empty($canManage) && $canManage)
            <div
                class="rounded-3xl border border-dashed border-violet-200 dark:border-violet-800 p-6 text-center text-sm text-gray-600 dark:text-slate-400">
                <p class="mb-3">Ainda não existe ata para esta assembleia.</p>
                <a href="{{ route('memberpanel.governance.assemblies.minute.edit', $assembly) }}"
                    class="inline-flex items-center px-4 py-2 rounded-xl bg-violet-600 text-white text-sm font-bold">Criar ata</a>
            </div>
        @endif

        @if (!empty($canManage) && $canManage)
            <form method="post" action="{{ route('memberpanel.governance.assemblies.destroy', $assembly) }}" class="mt-8"
                onsubmit="return confirm('Eliminar esta assembleia?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:underline">Eliminar assembleia</button>
            </form>
        @endif
    @endcomponent
@endsection
