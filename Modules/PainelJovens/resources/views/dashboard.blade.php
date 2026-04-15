@extends('paineljovens::components.layouts.app')

@section('title', 'Início')

@section('content')
    @php
        $u = $user ?? auth()->user();
    @endphp

    <div class="mx-auto max-w-7xl space-y-8 px-4 animate-fade-in pb-8 sm:px-6 lg:px-8 md:space-y-10">
        {{-- Hero --}}
        <div
            class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 dark:border-slate-800 bg-gradient-to-br from-violet-600 via-fuchsia-600 to-slate-900 text-white shadow-2xl shadow-violet-900/20">
            <div class="absolute inset-0 opacity-20 pointer-events-none"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.15\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>
            <div
                class="relative px-8 py-10 md:px-12 md:py-12 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold uppercase tracking-wider text-violet-100/95 mb-3">Bem-vindo(a)</p>
                    <h1 class="text-3xl md:text-4xl font-bold tracking-tight leading-tight">Olá,
                        {{ explode(' ', $u->name)[0] }}</h1>
                    <p class="mt-4 text-sm md:text-base text-violet-50/90 font-medium leading-relaxed">
                        Este é o teu espaço <strong>Unijovem</strong> na JUBAF — conta criada pelo líder da tua igreja
                        local. Aqui podes atualizar o perfil, ver avisos, usar o chat institucional (quando ativo) e aceder
                        à Bíblia e planos de leitura.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 shrink-0">
                    <a href="{{ route('jovens.profile.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-white text-violet-800 text-sm font-semibold shadow-lg hover:bg-violet-50 transition-all active:scale-[0.98]">
                        <x-icon name="user-gear" class="w-4 h-4" />
                        Meu perfil
                    </a>
                    @if (module_enabled('Chat'))
                        <a href="{{ route('jovens.chat.page') }}"
                            class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-slate-900/40 backdrop-blur border border-white/20 text-white text-sm font-semibold hover:bg-slate-900/60 transition-all active:scale-[0.98]">
                            <x-icon name="messages" class="w-4 h-4" />
                            Chat
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div
            class="rounded-2xl border border-violet-200/80 dark:border-violet-900/40 bg-violet-50/90 dark:bg-violet-950/30 px-6 py-5 flex flex-col sm:flex-row sm:items-center gap-4">
            <div
                class="w-12 h-12 rounded-2xl bg-violet-500/20 flex items-center justify-center text-violet-700 dark:text-violet-300 shrink-0">
                <x-icon name="users" style="duotone" class="w-6 h-6" />
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-base font-bold text-violet-900 dark:text-violet-200">Igreja local</h2>
                @if ($user->church)
                    <p class="text-sm text-violet-900/90 dark:text-violet-100/90 mt-1 font-medium">{{ $user->church->name }}
                        @if ($user->church->city)
                            — {{ $user->church->city }}
                        @endif
                    </p>
                    @if ($user->church->phone || $user->church->email)
                        <p class="text-sm text-violet-800/80 dark:text-violet-200/80 mt-1">
                            @if ($user->church->phone)
                                {{ $user->church->phone }}
                            @endif
                            @if ($user->church->phone && $user->church->email)
                                ·
                            @endif
                            @if ($user->church->email)
                                <a href="mailto:{{ $user->church->email }}" class="underline">{{ $user->church->email }}</a>
                            @endif
                        </p>
                    @endif
                @else
                    <p class="text-sm text-violet-900/80 dark:text-violet-100/80 mt-1">
                        Ainda sem congregação associada. Fala com o líder da tua igreja ou com a secretaria JUBAF para
                        concluir o vínculo.
                    </p>
                @endif
            </div>
        </div>

        {{-- Atalhos --}}
        <div>
            <h2 class="text-sm font-semibold text-slate-600 dark:text-slate-400 mb-4">Recursos e atalhos</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">

                <a href="{{ route('homepage') }}"
                    class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-violet-300 dark:hover:border-violet-800 transition-all">
                    <div
                        class="w-12 h-12 rounded-xl bg-violet-500/10 text-violet-600 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <x-icon name="home" style="duotone" class="w-6 h-6" />
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Site JUBAF</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Página pública, diretoria,
                        rádio e conteúdos para a juventude.</p>
                </a>

                @if (Route::has('jovens.devotionals.index'))
                    <a href="{{ route('jovens.devotionals.index') }}"
                        class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-fuchsia-300 dark:hover:border-fuchsia-800 transition-all">
                        <div
                            class="w-12 h-12 rounded-xl bg-fuchsia-500/10 text-fuchsia-600 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                            <x-icon name="book-open" style="duotone" class="w-6 h-6" />
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Devocionais</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Leituras, reflexão, vídeo e referência bíblica — aqui no painel Unijovem.</p>
                    </a>
                @elseif (Route::has('devocionais.index'))
                    <a href="{{ route('devocionais.index') }}"
                        class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-fuchsia-300 dark:hover:border-fuchsia-800 transition-all">
                        <div
                            class="w-12 h-12 rounded-xl bg-fuchsia-500/10 text-fuchsia-600 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                            <x-icon name="book-open" style="duotone" class="w-6 h-6" />
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Devocionais</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Leituras e materiais espirituais no site público.</p>
                    </a>
                @endif

                @if (module_enabled('Bible') && Route::has('jovens.bible.plans.index'))
                    <a href="{{ route('jovens.bible.plans.index') }}"
                        class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-teal-300 dark:hover:border-teal-800 transition-all">
                        <div
                            class="w-12 h-12 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                            <x-icon name="book-bible" style="duotone" class="w-6 h-6" />
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Bíblia e planos</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Planos de leitura,
                            leitor, favoritos, busca e interlinear no teu painel.</p>
                    </a>
                @elseif(Route::has('bible.public.index'))
                    <a href="{{ route('bible.public.index') }}"
                        class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-sky-300 dark:hover:border-sky-800 transition-all">
                        <div
                            class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-600 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                            <x-icon name="book-open" style="duotone" class="w-6 h-6" />
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Bíblia JUB</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Leitura pública com
                            estudo e interlinear quando disponível.</p>
                    </a>
                @endif

                @if (module_enabled('Notificacoes') && Route::has('jovens.notificacoes.index'))
                    <a href="{{ route('jovens.notificacoes.index') }}"
                        class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-amber-300 dark:hover:border-amber-800 transition-all">
                        <div
                            class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                            <x-icon name="bell" style="duotone" class="w-6 h-6" />
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Notificações</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Avisos enviados para a
                            tua conta.</p>
                    </a>
                @endif

                @if (module_enabled('Chat'))
                    <a href="{{ route('jovens.chat.page') }}"
                        class="group rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md hover:border-blue-300 dark:hover:border-blue-800 transition-all">
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                            <x-icon name="comments" style="duotone" class="w-6 h-6" />
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Mensagens</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Canal interno com a
                            diretoria e equipe JUBAF.</p>
                    </a>
                @else
                    <div
                        class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900/50 p-6">
                        <div
                            class="w-12 h-12 rounded-xl bg-slate-200/80 dark:bg-slate-800 text-slate-500 flex items-center justify-center mb-4">
                            <x-icon name="comments" style="duotone" class="w-6 h-6" />
                        </div>
                        <h3 class="text-base font-bold text-slate-600 dark:text-slate-300">Chat</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">O módulo de chat não está ativo neste
                            ambiente.</p>
                    </div>
                @endif

            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 md:p-10">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Próximos passos</h2>
            <ul class="space-y-4">
                <li class="flex gap-4">
                    <span
                        class="w-8 h-8 rounded-full bg-violet-500/15 text-violet-700 dark:text-violet-400 flex items-center justify-center text-xs font-black shrink-0">1</span>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed"><strong
                            class="text-slate-900 dark:text-white">Completa o teu perfil</strong> — foto, telefone e
                        palavra-passe segura.</p>
                </li>
                <li class="flex gap-4">
                    <span
                        class="w-8 h-8 rounded-full bg-violet-500/15 text-violet-700 dark:text-violet-400 flex items-center justify-center text-xs font-black shrink-0">2</span>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed"><strong
                            class="text-slate-900 dark:text-white">Consulta as notificações</strong> — mantém-te a par do
                        que a JUBAF partilha contigo.</p>
                </li>
                <li class="flex gap-4">
                    <span
                        class="w-8 h-8 rounded-full bg-violet-500/15 text-violet-700 dark:text-violet-400 flex items-center justify-center text-xs font-black shrink-0">3</span>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed"><strong
                            class="text-slate-900 dark:text-white">Participa na juventude</strong> — eventos e comunicações
                        aparecem no site e nos módulos ativos.</p>
                </li>
            </ul>
        </div>
    </div>
@endsection
