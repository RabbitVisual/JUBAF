@extends($layout)

@section('title', $church->displayName())

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10" x-data="{ tab: 'cadastro' }">
    @include('igrejas::paineldiretoria.partials.subnav', ['active' => 'list'])

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-cyan-700 hover:underline dark:text-cyan-400">
                <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
                Voltar à lista
            </a>
            <h1 class="mt-3 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-600 text-white shadow-lg shadow-cyan-600/25">
                    <x-module-icon module="Igrejas" class="h-7 w-7" />
                </span>
                {{ $church->displayName() }}
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ $church->city ?? 'Sem cidade' }}
                @if($church->state) · {{ $church->state }} @endif
                · <span class="font-mono text-xs text-gray-500">{{ $church->slug }}</span>
            </p>
            @if($church->uuid)
                <p class="mt-1 font-mono text-[11px] text-gray-400 dark:text-gray-500">UUID {{ $church->uuid }}</p>
            @endif
            @if($church->sector)
                <p class="mt-1 text-xs font-semibold text-cyan-800 dark:text-cyan-300">Setor: {{ $church->sector }} · Cooperação: {{ $church->cooperation_status }} · CRM: {{ $church->crm_status ?? '—' }}</p>
            @endif
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            @isset($members)
                @can('view', $church)
                    <a href="{{ route($routePrefix.'.members.export.csv', $church) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 shadow-sm transition hover:border-cyan-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        <x-icon name="download" class="h-4 w-4 text-cyan-600 dark:text-cyan-400" style="duotone" />
                        Membros CSV
                    </a>
                @endcan
            @endisset
            @can('update', $church)
                <a href="{{ route($routePrefix.'.edit', $church) }}" class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">
                    <x-icon name="pen-to-square" class="h-4 w-4" style="solid" />
                    Editar
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
        <div class="rounded-2xl border border-cyan-100 bg-white p-5 shadow-sm dark:border-cyan-900/40 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase tracking-wide text-cyan-800/80 dark:text-cyan-400/90">Líderes</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $church->leaders_count }}</p>
        </div>
        <div class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm dark:border-sky-900/40 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase tracking-wide text-sky-800/80 dark:text-sky-400/90">Jovens</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $church->jovens_members_count }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800 sm:col-span-2">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Situação CRM</p>
            <p class="mt-3">
                @php $crm = $church->crm_status ?? ($church->is_active ? \Modules\Igrejas\App\Models\Church::CRM_ATIVA : \Modules\Igrejas\App\Models\Church::CRM_INATIVA); @endphp
                @if($crm === \Modules\Igrejas\App\Models\Church::CRM_ATIVA)
                    <span class="inline-flex rounded-lg bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-900 ring-1 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-100 dark:ring-emerald-800/50">Ativa</span>
                @elseif($crm === \Modules\Igrejas\App\Models\Church::CRM_INADIMPLENTE)
                    <span class="inline-flex rounded-lg bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-900 ring-1 ring-amber-200 dark:bg-amber-900/40 dark:text-amber-100 dark:ring-amber-800/50">Inadimplente</span>
                @else
                    <span class="inline-flex rounded-lg bg-slate-200 px-2.5 py-1 text-xs font-bold text-slate-800 dark:bg-slate-700 dark:text-slate-200">Inativa</span>
                @endif
            </p>
        </div>
    </div>

    @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
        <div class="rounded-xl border border-amber-200 bg-amber-50/80 px-4 py-3 text-sm text-amber-900 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-200">
            {{ $pendingRequestsCount }} pedido(s) em análise para esta congregação.
            @can('igrejas.requests.review')
                <a href="{{ route('diretoria.igrejas.requests.index', ['status' => 'submitted']) }}" class="ml-2 font-bold underline">Ver fila</a>
            @endcan
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="flex flex-wrap gap-1 border-b border-gray-200 px-2 pt-2 dark:border-slate-600">
            <button type="button" @click="tab = 'cadastro'" :class="tab === 'cadastro' ? 'border-cyan-600 text-cyan-700 dark:text-cyan-300' : 'border-transparent text-gray-500 hover:text-gray-800 dark:text-gray-400'" class="rounded-t-lg border-b-2 px-4 py-3 text-sm font-bold transition">Dados cadastrais</button>
            <button type="button" @click="tab = 'lideranca'" :class="tab === 'lideranca' ? 'border-cyan-600 text-cyan-700 dark:text-cyan-300' : 'border-transparent text-gray-500 hover:text-gray-800 dark:text-gray-400'" class="rounded-t-lg border-b-2 px-4 py-3 text-sm font-bold transition">Liderança</button>
            <button type="button" @click="tab = 'financeiro'" :class="tab === 'financeiro' ? 'border-cyan-600 text-cyan-700 dark:text-cyan-300' : 'border-transparent text-gray-500 hover:text-gray-800 dark:text-gray-400'" class="rounded-t-lg border-b-2 px-4 py-3 text-sm font-bold transition">Financeiro</button>
            <button type="button" @click="tab = 'documentos'" :class="tab === 'documentos' ? 'border-cyan-600 text-cyan-700 dark:text-cyan-300' : 'border-transparent text-gray-500 hover:text-gray-800 dark:text-gray-400'" class="rounded-t-lg border-b-2 px-4 py-3 text-sm font-bold transition">Arquivos</button>
        </div>

        <div class="p-6 sm:p-8">
            <div x-show="tab === 'cadastro'" x-cloak class="space-y-6">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-xl border border-gray-100 p-4 dark:border-slate-700">
                        <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Razão social</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $church->legal_name ?? $church->name }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-100 p-4 dark:border-slate-700">
                        <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Nome fantasia</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $church->trade_name ?? $church->name }}</p>
                    </div>
                </div>
                @if($church->foundation_date || $church->institutionalAgeYears() !== null)
                    <div class="rounded-xl border border-gray-200/90 bg-gray-50/50 p-4 text-sm dark:border-slate-700 dark:bg-slate-900/40">
                        <span class="font-bold text-gray-900 dark:text-white">Institucional:</span>
                        @if($church->foundation_date)
                            <span class="text-gray-600 dark:text-gray-400"> fundação {{ $church->foundation_date->format('d/m/Y') }}</span>
                        @endif
                        @if($church->institutionalAgeYears() !== null)
                            <span class="text-gray-600 dark:text-gray-400"> (~{{ $church->institutionalAgeYears() }} anos)</span>
                        @endif
                    </div>
                @endif
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Endereço e contacto</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        @if($church->postal_code || $church->street)
                            <div>
                                <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Endereço estruturado</dt>
                                <dd class="mt-1 text-gray-800 dark:text-gray-200">
                                    @if($church->postal_code)CEP {{ $church->postal_code }} — @endif
                                    {{ trim(implode(', ', array_filter([$church->street, $church->number, $church->complement, $church->district, $church->city, $church->state]))) ?: '—' }}
                                    @if($church->country) ({{ $church->country }}) @endif
                                </dd>
                            </div>
                        @endif
                        @if($church->address)
                            <div><dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Endereço (livre)</dt><dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $church->address }}</dd></div>
                        @endif
                        @if($church->phone)
                            <div><dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Telefone</dt><dd class="mt-1"><a href="tel:{{ preg_replace('/\s+/', '', $church->phone) }}" class="font-semibold text-cyan-700 hover:underline dark:text-cyan-400">{{ $church->phone }}</a></dd></div>
                        @endif
                        @if($church->email)
                            <div><dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">E-mail</dt><dd class="mt-1"><a href="mailto:{{ $church->email }}" class="font-semibold text-cyan-700 hover:underline break-all dark:text-cyan-400">{{ $church->email }}</a></dd></div>
                        @endif
                        @if($church->joined_at)
                            <div><dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Filiação JUBAF</dt><dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $church->joined_at->format('d/m/Y') }}</dd></div>
                        @endif
                    </dl>
                </div>
                @if($church->asbaf_notes)
                    <div class="rounded-xl border border-cyan-100/80 bg-cyan-50/40 p-4 dark:border-cyan-900/40 dark:bg-cyan-950/20">
                        <p class="text-xs font-bold uppercase text-cyan-900 dark:text-cyan-400">Notas ASBAF / filiação</p>
                        <p class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-gray-700 dark:text-gray-300">{{ $church->asbaf_notes }}</p>
                    </div>
                @endif
            </div>

            <div x-show="tab === 'lideranca'" x-cloak class="space-y-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm dark:border-sky-900/40 dark:bg-slate-800">
                        <p class="text-xs font-bold uppercase text-sky-800 dark:text-sky-400">Pastor nomeado</p>
                        <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $church->pastor?->name ?? '—' }}</p>
                    </div>
                    <div class="rounded-2xl border border-cyan-100 bg-white p-5 shadow-sm dark:border-cyan-900/40 dark:bg-slate-800">
                        <p class="text-xs font-bold uppercase text-cyan-800 dark:text-cyan-400">Líder Unijovem</p>
                        <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $church->unijovemLeader?->name ?? '—' }}</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Pastores e líderes (papéis Spatie)</h3>
                    @if(isset($localLeadership) && $localLeadership->isNotEmpty())
                        <ul class="mt-3 divide-y divide-gray-100 dark:divide-slate-700">
                            @foreach($localLeadership as $u)
                                <li class="flex flex-wrap items-center justify-between gap-2 py-2 text-sm">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $u->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $u->roles->pluck('name')->implode(', ') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Sem utilizadores com papéis pastor/líder vinculados a esta igreja.</p>
                    @endif
                </div>
                @isset($members)
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Todos os membros vinculados ({{ $members->total() }})</h3>
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 text-left text-xs font-bold uppercase text-gray-500 dark:bg-slate-900 dark:text-gray-400">
                                    <tr>
                                        <th class="px-3 py-2">Nome</th>
                                        <th class="px-3 py-2">E-mail</th>
                                        <th class="px-3 py-2">Papéis</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                    @foreach($members as $u)
                                        <tr>
                                            <td class="px-3 py-2 font-medium text-gray-900 dark:text-white">{{ $u->name }}</td>
                                            <td class="px-3 py-2 text-gray-600 dark:text-gray-300">{{ $u->email }}</td>
                                            <td class="px-3 py-2 text-xs text-gray-600 dark:text-gray-300">{{ $u->roles->pluck('name')->implode(', ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $members->links() }}</div>
                    </div>
                @endisset
            </div>

            <div x-show="tab === 'financeiro'" x-cloak class="space-y-6">
                @isset($cotasSummary)
                    <div class="rounded-xl border border-amber-100 bg-amber-50/40 p-5 dark:border-amber-900/40 dark:bg-amber-950/20">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Cotas e obrigações</h3>
                        <dl class="mt-3 grid gap-3 text-sm sm:grid-cols-3">
                            <div><dt class="text-xs font-bold uppercase text-gray-500">Pendentes</dt><dd class="mt-1 font-semibold tabular-nums">{{ $cotasSummary['pending_count'] }}</dd></div>
                            <div><dt class="text-xs font-bold uppercase text-gray-500">Valor pendente (ref.)</dt><dd class="mt-1 font-semibold tabular-nums">{{ number_format($cotasSummary['overdue_amount'], 2, ',', '.') }}</dd></div>
                            <div><dt class="text-xs font-bold uppercase text-gray-500">Pagas (ano atual)</dt><dd class="mt-1 font-semibold tabular-nums">{{ $cotasSummary['paid_last_year'] }}</dd></div>
                        </dl>
                    </div>
                @endisset
                @if(isset($financeSummary) && $financeSummary && Route::has('diretoria.financeiro.transactions.index'))
                    <div class="rounded-xl border border-gray-200/90 bg-white p-5 dark:border-slate-700">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Lançamentos com esta igreja</h3>
                            <a href="{{ route('diretoria.financeiro.transactions.index', ['church_id' => $church->id]) }}" class="text-sm font-bold text-cyan-700 hover:underline dark:text-cyan-400">Ver lançamentos</a>
                        </div>
                        <dl class="mt-4 grid grid-cols-2 gap-4 text-sm sm:max-w-md">
                            <div><dt class="text-xs font-bold uppercase text-gray-500">Entradas</dt><dd class="mt-1 font-semibold text-emerald-700 dark:text-emerald-400">{{ number_format($financeSummary['in_sum'], 2, ',', '.') }}</dd></div>
                            <div><dt class="text-xs font-bold uppercase text-gray-500">Saídas</dt><dd class="mt-1 font-semibold text-rose-700 dark:text-rose-400">{{ number_format($financeSummary['out_sum'], 2, ',', '.') }}</dd></div>
                        </dl>
                    </div>
                @endif
                @if(! isset($financeSummary) && (! isset($cotasSummary) || ($cotasSummary['pending_count'] ?? 0) === 0))
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sem dados financeiros agregados para esta igreja. Utilize o módulo Financeiro para lançamentos e obrigações.</p>
                @endif
            </div>

            <div x-show="tab === 'documentos'" x-cloak class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">Documentos e atas da secretaria vinculados a esta congregação.</p>
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-slate-700">
                        <dt class="text-xs font-bold uppercase text-gray-500">Documentos</dt>
                        <dd class="mt-2 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $secretariaDocumentsCount ?? 0 }}</dd>
                    </div>
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-slate-700">
                        <dt class="text-xs font-bold uppercase text-gray-500">Atas</dt>
                        <dd class="mt-2 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $secretariaMinutesCount ?? 0 }}</dd>
                    </div>
                </dl>
                @if(Route::has('diretoria.secretaria.atas.index'))
                    <a href="{{ route('diretoria.secretaria.atas.index', ['church_id' => $church->id]) }}" class="inline-flex text-sm font-bold text-cyan-700 hover:underline dark:text-cyan-400">Abrir atas (filtro)</a>
                @endif
            </div>
        </div>
    </div>

    @isset($upcomingEvents)
        @if($upcomingEvents->isNotEmpty() && Route::has('diretoria.calendario.events.index'))
            <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Próximos eventos no calendário (esta igreja)</h2>
                    <a href="{{ route('diretoria.calendario.events.index', ['from' => now()->format('Y-m-d')]) }}" class="text-sm font-bold text-cyan-700 hover:underline dark:text-cyan-400">Calendário</a>
                </div>
                <ul class="mt-4 divide-y divide-gray-100 text-sm dark:divide-slate-700">
                    @foreach($upcomingEvents as $ev)
                        <li class="flex justify-between gap-3 py-2">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $ev->title }}</span>
                            <span class="shrink-0 text-gray-500">{{ $ev->starts_at->format('d/m H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endisset
</div>
@endsection
