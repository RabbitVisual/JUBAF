@php
    $isFullscreen = ($variant ?? 'classic') === 'fullscreen';
@endphp
<div
    class="checkin-scanner flex flex-col gap-3 sm:gap-6 {{ $isFullscreen ? 'fixed inset-0 z-50 min-h-dvh p-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] overflow-hidden' : 'min-h-[calc(100vh-8rem)]' }}"
    x-data="checkinScanner()"
    x-init="init()"
>
    <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between shrink-0">
        <div>
            <h1 class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">Scanner de Check-in</h1>
            <p class="text-xs sm:text-base text-gray-600 dark:text-gray-400 mt-0.5">Validação de ingressos em tempo real</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <button type="button" @click="toggleScanner()" :disabled="cameraBusy"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed min-w-[140px]"
                :class="scanning ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500'">
                <template x-if="cameraBusy && !scanning">
                    <span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                </template>
                <x-icon name="camera" style="duotone" class="w-4 h-4 shrink-0" x-show="!scanning && !cameraBusy" />
                <x-icon name="x" style="duotone" class="w-4 h-4 shrink-0" x-show="scanning" />
                <span x-text="scanning ? 'Parar' : (cameraBusy ? 'Aguarde...' : 'Iniciar Scanner')"></span>
            </button>
            @if ($isFullscreen)
                <a href="{{ route('admin.events.checkin.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-600 text-sm font-medium rounded-lg text-gray-100 bg-gray-800 hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <x-icon name="window-maximize" style="duotone" class="w-4 h-4" />
                    <span>Modo clássico</span>
                </a>
            @else
                <a href="{{ route('admin.events.checkin.scanner') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <x-icon name="mobile-screen-button" style="duotone" class="w-4 h-4" />
                    <span class="hidden xs:inline">Ecrã inteiro</span>
                </a>
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <x-icon name="arrow-left" style="duotone" class="w-4 h-4" />
                    <span class="hidden xs:inline">Voltar</span>
                </a>
            @endif
        </div>
    </header>

    <div class="grid grid-cols-2 {{ $isFullscreen ? 'gap-2 shrink-0' : 'md:grid-cols-4 gap-3 sm:gap-4' }}">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-4">
            <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Check-ins</p>
            <p class="mt-1 text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tabular-nums" x-text="stats.checkins"></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-4">
            <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Ingressos</p>
            <p class="mt-1 text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tabular-nums" x-text="stats.total"></p>
        </div>
    </div>

    <div class="{{ $isFullscreen ? 'flex flex-col flex-1 min-h-0 gap-3' : 'grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 flex-1 min-h-0' }}">
        <div class="{{ $isFullscreen ? 'flex flex-col flex-1 min-h-0 gap-3' : 'lg:col-span-2 flex flex-col gap-4 min-h-0' }}">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex-1 flex flex-col min-h-0 {{ $isFullscreen ? 'min-h-[48vh]' : 'min-h-[280px] sm:min-h-[320px]' }}">
                <div
                    class="relative bg-black flex-1 flex items-center justify-center overflow-hidden w-full {{ $isFullscreen ? 'min-h-[44vh] max-h-[72vh]' : 'min-h-[240px] aspect-video max-h-[70vh]' }}">
                    <div id="reader" class="w-full h-full min-h-[200px] max-w-full"></div>

                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900/90 z-10" x-show="!scanning && !feedback.visible" x-transition>
                        <x-icon name="camera" style="duotone" class="w-10 h-10 sm:w-12 sm:h-12 text-gray-500 mb-3 sm:mb-4" />
                        <p class="text-white font-medium text-sm sm:text-base">Câmera em standby</p>
                        <p class="text-gray-400 text-xs sm:text-sm mt-1">Toque em &quot;Iniciar Scanner&quot;</p>
                    </div>

                    <div class="absolute inset-0 pointer-events-none z-20 flex items-center justify-center" x-show="scanning && !feedback.visible" x-transition>
                        <div class="w-56 h-56 sm:w-64 sm:h-64 border-2 border-white/20 rounded-xl relative shadow-2xl">
                            <div class="absolute top-0 left-0 w-6 h-6 sm:w-8 sm:h-8 border-t-4 border-l-4 border-amber-500 -mt-0.5 -ml-0.5 sm:-mt-1 sm:-ml-1 rounded-tl"></div>
                            <div class="absolute top-0 right-0 w-6 h-6 sm:w-8 sm:h-8 border-t-4 border-r-4 border-amber-500 -mt-0.5 -mr-0.5 sm:-mt-1 sm:-mr-1 rounded-tr"></div>
                            <div class="absolute bottom-0 left-0 w-6 h-6 sm:w-8 sm:h-8 border-b-4 border-l-4 border-amber-500 -mb-0.5 -ml-0.5 sm:-mb-1 sm:-ml-1 rounded-bl"></div>
                            <div class="absolute bottom-0 right-0 w-6 h-6 sm:w-8 sm:h-8 border-b-4 border-r-4 border-amber-500 -mb-0.5 -mr-0.5 sm:-mb-1 sm:-mr-1 rounded-br"></div>
                            <div class="absolute top-0 left-0 w-full h-0.5 bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.8)] animate-scan-line"></div>
                        </div>
                    </div>

                    <div class="absolute inset-0 z-30 flex items-center justify-center p-4 sm:p-6 bg-gray-900/95" x-show="feedback.visible" x-transition style="display: none;">
                        <div class="text-center w-full max-w-sm bg-gray-800 rounded-2xl p-6 shadow-xl border border-gray-700">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-white"
                                 :class="{
                                    'bg-green-500': feedback.type === 'success',
                                    'bg-red-500': feedback.type === 'error',
                                    'bg-amber-500': feedback.type === 'warning'
                                 }">
                                <x-icon name="circle-check" style="duotone" class="w-8 h-8 sm:w-10 sm:h-10" x-show="feedback.type === 'success'" />
                                <x-icon name="circle-xmark" style="duotone" class="w-8 h-8 sm:w-10 sm:h-10" x-show="feedback.type === 'error'" />
                                <x-icon name="exclamation-circle" style="duotone" class="w-8 h-8 sm:w-10 sm:h-10" x-show="feedback.type === 'warning'" />
                            </div>
                            <h2 class="text-xl sm:text-2xl font-bold text-white mb-2" x-text="feedback.title"></h2>
                            <p class="text-gray-300 text-sm sm:text-base mb-4" x-text="feedback.message"></p>
                            <template x-if="feedback.extra">
                                <div class="bg-white/10 p-3 sm:p-4 rounded-lg mb-4">
                                    <p class="text-xs text-gray-400 uppercase font-semibold">Participante</p>
                                    <p class="text-base sm:text-lg font-bold text-white truncate" x-text="feedback.extra"></p>
                                </div>
                            </template>
                            <button type="button" @click="feedback.visible = false" class="w-full py-2.5 bg-gray-600 hover:bg-gray-500 text-white rounded-lg font-semibold transition-colors">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>

            @unless ($isFullscreen)
                <div class="flex items-start gap-3 p-3 sm:p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800">
                    <x-icon name="information-circle" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" />
                    <p class="text-xs sm:text-sm text-blue-800 dark:text-blue-200">Aponte a câmera para o QR Code do ingresso ou use a entrada manual ao lado.</p>
                </div>
            @endunless
        </div>

        <div class="{{ $isFullscreen ? 'flex flex-col gap-3 shrink-0 max-h-[38vh] overflow-y-auto' : 'flex flex-col gap-4 sm:gap-6 min-h-0' }}">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-3 sm:mb-4">Entrada manual</h3>
                <div class="space-y-3">
                    <input type="text" x-model="manualHash" @keydown.enter.prevent="manualCheckin()" placeholder="Código do ingresso"
                        class="w-full px-3 py-2.5 sm:px-4 sm:py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-mono text-sm uppercase tracking-wider placeholder:normal-case placeholder:tracking-normal">
                    <button type="button" @click="manualCheckin()" class="w-full py-2.5 bg-gray-900 dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white rounded-lg font-semibold transition-colors">
                        Validar código
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 {{ $isFullscreen ? 'flex-1 min-h-0 flex flex-col' : 'flex-1 flex flex-col min-h-0' }}">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Últimos acessos</h3>
                    <span class="text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 px-2 py-0.5 rounded-full">AO VIVO</span>
                </div>
                <div class="space-y-2 overflow-y-auto flex-1 min-h-0 -mr-1 pr-1">
                    <template x-for="(entry, index) in history" :key="index">
                        <div class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 shrink-0">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white shrink-0"
                                 :class="{
                                    'bg-green-500': entry.type === 'success',
                                    'bg-red-500': entry.type === 'error',
                                    'bg-amber-500': entry.type === 'warning'
                                 }">
                                <x-icon name="user" style="duotone" class="w-4 h-4" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold truncate text-gray-900 dark:text-white" x-text="entry.name"></p>
                                <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400" x-text="entry.time + ' · ' + entry.status"></p>
                            </div>
                        </div>
                    </template>
                    <template x-if="history.length === 0">
                        <p class="text-center text-gray-500 dark:text-gray-400 text-sm py-6">Nenhum registro recente</p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
