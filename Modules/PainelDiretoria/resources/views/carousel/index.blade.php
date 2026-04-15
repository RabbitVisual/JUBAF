@extends('paineldiretoria::components.layouts.app')

@section('title', 'Carrossel da página inicial')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    @include('paineldiretoria::partials.carousel-subnav', ['active' => 'lista'])

    {{-- Cabeçalho --}}
    <header class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 md:p-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold uppercase tracking-widest text-pink-600 dark:text-pink-400">Mídia e destaques</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Carrossel da home</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Os slides rotacionam no topo da página inicial. Ative ou desative o bloco inteiro, crie mensagens com texto rico e imagem, e defina a ordem — menor número aparece primeiro.
                </p>
                <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                    <a href="{{ route('diretoria.dashboard') }}" class="transition hover:text-pink-600 dark:hover:text-pink-400">Diretoria</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <span class="font-medium text-gray-800 dark:text-slate-300">Carrossel</span>
                </nav>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-gray-50/80 px-4 py-3 dark:border-slate-600 dark:bg-slate-900/50">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-400">Bloco na home</span>
                    <label class="relative inline-flex cursor-pointer items-center gap-2">
                        <input type="checkbox" id="carouselToggle" class="peer sr-only" {{ $isEnabled ? 'checked' : '' }} data-toggle-route="{{ route('diretoria.carousel.toggle') }}">
                        <span class="relative h-7 w-12 rounded-full bg-gray-300 transition peer-checked:bg-pink-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300/50 dark:bg-gray-600 dark:peer-focus:ring-pink-900/40 after:absolute after:top-0.5 after:left-0.5 after:h-6 after:w-6 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:after:translate-x-5"></span>
                        <span id="carouselStatus" class="min-w-[5.5rem] text-xs font-bold tabular-nums text-gray-800 dark:text-slate-200">{{ $isEnabled ? 'Ativado' : 'Desativado' }}</span>
                    </label>
                </div>
                <a href="{{ route('diretoria.carousel.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-pink-600 to-pink-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-pink-500/20 transition hover:from-pink-700 hover:to-pink-800 focus:outline-none focus:ring-4 focus:ring-pink-300/40 dark:focus:ring-pink-900/50">
                    <x-icon name="plus" class="h-5 w-5" style="solid" />
                    Novo slide
                </a>
            </div>
        </div>
    </header>

    {{-- Dica rápida --}}
    <div class="flex gap-4 rounded-2xl border border-sky-200/80 bg-sky-50/90 p-4 dark:border-sky-900/40 dark:bg-sky-950/30 md:items-center md:p-5">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-200/80 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200">
            <x-icon name="circle-info" class="h-5 w-5" style="duotone" />
        </span>
        <div class="min-w-0 text-sm text-sky-950/90 dark:text-sky-100/90">
            <p class="font-semibold text-sky-900 dark:text-sky-100">Como organizar</p>
            <p class="mt-1 leading-relaxed text-sky-900/85 dark:text-sky-200/85">
                Use a lista abaixo para ver o que está no ar. Arraste pelo ícone à esquerda para reordenar sem editar cada slide. Slides inativos permanecem na lista, mas não aparecem na home.
            </p>
        </div>
    </div>

    {{-- Métricas --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-pink-200/80 dark:border-slate-700 dark:bg-slate-800/80 dark:hover:border-pink-800/60">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-pink-500/10 blur-2xl transition group-hover:bg-pink-500/15"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Total de slides</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $slides->count() }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Cadastrados no painel</p>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-emerald-200/80 dark:border-slate-700 dark:bg-slate-800/80 dark:hover:border-emerald-800/60">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10 blur-2xl transition group-hover:bg-emerald-500/15"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Visíveis na home</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $slides->where('is_active', true)->count() }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Com status ativo</p>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:border-violet-200/80 dark:border-slate-700 dark:bg-slate-800/80 dark:hover:border-violet-800/60 sm:col-span-2 lg:col-span-1">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-violet-500/10 blur-2xl transition group-hover:bg-violet-500/15"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Com imagem no slide</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $slides->where('show_image', true)->whereNotNull('image')->count() }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Exibição de mídia ligada e arquivo enviado</p>
        </div>
    </div>

    @if ($slides->count() > 0)
        <section class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80" aria-labelledby="slides-gallery-heading">
            <div class="flex flex-col gap-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 id="slides-gallery-heading" class="text-base font-semibold text-gray-900 dark:text-white">Seus slides</h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Arraste para alterar a ordem de exibição na home</p>
                </div>
                <span class="inline-flex w-fit items-center rounded-full bg-pink-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-pink-800 dark:bg-pink-950/50 dark:text-pink-200">
                    {{ $slides->count() }} {{ $slides->count() === 1 ? 'item' : 'itens' }}
                </span>
            </div>

            <div id="slidesList" class="divide-y divide-gray-100 dark:divide-slate-700" data-reorder-route="{{ route('diretoria.carousel.reorder') }}">
                @foreach ($slides as $slide)
                    <article class="slide-item group transition-colors hover:bg-gray-50/80 dark:hover:bg-slate-700/30" data-id="{{ $slide->id }}">
                        <div class="flex flex-col gap-5 p-4 sm:p-5 md:flex-row md:items-stretch md:gap-6">
                            <div class="hidden shrink-0 cursor-grab items-center self-start rounded-xl border border-transparent p-2 active:cursor-grabbing md:flex drag-handle hover:border-pink-200 hover:bg-pink-50 dark:hover:border-pink-800 dark:hover:bg-pink-950/30" title="Arrastar para reordenar">
                                <x-icon name="bars" class="h-6 w-6 text-slate-400 group-hover:text-pink-500" />
                            </div>

                            <div class="relative h-40 w-full shrink-0 overflow-hidden rounded-2xl border border-gray-200 bg-gray-100 dark:border-slate-600 dark:bg-slate-900 md:h-auto md:w-52 md:min-h-[9rem]">
                                @if ($slide->image && $slide->show_image)
                                    <img src="{{ asset('storage/' . $slide->image) }}" alt="" class="h-full w-full object-cover" loading="lazy" />
                                @else
                                    <div class="flex h-full min-h-[10rem] flex-col items-center justify-center gap-2 text-slate-400 md:min-h-0">
                                        <x-icon name="image" class="h-9 w-9 opacity-60" style="duotone" />
                                        <span class="text-[10px] font-bold uppercase tracking-wider">Sem imagem</span>
                                    </div>
                                @endif
                                <div class="absolute right-2 top-2 rounded-lg bg-black/55 px-2 py-1 text-[11px] font-bold tabular-nums text-white backdrop-blur-sm" data-carousel-order-badge>
                                    #{{ $slide->order }}
                                </div>
                            </div>

                            <div class="min-w-0 flex-1 py-0.5">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                    @if ($slide->title)
                                        {{ Str::limit(strip_tags($slide->title), 90) }}
                                    @else
                                        <span class="font-normal italic text-slate-400">Sem título</span>
                                    @endif
                                </h3>
                                @if ($slide->description)
                                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                                        {{ Str::limit(strip_tags($slide->description), 160) }}
                                    </p>
                                @else
                                    <p class="mt-2 text-sm italic text-slate-400">Sem texto de apoio</p>
                                @endif

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide {{ $slide->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $slide->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $slide->is_active ? 'Ativo na home' : 'Inativo' }}
                                    </span>
                                    @if ($slide->link)
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-pink-50 px-2.5 py-1 text-[10px] font-semibold text-pink-700 dark:bg-pink-950/40 dark:text-pink-300">
                                            <x-icon name="link" class="h-3 w-3" />
                                            Com link
                                        </span>
                                    @endif
                                    @if ($slide->title && strip_tags($slide->title) !== $slide->title)
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-gray-100 px-2.5 py-1 text-[10px] font-semibold text-gray-600 dark:bg-slate-700 dark:text-slate-300">
                                            <x-icon name="code-bracket" class="h-3 w-3" />
                                            HTML
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center justify-end gap-1 border-t border-gray-100 pt-4 dark:border-slate-700 md:flex-col md:justify-center md:border-l md:border-t-0 md:pl-4 md:pt-0">
                                <a href="{{ route('diretoria.carousel.edit', $slide) }}" class="rounded-xl p-2.5 text-slate-500 transition hover:bg-pink-50 hover:text-pink-600 dark:text-slate-400 dark:hover:bg-pink-950/40 dark:hover:text-pink-400" title="Editar slide">
                                    <x-icon name="pencil-square" class="h-5 w-5" style="duotone" />
                                    <span class="sr-only">Editar</span>
                                </a>
                                <button
                                    type="button"
                                    onclick="confirmDelete('{{ route('diretoria.carousel.destroy', $slide) }}')"
                                    class="rounded-xl p-2.5 text-slate-500 transition hover:bg-red-50 hover:text-red-600 dark:text-slate-400 dark:hover:bg-red-950/40 dark:hover:text-red-400"
                                    title="Excluir slide"
                                >
                                    <x-icon name="trash" class="h-5 w-5" style="duotone" />
                                    <span class="sr-only">Excluir</span>
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @else
        <div class="rounded-3xl border-2 border-dashed border-gray-200 bg-gradient-to-b from-gray-50/50 to-white px-6 py-16 text-center dark:border-slate-600 dark:from-slate-900/40 dark:to-slate-800/40 md:px-12">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-pink-100 dark:bg-pink-950/50">
                <x-icon name="images" class="h-10 w-10 text-pink-500 dark:text-pink-400" style="duotone" />
            </div>
            <h2 class="mt-6 text-xl font-bold text-gray-900 dark:text-white">Nenhum slide ainda</h2>
            <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                O carrossel está vazio. Crie o primeiro slide para exibir destaques, campanhas ou avisos importantes na página inicial.
            </p>
            <a href="{{ route('diretoria.carousel.create') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-pink-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-pink-500/25 transition hover:bg-pink-700">
                <x-icon name="plus" class="h-5 w-5" style="solid" />
                Criar primeiro slide
            </a>
        </div>
    @endif
</div>

@push('scripts')
@vite(['resources/js/carousel-admin.js'])
<script>
    function confirmDelete(url) {
        if (confirm('Tem certeza que deseja excluir este slide?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection
