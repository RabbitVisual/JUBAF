@extends('paineljovens::layouts.jovens')

@section('title', 'A minha igreja')


@section('jovens_content')
    <x-ui.jovens::page-shell class="max-w-4xl space-y-8">
        <x-ui.jovens::hero
            title="A tua igreja na JUBAF"
            description="Informações oficiais da congregação onde estás inscrito como jovem — contactos para atividades locais e apoio do líder."
            eyebrow="Unijovem · Congregação" />

        @if ($church)
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Jovens na plataforma</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $church->jovens_members_count }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Líderes registados</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $church->leaders_count }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 bg-gray-50 px-6 py-5 dark:border-gray-700 dark:bg-gray-900/50">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $church->name }}</h2>
                    @if ($church->uuid)
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Identificador interno: <code class="rounded bg-gray-200/80 px-1.5 py-0.5 font-mono text-gray-700 dark:bg-gray-700 dark:text-gray-300">{{ $church->uuid }}</code></p>
                    @endif
                    @if ($church->city)
                        <p class="mt-1 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <x-icon name="location-dot" class="h-4 w-4 text-blue-500" />
                            {{ $church->city }}
                        </p>
                    @endif
                    <div class="mt-3">
                        @if ($church->is_active)
                            <span class="inline-flex rounded-lg bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200">Congregação ativa na JUBAF</span>
                        @else
                            <span class="inline-flex rounded-lg bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-900 dark:bg-amber-900/40 dark:text-amber-200">Estado a regularizar</span>
                        @endif
                    </div>
                </div>
                <div class="space-y-5 p-6 text-sm md:p-8">
                    @if ($church->address)
                        <div class="flex gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300"><x-icon name="map" class="h-5 w-5" /></span>
                            <div>
                                <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Endereço</p>
                                <p class="mt-1 leading-relaxed text-gray-800 dark:text-gray-100">{{ $church->address }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($church->phone)
                        <div class="flex gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300"><x-icon name="phone" class="h-5 w-5" /></span>
                            <div>
                                <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Telefone</p>
                                <p class="mt-1"><a href="tel:{{ preg_replace('/\s+/', '', $church->phone) }}" class="text-base font-semibold text-blue-600 hover:underline dark:text-blue-400">{{ $church->phone }}</a></p>
                            </div>
                        </div>
                    @endif
                    @if ($church->email)
                        <div class="flex gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300"><x-icon name="envelope" class="h-5 w-5" /></span>
                            <div>
                                <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">E-mail</p>
                                <p class="mt-1"><a href="mailto:{{ $church->email }}" class="break-all font-semibold text-blue-600 hover:underline dark:text-blue-400">{{ $church->email }}</a></p>
                            </div>
                        </div>
                    @endif
                    @if ($church->joined_at)
                        <p class="border-t border-gray-100 pt-2 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400">Filiação JUBAF: <strong class="text-gray-700 dark:text-gray-200">{{ $church->joined_at->format('d/m/Y') }}</strong></p>
                    @endif
                </div>
            </div>

            @if ($leaders->isNotEmpty())
                <div>
                    <h3 class="mb-3 text-base font-semibold text-gray-900 dark:text-white">Os teus líderes de jovens</h3>
                    <ul class="space-y-3">
                        @foreach ($leaders as $lider)
                            <li class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-100 text-lg font-bold text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">{{ mb_substr($lider->name, 0, 1) }}</span>
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white">{{ $lider->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Líder JUBAF</p>
                                    </div>
                                </div>
                                <div class="space-y-1 text-sm sm:text-right">
                                    @if ($lider->email)
                                        <a href="mailto:{{ $lider->email }}" class="block font-medium text-blue-600 hover:underline dark:text-blue-400">{{ $lider->email }}</a>
                                    @endif
                                    @if ($lider->phone)
                                        <a href="tel:{{ preg_replace('/\s+/', '', $lider->phone) }}" class="block text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400">{{ $lider->phone }}</a>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (Route::has('igrejas.public.index'))
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ route('igrejas.public.index') }}" target="_blank" rel="noopener" class="font-semibold text-blue-600 hover:underline dark:text-blue-400">Ver listagem pública de congregações JUBAF</a>
                </p>
            @endif
        @else
            <div class="rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-8 text-center dark:border-gray-600 dark:bg-gray-900/40">
                <x-icon name="church" class="mx-auto mb-4 h-12 w-12 text-gray-400" />
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ainda sem igreja associada</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                    Fala com o líder da tua congregação ou com a secretaria JUBAF para associares a tua conta Unijovem à igreja correta.
                </p>
                <a href="{{ route('jovens.profile.index') }}" class="mt-6 inline-flex rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Ir ao perfil</a>
            </div>
        @endif
    </x-ui.jovens::page-shell>
@endsection
