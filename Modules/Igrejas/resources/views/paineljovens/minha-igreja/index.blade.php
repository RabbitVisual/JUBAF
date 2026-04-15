@extends('paineljovens::components.layouts.app')

@section('title', 'A minha igreja')

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <span class="text-violet-700 dark:text-violet-300">Minha igreja</span>
@endsection

@section('content')
<div class="space-y-8 max-w-4xl">
    <div class="relative overflow-hidden rounded-[2rem] border border-violet-200/90 dark:border-violet-900/50 bg-gradient-to-br from-violet-600 via-fuchsia-600 to-slate-900 text-white shadow-2xl shadow-violet-900/25">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.12\'%3E%3Cpath d=\'M20 20h20v20H20zM0 0h20v20H0z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="relative px-6 py-8 md:px-10 md:py-10">
            <p class="text-xs font-bold uppercase tracking-widest text-violet-100/90 mb-2">Unijovem · JUBAF</p>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight flex items-center gap-3">
                <span class="flex h-12 w-12 rounded-2xl bg-white/15 items-center justify-center shrink-0">
                    <x-module-icon module="Igrejas" class="h-7 w-7 text-white" />
                </span>
                A tua igreja na JUBAF
            </h1>
            <p class="mt-3 text-sm md:text-base text-violet-50/95 max-w-xl leading-relaxed">
                Informações oficiais da congregação onde estás inscrito como jovem — contactos para atividades locais e apoio do líder.
            </p>
        </div>
    </div>

    @if($church)
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Jovens na plataforma</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $church->jovens_members_count }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Líderes registados</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $church->leaders_count }}</p>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 overflow-hidden shadow-sm">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 bg-gradient-to-r from-violet-50/80 to-fuchsia-50/50 dark:from-violet-950/40 dark:to-fuchsia-950/20">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $church->name }}</h2>
                @if($church->city)
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 flex items-center gap-2">
                        <x-icon name="location-dot" class="w-4 h-4 text-violet-500" />
                        {{ $church->city }}
                    </p>
                @endif
                <div class="mt-3">
                    @if($church->is_active)
                        <span class="inline-flex text-xs font-bold px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200">Congregação ativa na JUBAF</span>
                    @else
                        <span class="inline-flex text-xs font-bold px-2.5 py-1 rounded-lg bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-200">Estado a regularizar</span>
                    @endif
                </div>
            </div>
            <div class="p-6 md:p-8 space-y-5 text-sm">
                @if($church->address)
                    <div class="flex gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300"><x-icon name="map" class="w-5 h-5" /></span>
                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Endereço</p>
                            <p class="mt-1 text-slate-800 dark:text-slate-100 leading-relaxed">{{ $church->address }}</p>
                        </div>
                    </div>
                @endif
                @if($church->phone)
                    <div class="flex gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300"><x-icon name="phone" class="w-5 h-5" /></span>
                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Telefone</p>
                            <p class="mt-1"><a href="tel:{{ preg_replace('/\s+/', '', $church->phone) }}" class="font-semibold text-violet-700 dark:text-violet-400 hover:underline text-base">{{ $church->phone }}</a></p>
                        </div>
                    </div>
                @endif
                @if($church->email)
                    <div class="flex gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300"><x-icon name="envelope" class="w-5 h-5" /></span>
                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">E-mail</p>
                            <p class="mt-1"><a href="mailto:{{ $church->email }}" class="font-semibold text-violet-700 dark:text-violet-400 hover:underline break-all">{{ $church->email }}</a></p>
                        </div>
                    </div>
                @endif
                @if($church->joined_at)
                    <p class="text-xs text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-slate-700">Filiação JUBAF: <strong class="text-slate-700 dark:text-slate-200">{{ $church->joined_at->format('d/m/Y') }}</strong></p>
                @endif
            </div>
        </div>

        @if($leaders->isNotEmpty())
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-3">Os teus líderes de jovens</h3>
                <ul class="space-y-3">
                    @foreach($leaders as $lider)
                        <li class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-11 w-11 rounded-xl bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-300 items-center justify-center font-bold text-lg">{{ mb_substr($lider->name, 0, 1) }}</span>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $lider->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Líder JUBAF</p>
                                </div>
                            </div>
                            <div class="text-sm space-y-1 sm:text-right">
                                @if($lider->email)<a href="mailto:{{ $lider->email }}" class="block text-violet-600 dark:text-violet-400 font-medium hover:underline">{{ $lider->email }}</a>@endif
                                @if($lider->phone)<a href="tel:{{ preg_replace('/\s+/', '', $lider->phone) }}" class="block text-slate-600 dark:text-slate-400 hover:text-violet-600">{{ $lider->phone }}</a>@endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(Route::has('igrejas.public.index'))
            <p class="text-center text-sm text-slate-500 dark:text-slate-400">
                <a href="{{ route('igrejas.public.index') }}" target="_blank" rel="noopener" class="text-violet-600 dark:text-violet-400 font-semibold hover:underline">Ver listagem pública de congregações JUBAF</a>
            </p>
        @endif
    @else
        <div class="rounded-2xl border-2 border-dashed border-violet-300 dark:border-violet-800 bg-violet-50/80 dark:bg-violet-950/30 p-8 text-center">
            <x-icon name="church" class="w-12 h-12 text-violet-500 dark:text-violet-400 mx-auto mb-4" />
            <h2 class="text-lg font-bold text-violet-900 dark:text-violet-100">Ainda sem igreja associada</h2>
            <p class="text-sm text-violet-900/80 dark:text-violet-100/80 mt-2 max-w-md mx-auto leading-relaxed">
                Fala com o líder da tua congregação ou com a secretaria JUBAF para associares a tua conta Unijovem à igreja correta.
            </p>
            <a href="{{ route('jovens.profile.index') }}" class="inline-flex mt-6 px-5 py-2.5 rounded-xl bg-violet-600 text-white text-sm font-semibold hover:bg-violet-700">Ir ao perfil</a>
        </div>
    @endif
</div>
@endsection
