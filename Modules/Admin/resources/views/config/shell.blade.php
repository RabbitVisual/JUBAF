@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('content')
@php
    /** @var string $section */
    /** @var array $pageDef */
    /** @var array $configsGrouped */
    $pages = \App\Support\Admin\ConfigPageRegistry::pages();
@endphp
<div class="space-y-6 md:space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg text-white">
                    <x-icon name="cog" class="w-6 h-6" />
                </div>
                Configurações do sistema
            </h1>
            <p class="text-sm md:text-base text-gray-500 dark:text-gray-400">{{ $pageDef['label'] ?? 'Plataforma JUBAF' }}</p>
        </div>

        <form action="{{ route('admin.config.initialize') }}" method="POST" onsubmit="return confirm('Isso irá restaurar as configurações padrão apenas se elas não existirem. Deseja continuar?');">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50 transition-colors">
                <x-icon name="rotate-right" class="w-5 h-5" />
                Inicializar padrões
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-1">
            <div class="sticky top-24 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Secções</h3>
                </div>
                <nav class="p-2 space-y-1" aria-label="Configurações">
                    @foreach($pages as $p)
                        <a href="{{ route('admin.config.page', ['section' => $p['id']]) }}"
                           class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ ($p['id'] === $section) ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800' }}">
                            <x-icon name="{{ $p['icon'] }}" class="w-5 h-5 shrink-0" style="{{ in_array($p['icon'], ['book-bible', 'user-shield'], true) ? 'duotone' : 'solid' }}" />
                            <span class="text-left">{{ $p['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <div class="lg:col-span-3 space-y-6">
            @if($pageDef['type'] === 'branding')
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
                    <div class="mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Marca e logos</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Slogan, envio de imagens e restauro dos ficheiros oficiais.</p>
                    </div>
                    @include('admin::config.partials.branding', ['configs' => $configsGrouped])
                </div>
            @else
                <form action="{{ route('admin.config.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="config_section" value="{{ $section }}">

                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
                        @if($pageDef['type'] === 'generic')
                            @include('admin::config.pages.generic', [
                                'groupKey' => $pageDef['group'],
                                'configsGrouped' => $configsGrouped,
                                'pageTitle' => $pageDef['label'],
                                'pageLead' => $groupLead ?? null,
                            ])
                        @elseif($pageDef['type'] === 'custom' && !empty($pageDef['view']))
                            @include($pageDef['view'], [
                                'configsGrouped' => $configsGrouped,
                                'section' => $section,
                                'assignableRoles' => $assignableRoles ?? [],
                            ])
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-4 bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm sticky bottom-4 z-10">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 transition-colors shadow-sm">
                            <x-icon name="check" class="w-5 h-5" />
                            Guardar
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
