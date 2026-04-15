@extends($layout)

@section('title', 'Secretaria JUBAF')

@section('content')
@php $sb = $secretariaBase; @endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'dashboard'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Painel diretoria</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <x-module-icon module="Secretaria" class="h-10 w-10 shrink-0" />
                Secretaria
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Atas, reuniões, convocatórias e arquivo institucional — integrado com calendário, avisos e notificações (conforme configuração).</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50/90 p-4 text-sm text-emerald-900 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-100">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl border border-red-200/80 bg-red-50/90 p-4 text-sm text-red-900 dark:border-red-900/40 dark:bg-red-950/40 dark:text-red-100">{{ session('error') }}</div>
    @endif

    @if(user_is_diretoria_executive() && ($pendingMinutes > 0 || $pendingConvocations > 0))
        <div class="rounded-2xl border border-amber-200/90 bg-amber-50/90 p-5 shadow-sm dark:border-amber-900/40 dark:bg-amber-950/30">
            <p class="font-bold text-amber-900 dark:text-amber-200">Fila executiva</p>
            <p class="mt-1 text-sm text-amber-900/90 dark:text-amber-100/90">Presidente e vices: aprove ou publique itens pendentes.</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                @if($pendingMinutes > 0)
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-amber-800 dark:text-amber-300">Atas ({{ $pendingMinutes }})</p>
                        <ul class="mt-2 space-y-1 text-sm text-amber-950 dark:text-amber-50">
                            @foreach($pendingMinutesList as $pm)
                                <li><a href="{{ route($sb.'.atas.show', $pm) }}" class="font-medium underline decoration-amber-600/40 underline-offset-2 hover:text-amber-900 dark:hover:text-amber-100">{{ \Illuminate\Support\Str::limit($pm->title, 48) }}</a></li>
                            @endforeach
                        </ul>
                        <a href="{{ route($sb.'.atas.index', ['status' => 'pending_signatures']) }}" class="mt-2 inline-flex text-xs font-bold text-amber-800 hover:underline dark:text-amber-200">Ver todas as atas</a>
                    </div>
                @endif
                @if($pendingConvocations > 0)
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-amber-800 dark:text-amber-300">Convocatórias ({{ $pendingConvocations }})</p>
                        <ul class="mt-2 space-y-1 text-sm text-amber-950 dark:text-amber-50">
                            @foreach($pendingConvocationsList as $pc)
                                <li><a href="{{ route($sb.'.convocatorias.show', $pc) }}" class="font-medium underline decoration-amber-600/40 underline-offset-2 hover:text-amber-900 dark:hover:text-amber-100">{{ \Illuminate\Support\Str::limit($pc->title, 48) }}</a></li>
                            @endforeach
                        </ul>
                        <a href="{{ route($sb.'.convocatorias.index', ['status' => 'pending_approval']) }}" class="mt-2 inline-flex text-xs font-bold text-amber-800 hover:underline dark:text-amber-200">Ver todas</a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($isSecretary && ! user_is_diretoria_executive())
        <div class="rounded-2xl border border-cyan-200/80 bg-cyan-50/80 p-4 text-sm text-cyan-950 dark:border-cyan-900/40 dark:bg-cyan-950/25 dark:text-cyan-100">
            <p class="font-bold">Área dos secretários</p>
            <p class="mt-1 text-cyan-900/90 dark:text-cyan-100/85">Redija atas e convocatórias; envie para aprovação do executivo quando estiver pronto.</p>
            <div class="mt-3 flex flex-wrap gap-2">
                <a href="{{ route($sb.'.atas.create') }}" class="inline-flex rounded-lg bg-cyan-700 px-3 py-1.5 text-xs font-bold text-white hover:bg-cyan-800">Nova ata</a>
                <a href="{{ route($sb.'.convocatorias.create') }}" class="inline-flex rounded-lg border border-cyan-600 bg-white px-3 py-1.5 text-xs font-bold text-cyan-900 hover:bg-cyan-100 dark:bg-slate-900 dark:text-cyan-100 dark:hover:bg-slate-800">Nova convocatória</a>
                <a href="{{ route($sb.'.reunioes.create') }}" class="inline-flex rounded-lg border border-cyan-500/50 px-3 py-1.5 text-xs font-bold text-cyan-900 hover:bg-cyan-100/80 dark:text-cyan-200">Registar reunião</a>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <a href="{{ route($sb.'.reunioes.index') }}" class="group flex flex-col rounded-2xl border border-gray-200/90 bg-white p-4 text-left shadow-sm transition hover:border-emerald-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-emerald-600">
            <x-module-icon module="Calendario" class="mb-2 h-8 w-8 opacity-90" />
            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Reuniões</span>
            <span class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Agenda secretaria</span>
        </a>
        <a href="{{ route($sb.'.atas.index') }}" class="group flex flex-col rounded-2xl border border-gray-200/90 bg-white p-4 text-left shadow-sm transition hover:border-emerald-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-emerald-600">
            <x-module-icon module="Secretaria" class="mb-2 h-8 w-8 opacity-90" />
            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Atas</span>
            <span class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Modelos e anexos</span>
        </a>
        <a href="{{ route($sb.'.convocatorias.index') }}" class="group flex flex-col rounded-2xl border border-gray-200/90 bg-white p-4 text-left shadow-sm transition hover:border-emerald-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-emerald-600">
            <x-module-icon module="Avisos" class="mb-2 h-8 w-8 opacity-90" />
            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Convocatórias</span>
            <span class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Assembleias</span>
        </a>
        <a href="{{ route($sb.'.arquivo.index') }}" class="group flex flex-col rounded-2xl border border-gray-200/90 bg-white p-4 text-left shadow-sm transition hover:border-emerald-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-emerald-600">
            <x-icon name="folder" class="mb-2 h-8 w-8 text-emerald-600 dark:text-emerald-400" style="duotone" />
            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Arquivo</span>
            <span class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Documentos</span>
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Próximas reuniões</h2>
            <ul class="mt-4 space-y-3 text-sm">
                @forelse($upcomingMeetings as $m)
                    <li class="flex justify-between gap-3 border-b border-gray-100 pb-3 last:border-0 last:pb-0 dark:border-slate-700">
                        <a href="{{ route($sb.'.reunioes.show', $m) }}" class="font-medium text-gray-900 hover:text-emerald-700 dark:text-white dark:hover:text-emerald-300">{{ $m->title ?: $m->type }}</a>
                        <span class="shrink-0 text-gray-500 dark:text-gray-400">{{ $m->starts_at->format('d/m/Y H:i') }}</span>
                    </li>
                @empty
                    <li class="text-gray-500 dark:text-gray-400">Nenhuma registada.</li>
                @endforelse
            </ul>
        </div>
        <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Reuniões esta semana</h2>
            <ul class="mt-4 space-y-3 text-sm">
                @forelse($meetingsThisWeek as $m)
                    <li class="flex justify-between gap-3 border-b border-gray-100 pb-3 last:border-0 last:pb-0 dark:border-slate-700">
                        <span class="font-medium text-gray-900 dark:text-white">{{ $m->title ?: $m->type }}</span>
                        <span class="shrink-0 text-gray-500 dark:text-gray-400">{{ $m->starts_at->format('D d/m H:i') }}</span>
                    </li>
                @empty
                    <li class="text-gray-500 dark:text-gray-400">Nenhuma nesta semana.</li>
                @endforelse
            </ul>
        </div>
        <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 lg:col-span-2">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Convocatórias publicadas (próximas)</h2>
            <ul class="mt-4 space-y-3 text-sm">
                @forelse($extraordinarySoon as $c)
                    <li class="flex justify-between gap-3 border-b border-gray-100 pb-3 last:border-0 last:pb-0 dark:border-slate-700">
                        <a href="{{ route($sb.'.convocatorias.show', $c) }}" class="font-medium text-gray-900 hover:text-emerald-700 dark:text-white dark:hover:text-emerald-300">{{ $c->title }}</a>
                        <span class="shrink-0 text-gray-500 dark:text-gray-400">{{ $c->assembly_at->format('d/m/Y') }}</span>
                    </li>
                @empty
                    <li class="text-gray-500 dark:text-gray-400">Nenhuma.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
