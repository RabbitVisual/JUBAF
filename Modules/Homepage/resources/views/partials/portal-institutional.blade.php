@php
    $portalChurchStats = $portalChurchStats ?? null;
    $portalChurchByState = $portalChurchByState ?? [];
    $portalUpcomingEvents = isset($portalUpcomingEvents) ? $portalUpcomingEvents : collect();
    $showIgrejas = module_enabled('Igrejas') && $portalChurchStats !== null && (bool) \App\Models\SystemConfig::get('homepage_portal_igrejas_enabled', true);
    $showEventos = module_enabled('Calendario') && isset($portalUpcomingEvents) && $portalUpcomingEvents->isNotEmpty() && (bool) \App\Models\SystemConfig::get('homepage_portal_eventos_enabled', true);
    $maxState = 1;
    foreach ($portalChurchByState ?? [] as $row) {
        $maxState = max($maxState, (int) ($row['count'] ?? 0));
    }
@endphp
@if ($showIgrejas || $showEventos || \Illuminate\Support\Facades\Route::has('login'))
    <section id="rede-institutional" class="relative py-16 lg:py-20 bg-slate-900 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-30 pointer-events-none"
            style="background-image: radial-gradient(circle at 20% 20%, rgba(99,102,241,0.35), transparent 40%), radial-gradient(circle at 80% 60%, rgba(14,165,233,0.25), transparent 45%);">
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-300 mb-2">Portal JUBAF</p>
                    <h2 class="text-3xl md:text-4xl font-bold font-poppins">Rede e agenda</h2>
                    <p class="mt-2 text-slate-300 max-w-2xl text-sm md:text-base">Dados agregados das igrejas associadas e próximos eventos públicos — atualizados periodicamente.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if (\Illuminate\Support\Facades\Route::has('login'))
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-white text-slate-900 px-5 py-3 text-sm font-semibold shadow-lg hover:bg-slate-100 transition">
                            <x-icon name="right-to-bracket" class="w-4 h-4" style="duotone" />
                            Área do jovem / líder
                        </a>
                    @endif
                    @if (\Illuminate\Support\Facades\Route::has('eventos.index'))
                        <a href="{{ route('eventos.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/5 px-5 py-3 text-sm font-semibold text-white hover:bg-white/10 transition">
                            <x-icon name="calendar-days" class="w-4 h-4" style="duotone" />
                            Todos os eventos
                        </a>
                    @endif
                    @if (module_enabled('Blog') && \Illuminate\Support\Facades\Route::has('blog.index'))
                        <a href="{{ route('blog.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/5 px-5 py-3 text-sm font-semibold text-white hover:bg-white/10 transition">
                            <x-icon name="newspaper" class="w-4 h-4" style="duotone" />
                            Blog institucional
                        </a>
                    @endif
                </div>
            </div>

            @if ($showIgrejas || $showEventos || (module_enabled('Calendario') && \Illuminate\Support\Facades\Route::has('eventos.index')))
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-10">
                @if ($showIgrejas)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 md:p-8 backdrop-blur-sm">
                        <div class="flex items-start justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <x-icon name="building-columns" class="w-5 h-5 text-indigo-300" style="duotone" />
                                    Igrejas associadas
                                </h3>
                                <p class="text-xs text-slate-400 mt-1">Somente totais agregados (LGPD).</p>
                            </div>
                            <div class="text-right">
                                <p class="text-4xl md:text-5xl font-black text-white tabular-nums">{{ $portalChurchStats['churches'] }}</p>
                                <p class="text-xs text-slate-400 uppercase tracking-wide">Igrejas (sedes)</p>
                            </div>
                        </div>
                        <dl class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                            <div class="rounded-xl bg-slate-800/80 p-3 border border-white/5">
                                <dt class="text-slate-400 text-xs">Ativas (total)</dt>
                                <dd class="font-semibold text-white tabular-nums">{{ $portalChurchStats['total_active'] }}</dd>
                            </div>
                            <div class="rounded-xl bg-slate-800/80 p-3 border border-white/5">
                                <dt class="text-slate-400 text-xs">Congregações</dt>
                                <dd class="font-semibold text-white tabular-nums">{{ $portalChurchStats['congregations'] }}</dd>
                            </div>
                            <div class="rounded-xl bg-slate-800/80 p-3 border border-white/5">
                                <dt class="text-slate-400 text-xs">CRM ativas</dt>
                                <dd class="font-semibold text-white tabular-nums">{{ $portalChurchStats['crm_ativas'] }}</dd>
                            </div>
                        </dl>
                        @if (!empty($portalChurchByState))
                            <div class="mt-6 space-y-2">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Presença por UF</p>
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                                    @foreach ($portalChurchByState as $row)
                                        @php $pct = $maxState > 0 ? round(100 * ($row['count'] / $maxState)) : 0; @endphp
                                        <div>
                                            <div class="flex justify-between text-xs text-slate-300 mb-0.5">
                                                <span class="font-mono">{{ $row['state'] }}</span>
                                                <span class="tabular-nums">{{ $row['count'] }}</span>
                                            </div>
                                            <div class="h-1.5 rounded-full bg-slate-800 overflow-hidden">
                                                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-sky-400"
                                                    style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($showEventos)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 md:p-8 backdrop-blur-sm">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2 mb-4">
                            <x-icon name="calendar-days" class="w-5 h-5 text-sky-300" style="duotone" />
                            Próximos eventos públicos
                        </h3>
                        <ul class="space-y-4">
                            @foreach ($portalUpcomingEvents as $event)
                                <li class="flex gap-4 items-start border-b border-white/5 pb-4 last:border-0 last:pb-0">
                                    <div class="shrink-0 text-center min-w-[3.25rem]">
                                        <p class="text-2xl font-bold text-white leading-none">{{ $event->start_date?->format('d') }}</p>
                                        <p class="text-[10px] uppercase text-slate-400">{{ $event->start_date?->translatedFormat('M') }}</p>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        @if ($event->is_featured)
                                            <span class="inline-block text-[10px] font-bold uppercase tracking-wide text-amber-300 mb-1">Destaque</span>
                                        @endif
                                        <a href="{{ route('eventos.show', $event->slug) }}"
                                            class="font-semibold text-white hover:text-sky-200 transition line-clamp-2">
                                            {{ $event->title }}
                                        </a>
                                        @if ($event->location)
                                            <p class="text-xs text-slate-400 mt-1 line-clamp-1">{{ $event->location }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @elseif (module_enabled('Calendario') && \Illuminate\Support\Facades\Route::has('eventos.index'))
                    <div class="rounded-2xl border border-dashed border-white/20 bg-white/5 p-6 md:p-8 flex flex-col justify-center items-start text-slate-300">
                        <p class="text-sm">Nenhum evento público agendado no momento.</p>
                        <a href="{{ route('eventos.index') }}" class="mt-4 text-sm font-semibold text-sky-300 hover:text-sky-200">Ver calendário</a>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </section>
@endif
