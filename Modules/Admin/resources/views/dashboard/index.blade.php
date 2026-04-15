@extends('admin::layouts.admin')

@section('title', 'Dashboard — JUBAF')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-poppins">Painel de Controle</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Visão geral do sistema e indicadores de performance.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-slate-800 px-3 py-1 rounded-full font-mono">
                v{{ config('app.version', '1.0.0') }}
            </span>
        </div>
    </div>

    <!-- Smart Widgets (Cockpit UI) -->
    @if(isset($smartWidgets))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Widget A: Redação & Transparência -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute -top-4 -right-4 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                <x-icon name="newspaper" class="w-24 h-24" style="duotone" />
            </div>


            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                    <x-icon name="pen-nib" class="w-6 h-6" style="duotone" />
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white font-poppins text-lg">Redação & Transparência</h3>
            </div>


            <div class="flex-1">
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-5xl font-extrabold text-gray-900 dark:text-white font-inter tracking-tight tabular-nums">
                        {{ $smartWidgets['newsroom_drafts'] ?? 0 }}
                    </span>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Obras aguardando divulgação</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                    Módulo Newsroom identificou obras concluídas aptas para publicação automática.
                </p>
            </div>


            <div class="mt-auto">
                <a href="{{ route('admin.blog.create') }}?import_from_demandas=1" class="inline-flex items-center justify-center w-full px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg active:scale-[0.98]">
                    <x-icon name="wand-magic-sparkles" class="w-4 h-4 mr-2" /> Gerar Notícias
                </a>
            </div>
        </div>

        <!-- Widget B: Sincronização móvel -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute -top-4 -right-4 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                <x-icon name="satellite-dish" class="w-24 h-24" style="duotone" />
            </div>


            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                    <x-icon name="wifi" class="w-6 h-6" style="duotone" />
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white font-poppins text-lg">Sincronização móvel</h3>
            </div>


            <div class="flex-1 space-y-3">
                @forelse($smartWidgets['recent_syncs'] ?? [] as $sync)
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full {{ $sync->is_recent ? 'bg-green-500 animate-pulse' : 'bg-amber-500' }}"></div>
                        <span class="font-medium text-gray-700 dark:text-gray-300 truncate max-w-[120px]">
                            {{ Str::limit($sync->user_name, 15) }}
                        </span>
                    </div>
                    <div class="text-right text-xs text-gray-500">
                        <span class="block">{{ $sync->time_ago }}</span>
                        @if($sync->photos_count > 0)
                        <span class="text-blue-500 flex items-center justify-end gap-1">
                            <x-icon name="camera" class="w-3 h-3" /> {{ $sync->photos_count }}
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                @endforelse
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                    <span class="text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">Painel líderes ativo</span>
                </div>

                <div class="space-y-2 mb-4">
                    @forelse($smartWidgets['recent_syncs'] as $sync)
                    <div class="flex items-center justify-between text-xs bg-gray-50 dark:bg-slate-700/50 p-2 rounded-lg">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">{{ Str::limit($sync->user_name, 12) }}</span>
                        <span class="text-gray-400 tabular-nums">{{ $sync->time_ago }}</span>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>


            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                <a href="{{ route('admin.audit.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center justify-center">
                    Ver logs de sincronização <x-icon name="arrow-right" class="w-3 h-3 ml-1" />
                </a>
            </div>
        </div>

        <!-- Widget C: Almoxarifado Crítico -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute -top-4 -right-4 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                <x-icon name="boxes-stacked" class="w-24 h-24" style="duotone" />
            </div>


            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg text-amber-600 dark:text-amber-400">
                    <x-icon name="box-open" class="w-6 h-6" style="duotone" />
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white font-poppins text-lg">Almoxarifado Crítico</h3>
            </div>


            <div class="flex-1">
                @if(count($smartWidgets['low_stock_items'] ?? []) > 0)
                <div class="space-y-3 mb-6">
                    @foreach($smartWidgets['low_stock_items'] ?? [] as $item)
                    <div class="flex items-center justify-between p-3 bg-red-50/50 dark:bg-red-900/10 rounded-xl border border-red-100 dark:border-red-900/20">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="flex-shrink-0 w-8 h-8 bg-white dark:bg-slate-800 rounded-lg flex items-center justify-center shadow-sm">
                                <x-icon name="box" class="w-4 h-4 text-amber-600" />
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $item->nome }}</span>
                                @if($item->ncm)
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-mono">NCM: {{ $item->ncm->codigo }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-xs font-black text-red-600 dark:text-red-400 font-inter tabular-nums">
                            {{ (int)$item->quantidade_estoque }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-10 opacity-60">
                    <x-icon name="circle-check" class="w-12 h-12 text-green-500 mb-3" style="duotone" />
                    <p class="text-sm font-bold text-gray-600 dark:text-gray-400">Estoque Saudável</p>
                </div>
                @endif
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                @if(Route::has('admin.materiais.index'))
                <a href="{{ route('admin.materiais.index') }}" class="text-xs text-amber-600 dark:text-amber-400 hover:underline flex items-center justify-center">
                    Gerenciar estoque <x-icon name="arrow-right" class="w-3 h-3 ml-1" />
                </a>
                @else
                <a href="{{ route('admin.blog.index') }}" class="text-xs text-amber-600 dark:text-amber-400 hover:underline flex items-center justify-center">
                    Ir ao blog <x-icon name="arrow-right" class="w-3 h-3 ml-1" />
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Standard Stats Grid (Legacy but Polished) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Users -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-slate-700 flex items-center">
            <div class="p-3 rounded-full bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 mr-4">
                <x-icon name="users" class="w-6 h-6" style="duotone" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Usuários Ativos</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-inter tabular-nums">{{ $stats['active_users'] }}</p>
            </div>
        </div>

        <!-- Logs Today -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-slate-700 flex items-center">
            <div class="p-3 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 mr-4">
                <x-icon name="clock-rotate-left" class="w-6 h-6" style="duotone" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Atividades Hoje</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-inter tabular-nums">{{ $stats['logs_today'] }}</p>
            </div>
        </div>

        <!-- Pending Field Requests -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-slate-700 flex items-center">
            <div class="p-3 rounded-full bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 mr-4">
                <x-icon name="clipboard-list" class="w-6 h-6" style="duotone" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Solicitações Pendentes</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-inter tabular-nums">{{ $stats['solicitacoes_lider_pendentes'] }}</p>
            </div>
        </div>

        <!-- System Health -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-slate-700 flex items-center group hover:bg-green-50/50 dark:hover:bg-green-900/10 transition-colors">
            <div class="p-3 rounded-full bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-4">
                <x-icon name="server" class="w-6 h-6" style="duotone" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status do Sistema</p>
                <div class="flex items-center gap-2">
                    <p class="text-sm font-bold {{ ($smartWidgets['system_health'] ?? true) ? 'text-green-600' : 'text-amber-600' }}">
                        {{ ($smartWidgets['system_health'] ?? true) ? 'Operacional' : 'Base NCM pendente' }}
                    </p>
                    <div class="w-2 h-2 rounded-full {{ ($smartWidgets['system_health'] ?? true) ? 'bg-green-500' : 'bg-amber-500' }}"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Logs -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white font-poppins">Atividade Recente</h3>
                <a href="{{ route('admin.audit.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Ver tudo</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-slate-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">Usuário</th>
                            <th class="px-6 py-3">Ação</th>
                            <th class="px-6 py-3">Módulo</th>
                            <th class="px-6 py-3">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLogs as $log)
                        <tr class="bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-slate-600 flex items-center justify-center text-xs">
                                    {{ substr($log->user->name ?? 'S', 0, 1) }}
                                </div>
                                {{ Str::limit($log->user->name ?? 'Sistema', 15) }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $log->action }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                    {{ $log->module ?? 'Geral' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400 tabular-nums">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Module Distribution (Zero-CDN CSS Bars) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="font-bold text-gray-900 dark:text-white font-poppins text-lg mb-6">Distribuição de Dados</h3>

            <div class="space-y-5">
                @php
                    $maxCount = (count($moduleStats) > 0 && max($moduleStats) > 0) ? max($moduleStats) : 1;
                    $colors = ['bg-blue-500', 'bg-emerald-500', 'bg-amber-500', 'bg-purple-500', 'bg-rose-500', 'bg-indigo-500', 'bg-cyan-500', 'bg-teal-500'];
                    $i = 0;
                @endphp
                @foreach($moduleStats as $module => $count)
                    @php
                        $percentage = ($count / $maxCount) * 100;
                        $color = $colors[$i % count($colors)];
                        $i++;
                    @endphp
                    <div class="space-y-1 group">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-400 font-medium group-hover:text-gray-900 dark:group-hover:text-white transition-colors">{{ $module }}</span>
                            <span class="font-bold text-gray-900 dark:text-white font-inter tabular-nums">{{ number_format($count, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                            <div class="{{ $color }} h-full rounded-full transition-all duration-1000 ease-out group-hover:brightness-110" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-700">
                <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-widest font-bold text-center">
                    Inteligência de Dados Local
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Local-only dashboard logic (Zero-CDN)
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('actionsChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const data = @json($chartData['modules_activity']);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    data: Object.values(data),
                    backgroundColor: [
                        '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#EC4899', '#6366F1', '#14B8A6', '#F97316', '#64748B'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .font-inter { font-family: 'Inter', sans-serif; }
    .font-poppins { font-family: 'Poppins', sans-serif; }
    .tabular-nums { font-variant-numeric: tabular-nums; }
</style>
@endpush
