@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('content')
<div x-data="configTabs()" x-init="initTabs()" x-cloak class="space-y-6 md:space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                    <x-icon name="cog" class="w-6 h-6" />
                </div>
                Configurações do Sistema
            </h1>
            <p class="text-sm md:text-base text-gray-500 dark:text-gray-400">Gerencie todas as configurações globais da plataforma.</p>
        </div>

        <form action="{{ route('admin.config.initialize') }}" method="POST" onsubmit="return confirm('Isso irá restaurar as configurações padrão apenas se elas não existirem. Deseja continuar?');">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50 transition-colors">
                <x-icon name="rotate-right" class="w-5 h-5" />
                Inicializar Padrões
            </button>
        </form>
    </div>

    <!-- NOTE: Alerts are handled globally via layouts.app (flash-messages partial) -->

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Categorias</h3>
                </div>
                <div class="p-2 space-y-1">
                    @foreach($groups as $group)
                    <button type="button"
                            @click="setActiveTab('{{ $group }}')"
                            :class="activeTab === '{{ $group }}' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800'"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200">
                        @switch($group)
                            @case('general')
                                <x-icon name="cog" class="w-5 h-5" />
                                <span>Geral</span>
                                @break
                            @case('email')
                                <x-icon name="envelope" class="w-5 h-5" />
                                <span>E-mail</span>
                                @break
                            @case('security')
                                <x-icon name="shield-check" class="w-5 h-5" />
                                <span>Segurança</span>
                                @break
                            @case('backup')
                                <x-icon name="cloud-arrow-up" class="w-5 h-5" />
                                <span>Backup</span>
                                @break
                            @case('modules')
                                <x-icon name="cubes" class="w-5 h-5" />
                                <span>Módulos</span>
                                @break
                            @case('recaptcha')
                                <x-icon name="google" class="w-5 h-5" />
                                <span>reCAPTCHA</span>
                                @break
                            @case('integrations')
                                <x-icon name="puzzle-piece" class="w-5 h-5" />
                                <span>Integrações</span>
                                @break
                            @case('platform_modules')
                                <x-icon name="bolt" class="w-5 h-5" />
                                <span>Notificações e módulos</span>
                                @break
                            @case('bible_homepage')
                                <x-icon name="book-bible" class="w-5 h-5" style="duotone" />
                                <span>Bíblia — homepage</span>
                                @break
                            @case('homepage')
                                <x-module-icon module="homepage" class="w-5 h-5" style="duotone" />
                                <span>Homepage (chaves)</span>
                                @break
                            @case('branding')
                                <x-icon name="image" class="w-5 h-5" />
                                <span>Marca</span>
                                @break
                            @default
                                <x-icon name="cog" class="w-5 h-5" />
                                <span>{{ ucfirst($group) }}</span>
                        @endswitch
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-3 space-y-6">
            <form action="{{ route('admin.config.update') }}" method="POST" class="contents">
                @csrf
                @method('PUT')
                <!-- Preserva a aba após salvar; fora do cartão para não deixar shell vazio em Marca -->
                <input type="hidden" name="active_tab" :value="activeTab">

            <div
                x-show="activeTab !== 'branding'"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6"
            >
                @foreach($groups as $group)
                @continue($group === 'branding')
                <div
                    x-show="activeTab === '{{ $group }}'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-0"
                >
                    <div class="mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            @switch($group)
                                @case('general') Geral @break
                                @case('email') E-mail @break
                                @case('security') Segurança @break
                                @case('backup') Backup @break
                                @case('modules') Módulos @break
                                @case('recaptcha') reCAPTCHA @break
                                @case('integrations') Integrações @break
                                @case('platform_modules') Notificações e módulos @break
                                @case('bible_homepage') Bíblia na homepage @break
                                @case('homepage') Homepage (rodapé e chaves gerais) @break
                                @default {{ ucfirst(str_replace('_', ' ', $group)) }}
                            @endswitch
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            @switch($group)
                                @case('platform_modules') Notificações (e-mail, polling, Evolution API), quotas do Financeiro e alertas do módulo Igrejas — valores gravados no .env e aplicados em todo o projecto. @break
                                @case('bible_homepage') Versículo do dia, posição, textos e link «Bíblia» no menu público (espelho do painel Homepage). @break
                                @case('homepage') Chaves gerais da homepage (ex.: créditos no rodapé). @break
                                @default
                                    Configure as opções de {{ str_replace('_', ' ', $group) }}.
                            @endswitch
                        </p>
                    </div>

                    @if(isset($configs[$group]))
                        <div class="space-y-6">
                            @foreach($configs[$group] as $config)
                                <div class="group">
                                    <label for="config_{{ $config->key }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $config->description ?? $config->key }}
                                        @if($config->key === 'google_maps.api_key')
                                            <span class="ml-2 text-xs text-amber-500 bg-amber-50 dark:bg-amber-900/30 px-2 py-0.5 rounded border border-amber-200 dark:border-amber-800">Requerido</span>
                                        @endif
                                    </label>

                                    @if($config->type === 'boolean')
                                        <div class="flex items-center">
                                            <input type="hidden" name="configs[{{ $config->key }}]" value="0">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="configs[{{ $config->key }}]" value="1" class="sr-only peer" {{ $config->value == '1' ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300" x-text="{{ $config->value == '1' ? 'true' : 'false' }} ? 'Ativado' : 'Desativado'"></span>
                                            </label>
                                        </div>
                                    @elseif($config->type === 'password')
                                        <div class="relative" x-data="{ show: false }">
                                            <input :type="show ? 'text' : 'password'"
                                                   id="config_{{ $config->key }}"
                                                   name="configs[{{ $config->key }}]"
                                                   value="{{ $config->value }}"
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 pr-10 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500 transition-shadow hover:shadow-sm">
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 cursor-pointer">
                                                <x-icon x-show="!show" name="eye" class="w-5 h-5" />
                                                <x-icon x-show="show" name="eye-slash" class="w-5 h-5" style="display: none;" />
                                            </button>
                                        </div>
                                        @if($config->key === 'google_maps.api_key')
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Obtenha sua chave no <a href="https://console.cloud.google.com/" target="_blank" class="text-indigo-600 hover:underline dark:text-indigo-400">Google Cloud Console</a>. Habilite "Maps JavaScript API".</p>
                                        @endif
                                    @elseif($config->type === 'text')
                                        <textarea id="config_{{ $config->key }}"
                                                  name="configs[{{ $config->key }}]"
                                                  rows="3"
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500 transition-shadow hover:shadow-sm">{{ $config->value }}</textarea>
                                    @elseif($config->type === 'integer')
                                        <input type="number"
                                               id="config_{{ $config->key }}"
                                               name="configs[{{ $config->key }}]"
                                               value="{{ $config->value }}"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500 transition-shadow hover:shadow-sm">
                                    @else
                                        <input type="text"
                                               id="config_{{ $config->key }}"
                                               name="configs[{{ $config->key }}]"
                                               value="{{ $config->value }}"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500 transition-shadow hover:shadow-sm">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                                <x-icon name="information-circle" class="w-8 h-8 text-gray-400" />
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Nenhuma configuração aqui</h3>
                            <p class="text-gray-500 dark:text-gray-400 mt-1 max-w-sm">Não há configurações disponíveis para este grupo no momento.</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div x-show="activeTab !== 'branding'" class="flex items-center justify-end gap-4 bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm sticky bottom-4 z-10">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition-colors shadow-sm hover:shadow-md">
                    <x-icon name="check" class="w-5 h-5" />
                    Salvar Configurações
                </button>
            </div>
            </form>

            <div
                x-show="activeTab === 'branding'"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6"
            >
                <div class="mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">Marca e logos</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Slogan, envio de imagens e restauro dos ficheiros oficiais.</p>
                </div>
                @include('admin::config.partials.branding', ['configs' => $configs])
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function configTabs() {
        return {
            activeTab: localStorage.getItem('admin_config_tab') || 'general',
            setActiveTab(tab) {
                this.activeTab = tab;
                localStorage.setItem('admin_config_tab', tab);
            },
            initTabs() {
                // If url has hash, use it
                if (window.location.hash) {
                    const hash = window.location.hash.substring(1);
                    if (['general', 'branding', 'email', 'security', 'backup', 'modules', 'recaptcha', 'integrations', 'platform_modules', 'bible_homepage', 'homepage'].includes(hash)) {
                        this.activeTab = hash;
                    }
                }
            }
        }
    }
</script>
@endpush
@endsection
