@extends('homepage::layouts.homepage')

@section('title')
    Banco de talentos — {{ \App\Support\SiteBranding::siteName() }}
@endsection

@section('content')
    @include('homepage::layouts.navbar-homepage')

    @php
        $siteName = \App\Support\SiteBranding::siteName();
    @endphp

    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-violet-50/50 dark:from-slate-900 dark:via-slate-900 dark:to-violet-950/30 py-12 md:py-16">
        <div class="container mx-auto max-w-4xl px-4">
            <div class="mb-10 text-center">
                <div
                    class="inline-flex items-center gap-2 rounded-full bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-900 dark:bg-violet-900/40 dark:text-violet-100">
                    <x-module-icon module="Talentos" class="h-5 w-5" />
                    Voluntariado JUBAF
                </div>
                <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white md:text-4xl">Banco de
                    talentos</h1>
                <p class="mx-auto mt-3 max-w-2xl text-base text-gray-600 dark:text-gray-300">
                    O banco de talentos é o registo em que jovens e líderes indicam em que podem servir — música, receção,
                    comunicação, logística e outras áreas.
                    A <span class="font-semibold text-gray-800 dark:text-gray-200">diretoria</span> consulta um diretório
                    interno e pode enviar convites alinhados com eventos do calendário, sempre com respeito pela
                    disponibilidade de cada pessoa.
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div
                    class="rounded-2xl border border-violet-200/80 bg-white/90 p-6 shadow-sm dark:border-violet-900/40 dark:bg-slate-800/90">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300">
                        <x-icon name="users" class="h-6 w-6" style="duotone" />
                    </div>
                    <h2 class="mt-4 text-lg font-bold text-gray-900 dark:text-white">Quem pode inscrever-se?</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                        Membros com acesso ao painel de <strong class="text-gray-800 dark:text-gray-200">jovens</strong> ou
                        de <strong class="text-gray-800 dark:text-gray-200">líderes</strong> podem preencher ou atualizar a
                        ficha de talentos quando o módulo estiver ativo na sua conta.
                    </p>
                </div>
                <div
                    class="rounded-2xl border border-fuchsia-200/70 bg-white/90 p-6 shadow-sm dark:border-fuchsia-900/35 dark:bg-slate-800/90">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-fuchsia-100 text-fuchsia-800 dark:bg-fuchsia-900/40 dark:text-fuchsia-200">
                        <x-icon name="shield-halved" class="h-6 w-6" style="duotone" />
                    </div>
                    <h2 class="mt-4 text-lg font-bold text-gray-900 dark:text-white">Privacidade</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                        A lista completa e os contactos são visíveis apenas na equipe da diretoria com permissão. Pode optar
                        por não aparecer no diretório interno e mesmo assim manter dados mínimos para convites pontuais.
                    </p>
                </div>
            </div>

            <div
                class="mt-10 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 md:p-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Como atualizar a minha ficha?</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Inicie sessão no painel da JUBAF e abra a área de
                    talentos no seu perfil de jovem ou de líder.</p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    @auth
                        @if (Route::has('jovens.talentos.profile.edit') && auth()->user()->hasRole('jovens'))
                            <a href="{{ route('jovens.talentos.profile.edit') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-violet-600/25 transition hover:bg-violet-700">
                                <x-icon name="pen-to-square" class="h-4 w-4" style="solid" />
                                Abrir inscrição (jovens)
                            </a>
                        @endif
                        @if (Route::has('lideres.talentos.profile.edit') && auth()->user()->hasRole('lider'))
                            <a href="{{ route('lideres.talentos.profile.edit') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">
                                <x-icon name="pen-to-square" class="h-4 w-4" style="solid" />
                                Abrir inscrição (líderes)
                            </a>
                        @endif
                        @if (Route::has('diretoria.talentos.dashboard') &&
                                (auth()->user()->can('talentos.directory.view') || auth()->user()->can('talentos.assignments.view')))
                            <a href="{{ route('diretoria.talentos.dashboard') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-800 transition hover:border-violet-300 hover:bg-violet-50/50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-violet-700">
                                <x-icon name="chart-pie" class="h-4 w-4 text-violet-600 dark:text-violet-400" style="duotone" />
                                Painel da diretoria
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-violet-600/25 transition hover:bg-violet-700">
                            <x-icon name="right-to-bracket" class="h-4 w-4" style="solid" />
                            Iniciar sessão
                        </a>
                    @endauth
                </div>
            </div>

            <p class="mt-10 text-center text-xs text-gray-500 dark:text-gray-500">
                {{ $siteName }} — serviço e coordenação regional da juventude (Feira de Santana / ASBAF).
            </p>
        </div>
    </div>

    @include('homepage::layouts.footer-homepage')
@endsection
