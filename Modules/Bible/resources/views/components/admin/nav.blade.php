{{-- Submenu Bíblia — painel /admin/biblia-digital --}}
@php
    $linkBase =
        'flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-gray-100';
    $linkActive =
        'flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition-colors bg-amber-50 text-amber-900 dark:bg-amber-950/50 dark:text-amber-200';
    $isVersions = bible_route_is(
        'admin.bible.index',
        'admin.bible.create',
        'admin.bible.store',
        'admin.bible.show',
        'admin.bible.edit',
        'admin.bible.update',
        'admin.bible.book',
        'admin.bible.chapter',
        'admin.bible.chapter-audio.index',
        'admin.bible.chapter-audio.template',
    );
    $isImport = bible_route_is('admin.bible.import', 'admin.bible.import.store');
    $isPlans = bible_route_is('admin.bible.plans.*');
    $isStrongs = bible_route_is('admin.bible.study.strongs.*');
    $isCommentary = bible_route_is('admin.bible.study.commentary.*');
    $isCrossRefs = bible_route_is('admin.bible.study.cross-refs.*');
    $isReports = bible_route_is('admin.bible.reports.church-plan');
    $isInterlinearReader = bible_route_is('admin.bible.tools.interlinear');
@endphp
<nav class="space-y-0.5" aria-label="Submenu Bíblia Digital">
    <a href="{{ bible_admin_route('index') }}" class="{{ $isVersions ? $linkActive : $linkBase }}">
        <x-icon name="book-open" class="size-5 shrink-0 opacity-90" />
        Versões e livros
    </a>
    <a href="{{ bible_admin_route('import') }}" class="{{ $isImport ? $linkActive : $linkBase }}">
        <x-icon name="file-import" class="size-5 shrink-0 opacity-90" />
        Importação JSON
    </a>
    <a href="{{ bible_admin_route('plans.index') }}" class="{{ $isPlans ? $linkActive : $linkBase }}">
        <x-icon name="calendar-check" class="size-5 shrink-0 opacity-90" />
        Planos de leitura
    </a>
    <p class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Estudo
        interlinear</p>
    <a href="{{ bible_admin_route('tools.interlinear') }}" class="{{ $isInterlinearReader ? $linkActive : $linkBase }}">
        <x-icon name="layer-group" class="size-5 shrink-0 opacity-90" />
        {{ __('Leitor interlinear') }}
    </a>
    <a href="{{ bible_admin_route('study.strongs.index') }}" class="{{ $isStrongs ? $linkActive : $linkBase }}">
        <x-icon name="book-bible" class="size-5 shrink-0 opacity-90" />
        Léxico Strong
    </a>
    <a href="{{ bible_admin_route('study.commentary.sources.index') }}"
        class="{{ $isCommentary ? $linkActive : $linkBase }}">
        <x-icon name="comments" class="size-5 shrink-0 opacity-90" />
        Comentários
    </a>
    <a href="{{ bible_admin_route('study.cross-refs.index') }}" class="{{ $isCrossRefs ? $linkActive : $linkBase }}">
        <x-icon name="link" class="size-5 shrink-0 opacity-90" />
        Refs. cruzadas
    </a>
    <a href="{{ bible_admin_route('reports.church-plan') }}" class="{{ $isReports ? $linkActive : $linkBase }}">
        <x-icon name="chart-line" class="size-5 shrink-0 opacity-90" />
        Relatório por igreja
    </a>
</nav>
