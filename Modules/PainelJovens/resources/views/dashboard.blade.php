@extends('paineljovens::layouts.jovens')

@section('title', 'Início')

@section('jovens_content')
    @php
        $u = $user ?? auth()->user();
        $firstName = explode(' ', $u->name)[0];
    @endphp

    <x-ui.jovens::page-shell class="space-y-8 md:space-y-10">
        <header class="relative overflow-hidden rounded-[2rem] border border-gray-200/90 dark:border-gray-800 bg-gradient-to-br from-blue-700 via-blue-800 to-gray-900 text-white shadow-xl">
            <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="relative flex flex-col gap-8 px-6 py-10 md:px-10 md:py-12 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-xl">
                    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-blue-200/90">Unijovem · Início</p>
                    <h1 class="text-3xl font-bold leading-tight tracking-tight md:text-4xl">Olá, {{ $firstName }}!</h1>
                    <p class="mt-4 text-sm leading-relaxed text-blue-100/95 md:text-base">
                        Painel Unijovem — perfil, avisos, eventos e carteira num só sítio.
                    </p>
                </div>
                <div class="flex w-full flex-col gap-2 sm:flex-row lg:max-w-sm lg:flex-col">
                    <a href="{{ route('jovens.profile.index') }}"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-900 shadow-lg transition-colors hover:bg-blue-50 lg:flex-none">
                        <x-icon name="user-gear" class="h-4 w-4" />
                        Meu perfil
                    </a>
                    @if (module_enabled('Calendario') && Route::has('jovens.wallet.index') && auth()->user()?->can('calendario.participate'))
                        <a href="{{ route('jovens.wallet.index') }}"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-white/25 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-colors hover:bg-white/20 lg:flex-none">
                            <x-icon name="ticket" class="h-4 w-4" />
                            Carteira
                        </a>
                    @endif
                </div>
            </div>
        </header>

        @if ($registrationSummary->isNotEmpty())
            <section aria-labelledby="dash-reg-heading" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 id="dash-reg-heading" class="text-lg font-semibold text-gray-900 dark:text-white">Estado das inscrições</h2>
                    @if (Route::has('jovens.wallet.index') && auth()->user()?->can('calendario.participate'))
                        <a href="{{ route('jovens.wallet.index') }}"
                            class="text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">Abrir carteira</a>
                    @endif
                </div>
                <ul class="mt-4 space-y-3">
                    @foreach ($registrationSummary as $reg)
                        @php
                            $ev = $reg->event;
                            $gp = $reg->gatewayPayment;
                            $pay = $gp?->statusLabel() ?? ($reg->payment_status === 'not_required' ? 'Sem pagamento online' : ucfirst((string) $reg->payment_status));
                        @endphp
                        <li
                            class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-600 dark:bg-gray-900/50">
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $ev?->title ?? 'Evento' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $pay }}</p>
                            </div>
                            <div class="flex shrink-0 flex-wrap items-center gap-2">
                                @if ($gp && $gp->status === \Modules\Gateway\App\Models\GatewayPayment::STATUS_PENDING && Route::has('gateway.public.checkout'))
                                    <a href="{{ route('gateway.public.checkout', ['uuid' => $gp->uuid]) }}"
                                        class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-amber-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400">Pagar</a>
                                @endif
                                @if (Route::has('jovens.eventos.show') && $ev)
                                    <a href="{{ route('jovens.eventos.show', $ev) }}"
                                        class="text-xs font-bold text-blue-600 hover:underline dark:text-blue-400">Detalhes</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($urgentAvisos->isNotEmpty())
            <section aria-labelledby="dash-urgent-heading">
                <h2 id="dash-urgent-heading" class="sr-only">Avisos urgentes</h2>
                <div class="space-y-3">
                    @foreach ($urgentAvisos as $aviso)
                        <a href="{{ route('jovens.avisos.show', $aviso) }}"
                            class="flex gap-4 rounded-lg border border-rose-200 bg-white p-4 shadow-sm transition hover:border-rose-300 hover:shadow-md dark:border-rose-900/40 dark:bg-gray-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-600 text-white shadow-md">
                                <x-icon name="triangle-exclamation" class="h-5 w-5" style="solid" />
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-bold text-rose-950 dark:text-rose-100">{{ $aviso->titulo }}</span>
                                <span class="mt-1 line-clamp-2 text-xs text-rose-900/80 dark:text-rose-200/90">{{ \Illuminate\Support\Str::limit(strip_tags((string) $aviso->descricao), 140) }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <x-ui.jovens::engagement-card title="Igreja local">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-600 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                    <x-icon name="users" style="duotone" class="h-6 w-6" />
                </div>
                <div class="min-w-0 flex-1">
                    @if ($user->church)
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->church->name }}
                            @if ($user->church->city)
                                — {{ $user->church->city }}
                            @endif
                        </p>
                        @if ($user->church->phone || $user->church->email)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                @if ($user->church->phone)
                                    {{ $user->church->phone }}
                                @endif
                                @if ($user->church->phone && $user->church->email)
                                    ·
                                @endif
                                @if ($user->church->email)
                                    <a href="mailto:{{ $user->church->email }}" class="font-medium text-blue-600 underline decoration-blue-600/30 hover:decoration-blue-600 dark:text-blue-400">{{ $user->church->email }}</a>
                                @endif
                            </p>
                        @endif
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-300">Ainda sem congregação associada. Fala com o líder da tua igreja ou com a secretaria JUBAF.</p>
                    @endif
                </div>
            </div>
        </x-ui.jovens::engagement-card>

        @if (module_enabled('Calendario') && Route::has('jovens.eventos.index'))
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <x-ui.jovens::engagement-card>
                    <x-slot:header>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Próximos eventos</h2>
                        <a href="{{ route('jovens.eventos.index') }}"
                            class="text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">Ver todos</a>
                    </x-slot:header>
                    <ul class="space-y-3">
                        @forelse($featuredEvents ?? [] as $ev)
                            <li>
                                <a href="{{ route('jovens.eventos.show', $ev) }}"
                                    class="group block rounded-lg border border-gray-200 bg-gray-50 p-4 transition hover:border-blue-300 hover:bg-white dark:border-gray-600 dark:bg-gray-900/50 dark:hover:border-blue-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                    <div class="flex items-start justify-between gap-2">
                                        <span
                                            class="font-semibold text-gray-900 group-hover:text-blue-700 dark:text-white dark:group-hover:text-blue-300">{{ $ev->title }}</span>
                                        @if ($ev->is_featured)
                                            <x-ui.jovens::status-pill label="Destaque" variant="warning" />
                                        @endif
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $ev->starts_at?->timezone(config('app.timezone'))->translatedFormat('d M Y, H:i') }}</p>
                                </a>
                            </li>
                        @empty
                            <x-ui.jovens::empty-state title="Sem eventos futuros visíveis"
                                description="Quando a diretoria publicar encontros para a tua idade, aparecem aqui."
                                icon="calendar-days" />
                        @endforelse
                    </ul>
                </x-ui.jovens::engagement-card>

                <x-ui.jovens::engagement-card>
                    <x-slot:header>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Participação recente</h2>
                    </x-slot:header>
                    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Inscrições confirmadas em eventos passados.</p>
                    <ul class="space-y-2 text-sm">
                        @forelse($pastParticipations ?? [] as $reg)
                            <li
                                class="flex items-center justify-between gap-2 border-b border-gray-100 pb-2 last:border-0 dark:border-gray-700">
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $reg->event?->title ?? 'Evento' }}</span>
                                <span class="shrink-0 text-xs text-gray-500">{{ $reg->event?->starts_at?->timezone(config('app.timezone'))->translatedFormat('M Y') }}</span>
                            </li>
                        @empty
                            <li class="text-gray-500 dark:text-gray-400">Ainda sem participações registadas.</li>
                        @endforelse
                    </ul>
                </x-ui.jovens::engagement-card>
            </div>
        @endif

        @if (($user->talentProfile?->skills?->isNotEmpty() ?? false))
            <x-ui.jovens::engagement-card title="As tuas competências">
                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Meus talentos</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($user->talentProfile->skills->take(12) as $sk)
                        @php $verified = filled($sk->pivot?->validated_at); @endphp
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-800 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                            {{ $sk->name }}
                            @if ($verified)
                                <x-ui.jovens::status-pill label="Verificado" variant="success" class="!px-2 !py-0 !text-[10px]" />
                            @endif
                        </span>
                    @endforeach
                </div>
                @if (module_enabled('Talentos') && Route::has('jovens.talentos.profile.edit'))
                    <a href="{{ route('jovens.talentos.profile.edit') }}"
                        class="mt-4 inline-flex text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">Editar ficha de talentos</a>
                @endif
            </x-ui.jovens::engagement-card>
        @endif

        <div>
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Recursos e atalhos</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-5 xl:grid-cols-4">
                <a href="{{ route('homepage') }}"
                    class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-800 dark:hover:border-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    <div
                        class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-600 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                        <x-icon name="home" style="duotone" class="h-5 w-5" />
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Site JUBAF</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-500 dark:text-gray-400">Página pública, diretoria, rádio e conteúdos.</p>
                </a>

                @if (Route::has('jovens.devotionals.index'))
                    <a href="{{ route('jovens.devotionals.index') }}"
                        class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-800 dark:hover:border-blue-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                        <div
                            class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-600 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                            <x-icon name="book-open" style="duotone" class="h-5 w-5" />
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Devocionais</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500 dark:text-gray-400">Leituras e reflexão no painel Unijovem.</p>
                    </a>
                @elseif (Route::has('devocionais.index'))
                    <a href="{{ route('devocionais.index') }}"
                        class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-200 dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-600 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                            <x-icon name="book-open" style="duotone" class="h-5 w-5" />
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Devocionais</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Materiais no site público.</p>
                    </a>
                @endif

                @if (module_enabled('Bible') && Route::has('jovens.bible.plans.index'))
                    <a href="{{ route('jovens.bible.plans.index') }}"
                        class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-200 dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-600 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                            <x-icon name="book-bible" style="duotone" class="h-5 w-5" />
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Bíblia e planos</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Planos de leitura e leitor.</p>
                    </a>
                @elseif (Route::has('bible.public.index'))
                    <a href="{{ route('bible.public.index') }}"
                        class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-600 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                            <x-icon name="book-open" style="duotone" class="h-5 w-5" />
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Bíblia JUB</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Leitura pública.</p>
                    </a>
                @endif

                @if (module_enabled('Notificacoes') && Route::has('jovens.notificacoes.index'))
                    <a href="{{ route('jovens.notificacoes.index') }}"
                        class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-200 dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-600 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                            <x-icon name="bell" style="duotone" class="h-5 w-5" />
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Notificações</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Avisos enviados para a tua conta.</p>
                    </a>
                @endif

                @if (module_enabled('Chat'))
                    <a href="{{ route('jovens.chat.page') }}"
                        class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-200 dark:border-gray-700 dark:bg-gray-800">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-600 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                            <x-icon name="comments" style="duotone" class="h-5 w-5" />
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Mensagens</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Canal interno com a equipa JUBAF.</p>
                    </a>
                @else
                    <div
                        class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-5 dark:border-gray-600 dark:bg-gray-900/50">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg bg-gray-200 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <x-icon name="comments" style="duotone" class="h-5 w-5" />
                        </div>
                        <h3 class="text-base font-semibold text-gray-600 dark:text-gray-300">Chat</h3>
                        <p class="mt-2 text-sm text-gray-500">O módulo de chat não está activo.</p>
                    </div>
                @endif
            </div>
        </div>

        <x-ui.card>
            <x-slot:header>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Próximos passos</h2>
            </x-slot:header>
            <ul class="space-y-4">
                <li class="flex gap-4">
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">1</span>
                    <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300"><strong class="text-gray-900 dark:text-white">Completa o teu perfil</strong> — foto, telefone e palavra-passe segura.</p>
                </li>
                <li class="flex gap-4">
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">2</span>
                    <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300"><strong class="text-gray-900 dark:text-white">Consulta avisos urgentes</strong> — mantém-te a par do que a JUBAF partilha contigo.</p>
                </li>
                <li class="flex gap-4">
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">3</span>
                    <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300"><strong class="text-gray-900 dark:text-white">Participa nos eventos</strong> — inscreve-te e usa a carteira para QR e pagamentos.</p>
                </li>
            </ul>
        </x-ui.card>
    </x-ui.jovens::page-shell>
@endsection
