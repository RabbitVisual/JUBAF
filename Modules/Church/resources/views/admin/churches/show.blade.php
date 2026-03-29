@extends('admin::components.layouts.master')

@section('title', $church->name)

@section('content')
    <div class="space-y-8">
        
        <!-- Navbar e Breadcrumb de Ação Rápida -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <a href="{{ route('admin.churches.index') }}"
                class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" />
                Voltar à Lista
            </a>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.churches.edit', $church) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 font-bold rounded-xl hover:bg-indigo-100 transition-colors shadow-sm">
                    <x-icon name="pencil" style="duotone" class="w-4 h-4 mr-2" />
                    Editar
                </a>
                
                <form action="{{ route('admin.churches.destroy', $church) }}" method="POST" onsubmit="return confirm('Deseja excluir a igreja {{ $church->name }} permanentemente?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 font-bold rounded-xl hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors shadow-sm">
                        <x-icon name="trash-can" style="duotone" class="w-4 h-4 mr-2" />
                        Excluir
                    </button>
                </form>
            </div>
        </div>

        <!-- Cover Header -->
        <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row">
            <!-- Coluna de Imagem / Logo (Esquerda) -->
            <div class="md:w-1/3 bg-gradient-to-br from-indigo-500/10 to-blue-600/10 flex items-center justify-center p-8 border-b md:border-b-0 md:border-r border-gray-100 dark:border-gray-700 relative min-h-[250px]">
                @if ($church->logo_path)
                    <img src="{{ Storage::url($church->logo_path) }}" alt="{{ $church->name }}" class="w-full h-full object-contain max-h-[200px] drop-shadow-md">
                @else
                    <x-icon name="place-of-worship" style="duotone" class="w-24 h-24 text-indigo-500/30" />
                @endif
                
                @if ($church->sector)
                    <div class="absolute top-4 left-4 px-4 py-1.5 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-gray-800 dark:text-gray-200 rounded-full text-xs font-black uppercase tracking-widest shadow-sm border border-gray-200 dark:border-gray-700">
                        {{ $church->sector }}
                    </div>
                @endif
            </div>

            <!-- Dados Principais (Direita) -->
            <div class="md:w-2/3 p-8 md:p-10 flex flex-col justify-center bg-white dark:bg-gray-800 relative space-y-4">
                <div class="absolute top-6 right-6">
                    <span class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest {{ $church->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $church->is_active ? 'Ativa no Sistema' : 'Inativa' }}
                    </span>
                </div>

                <div class="space-y-1">
                    <h1 class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-tight uppercase">{{ $church->name }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Cadastrado em: {{ $church->created_at->format('d/m/Y') }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black tracking-widest uppercase text-gray-400">Jovens / UNIJOVEM</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $church->unijovem_name ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="space-y-1">
                        <p class="text-[10px] font-black tracking-widest uppercase text-gray-400">Liderança Principal</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $church->leader_name ?? 'Não informado' }}</p>
                        @if ($church->leader_phone)
                            <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $church->leader_phone) }}" target="_blank" class="inline-flex items-center text-sm font-bold text-green-500 hover:text-green-600 transition-colors">
                                <i class="fa-brands fa-whatsapp mr-1"></i> {{ $church->leader_phone }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Coluna de Endereço -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-50 dark:bg-emerald-900/10 rounded-bl-full -mr-8 -mt-8 pointer-events-none"></div>
                <div class="flex items-center gap-3 mb-6 relative z-10">
                    <div class="p-2.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl">
                        <x-icon name="map-location-dot" style="duotone" class="w-6 h-6" />
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Localização</h2>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                        <x-icon name="city" style="duotone" class="w-5 h-5 text-gray-400 mt-0.5" />
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cidade</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $church->city ?? '---' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                        <x-icon name="map" style="duotone" class="w-5 h-5 text-gray-400 mt-0.5" />
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Bairro</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $church->neighborhood ?? '---' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                        <x-icon name="location-dot" style="duotone" class="w-5 h-5 text-gray-400 mt-0.5" />
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Endereço</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $church->address ?? '---' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Integração com o sistema (membros, eventos, bíblia) -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2.5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                        <x-icon name="diagram-project" style="duotone" class="w-6 h-6" />
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">No ecossistema JUBAF</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('admin.users.index', ['church_id' => $church->id]) }}"
                        class="flex flex-col p-5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-900/40 hover:border-blue-300 dark:hover:border-blue-700 transition-colors group">
                        <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 font-bold text-sm mb-2">
                            <x-icon name="users" style="duotone" class="w-5 h-5" />
                            Membros vinculados
                        </div>
                        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $church->users_count }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 group-hover:text-blue-600 dark:group-hover:text-blue-400">Abrir lista filtrada no Admin →</p>
                    </a>

                    <a href="{{ route('admin.events.events.index') }}"
                        class="flex flex-col p-5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-900/40 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors group">
                        <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-bold text-sm mb-2">
                            <x-icon name="calendar-days" style="duotone" class="w-5 h-5" />
                            Inscrições (participantes)
                        </div>
                        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $church->participants_count }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Gerir eventos e inscrições →</p>
                    </a>

                    <a href="{{ route('admin.bible.reports.church-plan') }}"
                        class="sm:col-span-2 flex items-center gap-4 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-amber-50/50 dark:bg-amber-900/10 hover:border-amber-300 dark:hover:border-amber-700 transition-colors">
                        <x-icon name="book-bible" style="duotone" class="w-10 h-10 text-amber-600 dark:text-amber-400 shrink-0" />
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">Planos de leitura da igreja</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Relatório consolidado (Bíblia) — progresso dos planos institucionais e filiados.</p>
                        </div>
                        <x-icon name="arrow-right" class="w-5 h-5 text-amber-600 dark:text-amber-400 ml-auto shrink-0" />
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection
