@extends('memberpanel::components.layouts.master')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@section('page-title', 'Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-200 pb-12">
        <div class="max-w-7xl mx-auto space-y-8 px-6 pt-8">

            <!-- Dashboard Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Visão Geral</h1>
                    <p class="text-gray-500 dark:text-slate-400 mt-1 max-w-md">Bem-vindo ao seu painel pessoal.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-sm flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider">Sistema Online</span>
                    </div>
                </div>
            </div>

            <!-- Hero Section: Welcome & Quick Stats -->
            <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl shadow-xl dark:shadow-2xl border border-gray-100 dark:border-slate-800 transition-colors duration-200" data-tour="dashboard-hero">
                <!-- Decorative Mesh Gradient Background -->
                <div class="absolute inset-0 opacity-20 dark:opacity-40 pointer-events-none">
                    <div class="absolute -top-24 -left-20 w-96 h-96 bg-blue-400 dark:bg-blue-600 rounded-full blur-[100px]"></div>
                    <div class="absolute top-1/2 -right-20 w-80 h-80 bg-purple-400 dark:bg-purple-600 rounded-full blur-[100px]"></div>
                    <div class="absolute bottom-0 left-1/2 w-64 h-64 bg-indigo-300 dark:bg-indigo-500 rounded-full blur-[80px]"></div>
                </div>

                <div class="relative px-8 py-10 flex flex-col md:flex-row items-center gap-10 z-10">
                    <!-- User Avatar -->
                    <div class="relative group shrink-0">
                        <div class="w-28 h-28 rounded-full p-[3px] bg-linear-to-br from-blue-500 via-purple-500 to-indigo-500 shadow-xl shadow-blue-500/20">
                            <div class="w-full h-full rounded-full overflow-hidden border-4 border-white dark:border-slate-900 bg-gray-100 dark:bg-slate-800 relative z-10">
                                @if ($user->photo)
                                    <img src="{{ Storage::url($user->photo) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl font-black text-gray-300 dark:text-slate-600 bg-gray-50 dark:bg-slate-900">
                                        {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- Status Indicator -->
                        <div class="absolute bottom-1 right-1 w-8 h-8 bg-white dark:bg-slate-900 rounded-full flex items-center justify-center z-20 shadow-md">
                            <div class="w-5 h-5 rounded-full {{ $user->is_active ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]' : 'bg-red-500' }} border-2 border-white dark:border-slate-900"></div>
                        </div>
                    </div>

                    <!-- Welcome Text -->
                    <div class="flex-1 text-center md:text-left space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 mb-2">
                            <x-icon name="sparkles" class="w-3 h-3 text-blue-600 dark:text-blue-400" />
                            <span class="text-[10px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Painel do Membro</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">
                            Olá, {{ $user->first_name ?? explode(' ', $user->name)[0] }}!
                        </h1>
                        <p class="text-gray-500 dark:text-slate-300 font-medium max-w-xl text-lg leading-relaxed">
                            É uma alegria ter você aqui. Acompanhe sua jornada e novidades da nossa comunidade.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Grid Layout -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

                <!-- Left Column: Stats & Lists (2/3 width) -->
                <div class="xl:col-span-2 space-y-8">

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" data-tour="dashboard-stats">

                        <!-- Profile Completion -->
                        <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-6 border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300" data-tour="dashboard-profile-completion">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-1">Perfil</p>
                                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $stats['profile_completion'] }}%</h3>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                                    <x-icon name="circle-user" class="w-6 h-6" />
                                </div>
                            </div>
                            <div class="relative h-2 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden mb-3">
                                <div class="absolute h-full bg-linear-to-r from-emerald-500 to-green-500 rounded-full transition-all duration-1000" style="width: {{ $stats['profile_completion'] }}%"></div>
                            </div>
                             <p class="text-xs font-bold {{ $stats['profile_completion'] < 100 ? 'text-orange-500' : 'text-emerald-500' }}">
                                {{ $stats['profile_completion'] < 100 ? 'Complete seu cadastro agora!' : 'Perfil perfeitamente preenchido!' }}
                            </p>
                        </div>

                        <!-- Igreja local JUBAF -->
                        <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-6 border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <div class="min-w-0 pr-2">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-1">Igreja local</p>
                                    <h3 class="text-lg sm:text-xl font-black text-gray-900 dark:text-white tracking-tight truncate" title="{{ $stats['church_label'] ?? '' }}">{{ $stats['church_label'] ?? 'Não informada' }}</h3>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform duration-300 shrink-0">
                                    <x-icon name="place-of-worship" class="w-6 h-6" />
                                </div>
                            </div>
                            <p class="text-xs font-bold text-gray-500 dark:text-slate-400 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Vínculo ASBAF no cadastro JUBAF
                            </p>
                        </div>

                    </div>

                </div>

                <!-- Right Column: Quick Actions & Sidebar Widgets (1/3 width) -->
                <div class="space-y-8">


                    <!-- Quick Actions Grid -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden" data-tour="dashboard-quick-actions">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex items-center gap-3">
                            <x-icon name="bolt" class="w-4 h-4 text-gray-400 dark:text-slate-500" />
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-500 dark:text-slate-400">Acesso Rápido</h3>
                        </div>
                        <div class="p-4 grid grid-cols-2 gap-3">
                            <!-- Profile -->
                            <a href="{{ route('memberpanel.profile.show') }}" class="flex flex-col items-center justify-center gap-3 p-5 rounded-2xl bg-blue-50 dark:bg-blue-900/10 hover:bg-blue-100 dark:hover:bg-blue-900/20 border border-blue-100 dark:border-blue-900/20 transition-all group text-center">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-blue-900/30 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <x-icon name="user" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                                </div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white">Meu Perfil</span>
                            </a>
                            <!-- Bible -->
                            <a href="{{ route('memberpanel.bible.index') }}" class="flex flex-col items-center justify-center gap-3 p-5 rounded-2xl bg-purple-50 dark:bg-purple-900/10 hover:bg-purple-100 dark:hover:bg-purple-900/20 border border-purple-100 dark:border-purple-900/20 transition-all group text-center">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-purple-900/30 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <x-icon name="book-bible" class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white">Bíblia</span>
                            </a>
                            <!-- Events -->
                            <a href="{{ route('memberpanel.events.index') }}" class="flex flex-col items-center justify-center gap-3 p-5 rounded-2xl bg-pink-50 dark:bg-pink-900/10 hover:bg-pink-100 dark:hover:bg-pink-900/20 border border-pink-100 dark:border-pink-900/20 transition-all group text-center">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-pink-900/30 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <x-icon name="calendar-days" class="w-5 h-5 text-pink-600 dark:text-pink-400" />
                                </div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white">Eventos</span>
                            </a>
                            <!-- Donations -->
                            <a href="{{ route('memberpanel.donations.create') }}" class="flex flex-col items-center justify-center gap-3 p-5 rounded-2xl bg-emerald-50 dark:bg-emerald-900/10 hover:bg-emerald-100 dark:hover:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-900/20 transition-all group text-center">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-emerald-900/30 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <x-icon name="hand-holding-heart" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                                </div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white">Doar</span>
                            </a>
                        </div>
                    </div>

                    <!-- Next Steps / Tips -->
                    <div class="bg-linear-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-xl shadow-indigo-500/20 p-8 text-white relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none group-hover:scale-150 transition-transform duration-700"></div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm shadow-inner">
                                    <x-icon name="lightbulb" class="w-5 h-5 text-white" />
                                </div>
                                <h3 class="font-black text-lg tracking-tight">Dica do Dia</h3>
                            </div>

                            @if($stats['profile_completion'] < 100)
                                <p class="text-indigo-100 text-sm font-medium leading-relaxed mb-6 opacity-90">
                                    Complete seu perfil para desbloquear novas conquistas e ser reconhecido pela liderança.
                                </p>
                                <a href="{{ route('memberpanel.profile.edit') }}" class="inline-flex items-center justify-center w-full py-3.5 bg-white text-indigo-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-50 transition-colors shadow-lg active:scale-95">
                                    Completar Perfil Agora
                                </a>
                            @else
                                <p class="text-indigo-100 text-sm font-medium leading-relaxed mb-6 opacity-90">
                                    Seus dados estão em dia! Continue participando dos eventos e mantenha suas informações sempre atualizadas.
                                </p>
                                <button type="button" class="inline-flex items-center justify-center w-full py-3.5 bg-white/10 text-white border border-white/20 rounded-xl font-black text-xs uppercase tracking-widest cursor-default">
                                    Perfil Atualizado <x-icon name="check" class="w-3 h-3 ml-2" />
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Info Widget -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 overflow-hidden">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-1 h-4 bg-gray-900 dark:bg-white rounded-full"></div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Minha Conta</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-50 dark:border-slate-800">
                                <span class="text-sm font-medium text-gray-500 dark:text-slate-400">Cadastro no sistema</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white font-mono">{{ $user->created_at?->format('d/m/Y') ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-50 dark:border-slate-800">
                                <span class="text-sm font-medium text-gray-500 dark:text-slate-400">Igreja local</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white text-right max-w-[60%] truncate" title="{{ $user->church?->name ?? '' }}">{{ $user->church?->name ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500 dark:text-slate-400">Função</span>
                                <span class="px-2.5 py-1 bg-gray-100 dark:bg-slate-800 rounded-lg text-xs font-black uppercase tracking-wider text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-700">
                                    {{ $user->role ? $user->role->name : 'Membro' }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

