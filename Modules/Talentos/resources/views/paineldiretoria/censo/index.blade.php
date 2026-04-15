@extends($layout)

@section('title', 'Censo e radar de talentos')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('talentos::paineldiretoria.partials.subnav', ['active' => 'census'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-violet-800 dark:text-violet-400">Diretoria · Talentos</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-600/25">
                    <x-module-icon module="Talentos" class="h-7 w-7" />
                </span>
                Censo regional e talentos validados
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                Visão agregada por setor JUBAF nas congregações e competências mais frequentes entre perfis pesquisáveis com validação local.
            </p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        @foreach($summary as $row)
            <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $row['sector_name'] }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Jovens (perfil com role «jovens»)</p>
                <p class="mt-3 text-3xl font-black tabular-nums text-violet-600 dark:text-violet-400">{{ $row['youth_count'] }}</p>
                @if($row['average_age'] !== null)
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Idade média (com data de nascimento): <strong>{{ $row['average_age'] }}</strong> anos</p>
                @endif
                <div class="mt-4 space-y-2">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Faixas etárias</p>
                    @foreach($row['age_buckets'] as $label => $n)
                        <div class="flex items-center gap-3">
                            <span class="w-14 shrink-0 text-xs font-semibold text-gray-600 dark:text-gray-300">{{ $label }}</span>
                            <div class="h-2 flex-1 overflow-hidden rounded-full bg-gray-100 dark:bg-slate-900">
                                @php $max = max(1, $row['youth_count']); $pct = min(100, ($n / $max) * 100); @endphp
                                <div class="h-full rounded-full bg-gradient-to-r from-violet-500 to-fuchsia-500" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="w-8 text-right text-xs tabular-nums text-gray-700 dark:text-gray-200">{{ $n }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Competências mais comuns (validadas)</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Entre perfis marcados como pesquisáveis no diretório.</p>
        <ul class="mt-4 divide-y divide-gray-100 dark:divide-slate-700">
            @forelse($topSkills as $t)
                <li class="flex items-center justify-between py-3 text-sm">
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $t->name }}</span>
                    <span class="rounded-lg bg-violet-100 px-2 py-0.5 text-xs font-bold text-violet-900 dark:bg-violet-900/40 dark:text-violet-100">{{ $t->profile_count }} perfis</span>
                </li>
            @empty
                <li class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">Ainda sem competências validadas no sistema.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
