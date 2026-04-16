{{--
    Atalhos para módulos do painel (perfil estilo rede social — integração com o resto da app).
    @props context: diretoria|admin|jovens|lider|pastor, accent: indigo|violet|emerald|blue
--}}
@props([
    'context' => 'diretoria',
    'accent' => 'indigo',
])

@php
    $u = auth()->user();
    $items = [];

    $add = function (string $label, string $icon, string $routeName, ?callable $gate = null) use (&$items, $u) {
        if (! \Illuminate\Support\Facades\Route::has($routeName)) {
            return;
        }
        if ($gate !== null && (! $u || ! $gate($u))) {
            return;
        }
        $items[] = ['label' => $label, 'icon' => $icon, 'url' => route($routeName)];
    };

    if ($context === 'diretoria') {
        $add('Painel', 'gauge-high', 'diretoria.dashboard');
        if (module_enabled('Avisos')) {
            $add('Avisos', 'bullhorn', 'diretoria.avisos.index');
        }
        if (module_enabled('Notificacoes')) {
            $add('Notificações', 'bell', 'diretoria.notificacoes.index');
        }
        if (module_enabled('Homepage')) {
            $add('Homepage', 'house', 'diretoria.homepage.index', fn ($user) => $user->can('homepage.edit'));
        }
        if (module_enabled('Calendario')) {
            $add('Calendário', 'calendar-days', 'diretoria.calendario.dashboard', fn ($user) => $user->can('calendario.events.view'));
        }
        if (module_enabled('Financeiro')) {
            $add('Financeiro', 'coins', 'diretoria.financeiro.dashboard', fn ($user) => $user->can('financeiro.dashboard.view'));
        }
        if (module_enabled('Chat')) {
            $add('Chat', 'comments', 'diretoria.chat.index');
        }
        if (module_enabled('Igrejas')) {
            $add('Igrejas', 'church', 'diretoria.igrejas.dashboard');
        }
        if (module_enabled('Secretaria')) {
            $add('Secretaria', 'file-lines', 'diretoria.secretaria.dashboard');
        }
        if (module_enabled('Talentos')) {
            $add('Talentos', 'star', 'diretoria.talentos.dashboard');
        }
        if (user_is_diretoria_executive($u)) {
            $add('Segurança', 'shield-halved', 'diretoria.seguranca.hub');
        }
    } elseif ($context === 'admin') {
        $add('Painel', 'gauge-high', 'admin.dashboard');
        if (module_enabled('Homepage')) {
            $add('Homepage', 'house', 'admin.homepage.index');
        }
        if (module_enabled('Blog')) {
            $add('Blog', 'newspaper', 'admin.blog.index');
        }
        if (module_enabled('Notificacoes')) {
            $add('Notificações', 'bell', 'admin.notificacoes.index');
        }
        if (module_enabled('Avisos')) {
            $add('Avisos', 'bullhorn', 'admin.avisos.index');
        }
        if (user_can_access_admin_panel($u)) {
            $add('Segurança', 'shield-halved', 'admin.seguranca.hub');
        }
    } elseif ($context === 'jovens') {
        $add('Painel', 'gauge-high', 'jovens.dashboard');
        if (module_enabled('Igrejas')) {
            $add('Minha igreja', 'church', 'jovens.igreja.index');
        }
        if (module_enabled('Notificacoes')) {
            $add('Alertas', 'bell', 'jovens.notificacoes.index');
        }
        if (module_enabled('Avisos')) {
            $add('Avisos JUBAF', 'bullhorn', 'jovens.avisos.index');
        }
        if (module_enabled('Secretaria')) {
            $add('Secretaria', 'file-lines', 'jovens.secretaria.index', fn ($user) => $user->can('secretaria.minutes.view'));
        }
        if (module_enabled('Chat')) {
            $add('Chat', 'comments', 'jovens.chat.index');
        }
        if (module_enabled('Bible')) {
            $add('Bíblia', 'book-bible', 'jovens.bible.index');
        }
        if (module_enabled('Calendario')) {
            $add('Calendário', 'calendar-days', 'jovens.eventos.index');
        }
        if (module_enabled('Talentos')) {
            $add('Talentos', 'star', 'jovens.talentos.profile.edit');
        }
    } elseif ($context === 'lider') {
        $add('Painel', 'gauge-high', 'lideres.dashboard');
        if (module_enabled('Igrejas')) {
            $add('Congregação', 'church', 'lideres.congregacao.index');
        }
        if (module_enabled('Avisos')) {
            $add('Avisos JUBAF', 'bullhorn', 'lideres.avisos.index');
        }
        if (module_enabled('Secretaria')) {
            $add('Secretaria', 'file-lines', 'lideres.secretaria.index', fn ($user) => $user->can('secretaria.minutes.view'));
        }
        if (module_enabled('Chat')) {
            $add('Chat', 'comments', 'lideres.chat.index');
        }
        if (module_enabled('Bible')) {
            $add('Bíblia', 'book-bible', 'lideres.bible.index');
        }
        if (module_enabled('Calendario')) {
            $add('Calendário', 'calendar-days', 'lideres.calendario.index');
        }
        if (module_enabled('Talentos')) {
            $add('Talentos', 'star', 'lideres.talentos.profile.edit');
        }
    } elseif ($context === 'pastor') {
        $add('Painel', 'gauge-high', 'pastor.dashboard');
        if (module_enabled('Avisos')) {
            $add('Avisos', 'bullhorn', 'pastor.avisos.index');
        }
        if (module_enabled('Igrejas')) {
            $add('Igrejas', 'church', 'pastor.igrejas.index', fn ($user) => $user->can('igrejas.view'));
        }
    }

    $accentRing = [
        'blue' => 'border-blue-200/80 bg-blue-50/80 text-blue-900 hover:bg-blue-100 dark:border-blue-800/60 dark:bg-blue-950/40 dark:text-blue-200 dark:hover:bg-blue-900/50',
        'indigo' => 'border-indigo-200/80 bg-indigo-50/80 text-indigo-800 hover:bg-indigo-100 dark:border-indigo-800/60 dark:bg-indigo-950/40 dark:text-indigo-200 dark:hover:bg-indigo-900/50',
        'violet' => 'border-violet-200/80 bg-violet-50/80 text-violet-900 hover:bg-violet-100 dark:border-violet-800/60 dark:bg-violet-950/40 dark:text-violet-200 dark:hover:bg-violet-900/50',
        'emerald' => 'border-emerald-200/80 bg-emerald-50/80 text-emerald-900 hover:bg-emerald-100 dark:border-emerald-800/60 dark:bg-emerald-950/40 dark:text-emerald-200 dark:hover:bg-emerald-900/50',
    ];
    $chip = $accentRing[$accent] ?? $accentRing['indigo'];
@endphp

@if(count($items))
    <div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200/80 bg-white/90 p-4 shadow-sm backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/80']) }}>
        <p class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
            <x-icon name="bookmark" class="h-3.5 w-3.5 opacity-80" />
            Atalhos do painel
        </p>
        <div class="flex flex-wrap gap-2">
            @foreach($items as $row)
                <a href="{{ $row['url'] }}" class="inline-flex items-center gap-1.5 rounded-xl border px-3 py-2 text-xs font-semibold transition {{ $chip }}">
                    <x-icon :name="$row['icon']" class="h-3.5 w-3.5 shrink-0 opacity-90" />
                    {{ $row['label'] }}
                </a>
            @endforeach
        </div>
    </div>
@endif
