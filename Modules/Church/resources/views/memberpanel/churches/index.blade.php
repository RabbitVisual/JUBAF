@extends('memberpanel::components.layouts.master')

@section('page-title', 'Igrejas e setores')
@section('title', 'Igrejas e Setores')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Igrejas & lideranças',
        'subtitle' => 'Rol de igrejas, UNIJOVEM e líderes no painel — mesmo padrão visual da tesouraria.',
        'badge' => 'Igrejas',
    ])
        @slot('actions')
            <a href="{{ route('memberpanel.churches.create') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-lg shadow-indigo-500/20 transition-all">
                <x-icon name="plus" style="duotone" class="w-4 h-4 mr-2" />
                Cadastrar igreja
            </a>
        @endslot

        <div
            class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl shadow-xl dark:shadow-2xl border border-gray-100 dark:border-slate-800 mb-8">
            <div class="absolute inset-0 opacity-20 dark:opacity-40 pointer-events-none">
                <div class="absolute -top-24 -left-20 w-96 h-96 bg-indigo-400 dark:bg-indigo-600 rounded-full blur-[100px]"></div>
                <div class="absolute top-1/2 -right-20 w-80 h-80 bg-purple-400 dark:bg-purple-600 rounded-full blur-[100px]"></div>
            </div>
            <div class="relative px-8 py-10 z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 mb-4">
                    <x-icon name="place-of-worship" style="duotone" class="w-3 h-3 text-indigo-600 dark:text-indigo-400" />
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">ASBAF / JUBAF</span>
                </div>
                <p class="text-gray-500 dark:text-slate-300 font-medium max-w-2xl text-lg leading-relaxed">
                    Filtre por nome, setor ou status e gira o cadastro associacional sem sair do <span class="font-semibold text-gray-800 dark:text-white">/painel</span>.
                </p>
            </div>
        </div>

        <!-- Filters Block -->
        <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm mb-8">
            <form action="{{ route('memberpanel.churches.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:flex-1">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Busca</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-icon name="magnifying-glass" class="w-4 h-4 text-gray-400" />
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 dark:border-slate-700 rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="Nome da igreja, líder ou UNIJOVEM...">
                    </div>
                </div>

                <div class="w-full md:w-48">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Setor</label>
                    <div class="relative">
                        <select name="sector" class="block w-full px-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all appearance-none">
                            <option value="">Todos</option>
                            @foreach ($sectors as $s)
                                <option value="{{ $s }}" @selected(request('sector') == $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <x-icon name="chevron-down" class="w-4 h-4 text-gray-400" />
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-48">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Status</label>
                    <div class="relative">
                        <select name="status" class="block w-full px-3 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all appearance-none">
                            <option value="">Qualquer status</option>
                            <option value="active" @selected(request('status') === 'active')>Ativo</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Inativo</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <x-icon name="chevron-down" class="w-4 h-4 text-gray-400" />
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:w-auto px-6 py-2.5 bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 text-white font-bold rounded-xl transition-colors whitespace-nowrap">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'sector', 'status']))
                        <a href="{{ route('memberpanel.churches.index') }}" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors tooltip" title="Limpar Filtros">
                            <x-icon name="xmark" class="w-5 h-5" />
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Lista Grid de Igrejas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($churches as $church)
                <div class="group bg-white dark:bg-gray-800 rounded-3xl shadow-sm hover:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col transition-all duration-300 relative">
                    <!-- Top Ribbon / Status -->
                    <div class="absolute top-4 right-4 z-10 flex gap-2">
                         @if ($church->sector)
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-800 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm border border-gray-100">
                                {{ $church->sector }}
                            </span>
                        @endif
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm {{ $church->is_active ? 'bg-green-500/90 text-white backdrop-blur-sm' : 'bg-gray-500/90 text-white backdrop-blur-sm' }}">
                            {{ $church->is_active ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>

                    <!-- Header/Cover -->
                    <div class="relative h-36 overflow-hidden bg-gradient-to-br from-purple-500/10 to-indigo-600/10 flex items-center justify-center p-6 border-b border-gray-100 dark:border-gray-700">
                        @if ($church->logo_path)
                            <img src="{{ Storage::url($church->logo_path) }}" alt="{{ $church->name }}" class="w-full h-full object-contain p-2 group-hover:scale-110 transition-transform duration-700 drop-shadow-md">
                        @else
                            <x-icon name="place-of-worship" style="duotone" class="w-16 h-16 text-purple-500/30 group-hover:scale-110 transition-transform duration-700" />
                        @endif
                    </div>

                    <!-- Body -->
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white line-clamp-2 leading-tight mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                            {{ $church->name }}
                        </h3>

                        <div class="space-y-3 mt-4 flex-1">
                            @if ($church->unijovem_name)
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 p-1.5 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400">
                                        <x-icon name="users" style="duotone" class="w-3.5 h-3.5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Unijovem / Jovens</p>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $church->unijovem_name }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($church->leader_name)
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                                        <x-icon name="user-tie" style="duotone" class="w-3.5 h-3.5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Líder Principal</p>
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate max-w-[150px]">{{ $church->leader_name }}</p>
                                            @if ($church->leader_phone)
                                                <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $church->leader_phone) }}" target="_blank" class="text-green-500 hover:text-green-600 transition-colors" title="Chamar no WhatsApp">
                                                    <i class="fa-brands fa-whatsapp"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if ($church->city)
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 p-1.5 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                        <x-icon name="location-dot" style="duotone" class="w-3.5 h-3.5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Localidade</p>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $church->city }}{{ $church->neighborhood ? ' - ' . $church->neighborhood : '' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div class="flex gap-2">
                                <a href="{{ route('memberpanel.churches.show', $church) }}" class="p-2 text-gray-400 hover:text-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-xl transition-all tooltip" title="Visualizar">
                                    <x-icon name="eye" style="duotone" class="w-4 h-4" />
                                </a>
                                <a href="{{ route('memberpanel.churches.edit', $church) }}" class="p-2 text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all tooltip" title="Editar">
                                    <x-icon name="pencil" style="duotone" class="w-4 h-4" />
                                </a>
                            </div>
                            <form action="{{ route('memberpanel.churches.destroy', $church) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta igreja? Todos os dados vinculados podem ser afetados.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-400 hover:text-white hover:bg-red-500 rounded-xl transition-all tooltip" title="Excluir">
                                    <x-icon name="trash-can" style="duotone" class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 px-8 bg-white dark:bg-gray-800 rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mb-6">
                        <x-icon name="place-of-worship" style="duotone" class="w-10 h-10 text-gray-400 dark:text-gray-500" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Nenhuma igreja encontrada</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm text-sm mb-8">Nenhuma igreja ou congregação está cadastrada no sistema ou corresponde aos filtros de busca atual.</p>
                    <a href="{{ route('memberpanel.churches.create') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 dark:bg-purple-600 text-white rounded-xl font-bold hover:bg-gray-800 dark:hover:bg-purple-700 transition-all">
                        <x-icon name="plus" style="duotone" class="w-5 h-5 mr-2" />
                        Cadastrar Agora
                    </a>
                </div>
            @endforelse
        </div>

        @if ($churches->hasPages())
            <div class="flex justify-center mt-8">
                {{ $churches->links() }}
            </div>
        @endif
    @endcomponent
@endsection
