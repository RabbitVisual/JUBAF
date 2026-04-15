@extends('layouts.app')

@section('title', 'Configurações do Chat')

@section('content')
    <div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
        <!-- Page Header -->
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
            <div>
                <h1
                    class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                    <div
                        class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                        <x-icon module="chat" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                    </div>
                    <span>Configurações</span>
                </h1>
                <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{ route('admin.dashboard') }}"
                        class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors italic">Admin</a>
                    <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                    <a href="{{ route('admin.chat.index') }}"
                        class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors italic">Chat</a>
                    <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                    <span
                        class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Ajustes</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.chat.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-slate-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors uppercase tracking-widest text-[10px]">
                    <x-icon name="arrow-left" class="w-5 h-5" style="duotone" />
                    Voltar
                </a>
            </div>
        </div>

        <form action="{{ route('admin.chat.config.update') }}" method="POST"
            class="grid grid-cols-1 lg:grid-cols-12 gap-8 font-sans">
            @csrf
            @method('PUT')

            <div class="lg:col-span-8 space-y-8">
                <!-- Configurações Gerais -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
                        <x-icon name="sliders" class="w-4 h-4 text-blue-500" style="duotone" />
                        <h3 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">
                            Ajustes Gerais</h3>
                    </div>
                    <div class="p-6 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div
                                class="flex items-center justify-between p-5 bg-gray-50 dark:bg-slate-900/50 rounded-2xl border border-transparent hover:border-blue-200 dark:hover:border-blue-800 transition-all group">
                                <div>
                                    <label
                                        class="block text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Chat
                                        Habilitado</label>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Status
                                        global</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="chat_enabled" value="1" class="sr-only peer"
                                        {{ ($configs['chat_enabled']->value ?? 'true') === 'true' ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600 shadow-inner">
                                    </div>
                                </label>
                            </div>

                            <div
                                class="flex items-center justify-between p-5 bg-gray-50 dark:bg-slate-900/50 rounded-2xl border border-transparent hover:border-blue-200 dark:hover:border-blue-800 transition-all group">
                                <div>
                                    <label
                                        class="block text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Widget
                                        Público</label>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Exibir na
                                        home</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="public_chat_enabled" value="1" class="sr-only peer"
                                        {{ ($configs['public_chat_enabled']->value ?? 'true') === 'true' ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600 shadow-inner">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div
                            class="p-5 bg-blue-50/80 dark:bg-blue-950/30 rounded-2xl border border-blue-100 dark:border-blue-900/50">
                            <label
                                class="block text-[10px] font-black text-blue-800 dark:text-blue-200 uppercase tracking-widest mb-2 ml-1">Disponibilidade
                                do widget público</label>
                            <select name="public_availability_mode"
                                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-blue-200 dark:border-blue-800 rounded-xl text-sm font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500">
                                @php $mode = $configs['public_availability_mode']->value ?? 'either'; @endphp
                                <option value="office_hours" {{ $mode === 'office_hours' ? 'selected' : '' }}>Somente
                                    horário configurado abaixo</option>
                                <option value="agents_online" {{ $mode === 'agents_online' ? 'selected' : '' }}>Somente com
                                    diretoria online no painel</option>
                                <option value="either" {{ $mode === 'either' ? 'selected' : '' }}>Horário OU diretoria
                                    online (recomendado)</option>
                            </select>
                            <p
                                class="mt-2 text-[10px] font-bold text-blue-700/80 dark:text-blue-300/80 uppercase tracking-wide leading-relaxed">
                                Com diretoria online, o painel envia sinal a cada minuto; fora do horário o visitante ainda
                                pode falar se alguém da equipe estiver no painel da diretoria.
                            </p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1 italic">Mensagem
                                    de Boas-vindas</label>
                                <textarea name="welcome_message" rows="3"
                                    class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-700 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600 transition-all dark:text-white resize-none">{{ $configs['welcome_message']->value ?? '' }}</textarea>
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1 italic">Mensagem
                                    Offline</label>
                                <textarea name="offline_message" rows="3"
                                    class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-700 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600 transition-all dark:text-white resize-none">{{ $configs['offline_message']->value ?? '' }}</textarea>
                                <p class="mt-2 text-[10px] font-bold text-gray-400 uppercase px-1">Exibida quando o widget
                                    está indisponível (conforme a regra de disponibilidade acima).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horários de Funcionamento -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
                        <x-icon name="calendar-clock" class="w-4 h-4 text-blue-500" style="duotone" />
                        <h3 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">
                            Horários de Funcionamento</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4">
                            @php
                                $days = [
                                    'monday' => 'Segunda-feira',
                                    'tuesday' => 'Terça-feira',
                                    'wednesday' => 'Quarta-feira',
                                    'thursday' => 'Quinta-feira',
                                    'friday' => 'Sexta-feira',
                                    'saturday' => 'Sábado',
                                    'sunday' => 'Domingo',
                                ];
                            @endphp
                            @foreach ($days as $key => $label)
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 bg-gray-50 dark:bg-slate-900/50 rounded-2xl border border-transparent hover:border-blue-200 dark:hover:border-blue-800 transition-all group">
                                    <div class="flex items-center gap-3 sm:w-48">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="opening_hours[{{ $key }}][enabled]"
                                                value="1" class="sr-only peer"
                                                {{ $openingHours[$key]['enabled'] ?? false ? 'checked' : '' }}>
                                            <div
                                                class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600 shadow-inner">
                                            </div>
                                        </label>
                                        <span
                                            class="text-xs font-black text-gray-700 dark:text-slate-300 uppercase tracking-wide">{{ $label }}</span>
                                    </div>
                                    <div class="flex-1 grid grid-cols-2 gap-4">
                                        <div class="relative">
                                            <input type="time" name="opening_hours[{{ $key }}][start]"
                                                value="{{ $openingHours[$key]['start'] ?? '08:00' }}"
                                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition-all dark:text-white">
                                            <span
                                                class="absolute -top-2 left-3 px-1 bg-gray-50 dark:bg-slate-900 text-[9px] font-black text-gray-400 uppercase">Início</span>
                                        </div>
                                        <div class="relative">
                                            <input type="time" name="opening_hours[{{ $key }}][end]"
                                                value="{{ $openingHours[$key]['end'] ?? '17:00' }}"
                                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition-all dark:text-white">
                                            <span
                                                class="absolute -top-2 left-3 px-1 bg-gray-50 dark:bg-slate-900 text-[9px] font-black text-gray-400 uppercase">Fim</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <!-- Configurações Avançadas -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
                        <x-icon name="rocket" class="w-4 h-4 text-blue-500" style="duotone" />
                        <h3 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">
                            Avançado</h3>
                    </div>
                    <div class="p-6 space-y-8">
                        <div>
                            <label
                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Inatividade
                                (minutos)</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-icon name="clock-rotate-left"
                                        class="w-4 h-4 text-gray-300 group-focus-within:text-blue-500 transition-colors" />
                                </div>
                                <input type="number" name="auto_close_timeout"
                                    value="{{ $configs['auto_close_timeout']->value ?? '30' }}" min="1"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-700 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition-all dark:text-white">
                            </div>
                            <p class="mt-2 text-[9px] font-bold text-gray-400 uppercase px-1 leading-relaxed">Tempo para
                                encerrar sessões sem interação.</p>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Limite
                                por Atendente</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-icon name="users-gear"
                                        class="w-4 h-4 text-gray-300 group-focus-within:text-blue-500 transition-colors" />
                                </div>
                                <input type="number" name="max_concurrent_sessions"
                                    value="{{ $configs['max_concurrent_sessions']->value ?? '10' }}" min="1"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-700 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-600 transition-all dark:text-white">
                            </div>
                            <p class="mt-2 text-[9px] font-bold text-gray-400 uppercase px-1 leading-relaxed">Máximo de
                                atendimentos simultâneos.</p>
                        </div>

                        <div
                            class="flex items-center justify-between p-5 bg-gray-50 dark:bg-slate-900/50 rounded-2xl border border-transparent hover:border-blue-200 dark:hover:border-blue-800 transition-all">
                            <div>
                                <label
                                    class="block text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Efeitos
                                    Sonoros</label>
                                <p class="text-[9px] text-gray-500 font-bold mt-1 uppercase">Avisos ativos</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notification_sound" value="1" class="sr-only peer"
                                    {{ ($configs['notification_sound']->value ?? 'true') === 'true' ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600 shadow-inner">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-slate-900 to-slate-800 dark:from-blue-600 dark:to-indigo-700 p-8 rounded-3xl shadow-xl text-center">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <x-icon name="floppy-disk" class="w-7 h-7 text-white" style="duotone" />
                    </div>
                    <h4 class="text-white font-black text-lg mb-2 uppercase tracking-tight">Salvar Alterações?</h4>
                    <p class="text-blue-100/60 font-medium text-xs mb-8 leading-relaxed px-4">As definições serão aplicadas
                        imediatamente.</p>
                    <button type="submit"
                        class="w-full py-3.5 bg-white text-slate-900 dark:text-blue-600 font-black rounded-xl hover:scale-105 active:scale-95 transition-all shadow-lg text-sm uppercase tracking-wide">
                        Confirmar
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
