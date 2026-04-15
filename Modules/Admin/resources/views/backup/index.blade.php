@extends('admin::layouts.admin')

@section('title', 'Gerenciamento de Backups')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="circle-stack" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Backups</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-cyan-600 dark:hover:text-cyan-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Gerenciamento de Dados</span>
            </nav>
        </div>

        <form action="{{ route('admin.backup.create') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 focus:ring-4 focus:ring-cyan-200 dark:focus:ring-cyan-900">
                <x-icon name="plus" class="w-5 h-5" style="solid" />
                Novo Backup
            </button>
        </form>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <!-- Total Backups -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/60 relative overflow-hidden group hover:border-cyan-200 dark:hover:border-cyan-800 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <x-icon name="archive-box" class="w-24 h-24 text-cyan-600" style="duotone" />
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Total de Arquivos</p>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">
                    {{ count($backups) }}
                </h3>
                <p class="text-xs font-bold text-cyan-600 dark:text-cyan-400 mt-2 flex items-center gap-1">
                    <x-icon name="check-circle" class="w-4 h-4" style="solid" />
                    Armazenados localmente
                </p>
            </div>
        </div>

        <!-- Total Size -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/60 relative overflow-hidden group hover:border-cyan-200 dark:hover:border-cyan-800 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <x-icon name="server" class="w-24 h-24 text-cyan-600" style="duotone" />
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Espaço Ocupado</p>
                @php
                    $totalSize = array_sum(array_column($backups, 'size'));
                    $sizeFormatted = $totalSize > 1048576
                        ? round($totalSize / 1048576, 2) . ' MB'
                        : round($totalSize / 1024, 2) . ' KB';
                @endphp
                <h3 class="text-3xl font-black text-gray-900 dark:text-white">
                    {{ $sizeFormatted }}
                </h3>
                <p class="text-xs font-bold text-slate-500 mt-2 flex items-center gap-1">
                    <x-icon name="database" class="w-4 h-4" style="solid" />
                    Base de dados MySQL
                </p>
            </div>
        </div>

        <!-- Last Backup -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/60 relative overflow-hidden group hover:border-cyan-200 dark:hover:border-cyan-800 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <x-icon name="clock" class="w-24 h-24 text-cyan-600" style="duotone" />
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Último Backup</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-white truncate">
                    @if(count($backups) > 0)
                        {{ \Carbon\Carbon::parse($backups[0]['created_at'])->diffForHumans() }}
                    @else
                        Nenhum registro
                    @endif
                </h3>
                <p class="text-xs font-bold text-slate-500 mt-2 flex items-center gap-1">
                    @if(count($backups) > 0)
                        {{ \Carbon\Carbon::parse($backups[0]['created_at'])->format('d/m/Y H:i') }}
                    @else
                        ----
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Backups List -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden font-sans">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <x-icon name="table-cells" class="w-5 h-5 text-cyan-500" style="duotone" />
                <h2 class="text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Histórico de Arquivos</h2>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-700">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Arquivo</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Tamanho</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Data de Criação</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($backups as $backup)
                    <tr class="group hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center text-cyan-600 dark:text-cyan-400 group-hover:bg-cyan-200 dark:group-hover:bg-cyan-900/50 transition-colors">
                                    <x-icon name="document-text" class="w-5 h-5" style="duotone" />
                                </div>
                                <div>
                                    <span class="block text-sm font-bold text-gray-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">
                                        {{ $backup['name'] }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">SQL Dump</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700/50 px-2 py-1 rounded">
                                {{ $backup['size'] > 1048576
                                    ? round($backup['size'] / 1048576, 2) . ' MB'
                                    : round($backup['size'] / 1024, 2) . ' KB'
                                }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($backup['created_at'])->format('d/m/Y') }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($backup['created_at'])->format('H:i:s') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.backup.download', $backup['name']) }}"
                                   class="p-2 text-slate-500 hover:text-cyan-600 dark:text-slate-400 dark:hover:text-cyan-400 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 rounded-lg transition-colors"
                                   title="Download">
                                    <x-icon name="arrow-down-tray" class="w-4 h-4" style="duotone" />
                                </a>

                                <form action="{{ route('admin.backup.restore', $backup['name']) }}" method="POST" class="inline-block" onsubmit="return confirm('ATENÇÃO: Isso irá substituir TODA a base de dados atual pelo backup selecionado. Esta ação não pode ser desfeita. Tem certeza?');">
                                    @csrf
                                    <button type="submit"
                                            class="p-2 text-slate-500 hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors"
                                            title="Restaurar Base de Dados">
                                        <x-icon name="arrow-path" class="w-4 h-4" style="duotone" />
                                    </button>
                                </form>

                                <form action="{{ route('admin.backup.destroy', $backup['name']) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir este backup permanentemente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-slate-500 hover:text-red-600 dark:text-slate-400 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                            title="Excluir">
                                        <x-icon name="trash" class="w-4 h-4" style="duotone" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-2">
                                    <x-icon name="archive-box-x-mark" class="w-8 h-8 text-slate-400" style="duotone" />
                                </div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">Nenhum backup encontrado</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                                    Não há arquivos de backup armazenados no sistema. Crie um novo backup para proteger seus dados.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Note -->
    <div class="bg-cyan-50 dark:bg-cyan-900/10 border border-cyan-100 dark:border-cyan-800/30 rounded-2xl p-4 flex items-start gap-3">
        <x-icon name="information-circle" class="w-5 h-5 text-cyan-600 dark:text-cyan-400 mt-0.5" style="duotone" />
        <div class="flex-1">
            <h4 class="text-sm font-bold text-cyan-800 dark:text-cyan-300">Informações Importantes</h4>
            <p class="text-xs text-cyan-700 dark:text-cyan-400 mt-1 leading-relaxed">
                Os backups são armazenados localmente no servidor. Recomenda-se realizar o download e armazenar cópias de segurança em locais externos regularmente. A restauração de um backup substituirá todos os dados atuais do banco de dados.
            </p>
        </div>
    </div>
</div>
@endsection
