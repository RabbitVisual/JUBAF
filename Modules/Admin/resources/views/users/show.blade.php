@extends('admin::components.layouts.master')

@section('title', 'Perfil: ' . $user->name)

@endphp
@section('content')
<div class="space-y-8" x-data="{ tab: 'general' }">
    <!-- Immersive Header -->
    <div class="relative rounded-3xl overflow-hidden bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700">
        <!-- Badges strip -->
        <div class="absolute top-4 left-8 z-10 flex flex-wrap items-center gap-2">
            <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-white text-[10px] font-bold uppercase tracking-widest">Membros</span>
            <span class="px-3 py-1 rounded-full {{ $user->is_active ? 'bg-green-500/30 border-green-400/50 text-green-100' : 'bg-red-500/30 border-red-400/50 text-red-100' }} border text-[10px] font-bold uppercase tracking-widest">{{ $user->is_active ? 'Ativo' : 'Inativo' }}</span>
        </div>
        <!-- Banner Background -->
        <div class="h-48 bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 relative overflow-hidden">
            <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: url('{{ asset('storage/image/pattern.png') }}'); background-size: 100px;"></div>
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Profile Info Overlay -->
        <div class="px-8 pb-8 flex flex-col md:flex-row gap-8 items-end -mt-16 relative">
            <!-- avatar -->
            <div class="relative group">
                <div class="w-40 h-40 rounded-3xl border-8 border-white dark:border-gray-800 shadow-2xl overflow-hidden bg-gray-100 dark:bg-gray-700">
                    @if ($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-linear-to-tr from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600">
                            <x-icon name="user" class="w-20 h-20" />
                        </div>
                    @endif
                </div>
                <div class="absolute -bottom-2 -right-2 w-10 h-10 rounded-xl bg-green-500 border-4 border-white dark:border-gray-800 flex items-center justify-center text-white" title="Status: {{ $user->is_active ? 'Ativo' : 'Inativo' }}">
                    <x-icon name="{{ $user->is_active ? 'check' : 'xmark' }}" class="w-5 h-5" />
                </div>
            </div>

            <!-- Basic Info -->
            <div class="flex-1 space-y-3 pb-2">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ $user->name }}</h1>
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                        {{ $user->role->name }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-6 text-sm">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 font-medium">
                        <x-icon name="envelope" class="w-4 h-4 mr-2" />
                        {{ $user->email }}
                    </div>
                    @if($user->cellphone)
                    <div class="flex items-center text-gray-500 dark:text-gray-400 font-medium">
                        <x-icon name="phone" class="w-4 h-4 mr-2" />
                        {{ $user->cellphone }}
                    </div>
                    @endif
                    <div class="flex items-center text-gray-500 dark:text-gray-400 font-medium">
                        <x-icon name="calendar" class="w-4 h-4 mr-2" />
                        Cadastrado em {{ $user->created_at?->format('d/m/Y') ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex gap-3 mb-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="flex items-center px-6 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600 transition-all border border-gray-200 dark:border-gray-600 shadow-sm active:scale-95">
                    <x-icon name="pencil" class="w-4 h-4 mr-2" />
                    Editar
                </a>
            </div>
        </div>

        <div class="px-8 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/30">
            <div class="flex gap-8">
                <button @click="tab = 'general'" :class="tab === 'general' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-4 border-b-2 font-black text-[10px] uppercase tracking-widest transition-all">Geral</button>
                <button @click="tab = 'financial'" :class="tab === 'financial' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-4 border-b-2 font-black text-[10px] uppercase tracking-widest transition-all">Financeiro</button>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Sidebar: Completion -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Completion Card -->
            @php $completion = $user->getProfileCompletionPercentage(); @endphp
            <div class="bg-linear-to-br from-indigo-900 to-blue-900 rounded-3xl p-8 text-white shadow-xl shadow-indigo-900/20">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-indigo-300 mb-6">Perfil do Membro</h3>
                <div class="text-4xl font-black mb-2">{{ $completion }}%</div>
                <p class="text-sm font-medium text-indigo-200 mb-6">Campos obrigatórios preenchidos corretamente no sistema.</p>
                <div class="h-2 w-full bg-indigo-950/50 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-400 rounded-full" style="width: {{ $completion }}%"></div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 min-h-[600px] overflow-hidden">

                <!-- Tab: General Info -->
                <div x-show="tab === 'general'" x-cloak class="p-8 space-y-10 animate-fade-in">
                    <!-- Section: Personal -->
                    <section class="space-y-6">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                             <x-icon name="user" class="w-4 h-4" />
                             Dados Pessoais
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-blue-500 transition-colors">Nome Completo</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $user->name }}</p>
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-blue-500 transition-colors">CPF</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $user->cpf ?? 'Não registrado' }}</p>
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-blue-500 transition-colors">Data de Nascimento</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $user->date_of_birth?->format('d/m/Y') ?? 'Não registrado' }}</p>
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-blue-500 transition-colors">Estado Civil</label>
                                <p class="text-gray-900 dark:text-white font-bold capitalize">{{ $user->marital_status ?? 'Não registrado' }}</p>
                            </div>
                        </div>
                    </section>

                    <!-- Section: Contact -->
                    <section class="space-y-6 pt-10 border-t border-gray-100 dark:border-gray-700">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                             <x-icon name="at-symbol" class="w-4 h-4" />
                             Contato e Localização
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-blue-500 transition-colors">Endereço Residencial</label>
                                <p class="text-gray-900 dark:text-white font-bold">
                                    {{ $user->address ?? 'Não registrado' }}@if($user->address_number), {{ $user->address_number }}@endif
                                </p>
                                <p class="text-sm text-gray-500 font-medium">
                                    {{ $user->neighborhood }} - {{ $user->city }}/{{ $user->state }}
                                </p>
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-blue-500 transition-colors">Telefone Principal</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $user->cellphone ?? $user->phone ?? 'Não registrado' }}</p>
                            </div>
                        </div>
                    </section>
                </div>


                <!-- Tab: Financial -->
                <div x-show="tab === 'financial'" x-cloak class="p-8 space-y-8 animate-fade-in">
                    @if(auth()->user()->isAdmin() || \Modules\Treasury\App\Models\TreasuryPermission::where('user_id', auth()->id())->exists())
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Últimas Contribuições</h4>
                            <p class="text-xs text-gray-500 mb-4">Mostrando histórico recente de dízimos e ofertas.</p>

                            <div class="space-y-3">
                                @forelse($user->financialEntries as $entry)
                                    <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-all hover:translate-x-1">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/30 text-green-600 flex items-center justify-center">
                                                <x-icon name="cash" class="w-5 h-5" />
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $entry->category === 'tithe' ? 'Dízimo' : ($entry->category === 'offering' ? 'Oferta' : 'Contribuição') }}</div>
                                                <div class="text-xs text-gray-500 font-bold uppercase tracking-widest">{{ $entry->entry_date->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-black text-green-600 dark:text-green-400 tracking-tight">R$ {{ number_format($entry->amount, 2, ',', '.') }}</div>
                                            <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ $entry->payment_method ?? 'Interno' }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-12 text-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                            <x-icon name="document-text" class="w-8 h-8" />
                                        </div>
                                        <p class="text-sm font-bold text-gray-500">Nenhum registro financeiro encontrado para este período.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <div class="py-24 text-center">
                            <x-icon name="lock-closed" class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                            <p class="text-sm font-bold text-gray-500">Você não tem permissão para visualizar dados financeiros.</p>
                        </div>
                    @endif
                </div>


            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

