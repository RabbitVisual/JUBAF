@extends('homepage::layouts.homepage')

@section('title')
Sobre nós — {{ \App\Support\SiteBranding::siteName() }}
@endsection

@section('content')
@include('homepage::layouts.navbar-homepage')

@php
    $siteName = \App\Support\SiteBranding::siteName();
    $contactEmail = \App\Models\SystemConfig::get('homepage_email', '');
    $contactPhone = \App\Models\SystemConfig::get('homepage_telefone', '');
    $contactAddr = \App\Models\SystemConfig::get('homepage_endereco', '');
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <x-icon name="building-columns" style="duotone" class="w-4 h-4" />
                    Juventude Batista Feirense
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-gray-900 dark:text-white mb-4">
                    Sobre nós
                </h1>
                <p class="text-base md:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                    Conheça a JUBAF e o propósito do site <strong>{{ $siteName }}</strong> — unidade, fé e serviço, no tema <strong>SOMOS UM</strong>.
                </p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 md:p-8 lg:p-10 space-y-8">
                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="bullseye-arrow" style="duotone" class="w-6 h-6 text-blue-600" />
                            Nossa missão
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Proclamar Jesus Cristo, formar discípulos e servir Feira de Santana e região com amor, verdade e comunhão batista.
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="eye" style="duotone" class="w-6 h-6 text-blue-600" />
                            Nossa visão
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Ser referência de juventude batista engajada no Reino de Deus, integrada às igrejas e à ASBAF.
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="scale-balanced" style="duotone" class="w-6 h-6 text-blue-600" />
                            Valores
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300 ml-4">
                                <li><strong>Fé bíblica</strong> — Cristo no centro</li>
                                <li><strong>Comunhão</strong> — acolhimento e cuidado mútuo</li>
                                <li><strong>Missão</strong> — evangelho vivido no dia a dia</li>
                                <li><strong>Transparência</strong> — confiança na comunidade</li>
                            </ul>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="briefcase" style="duotone" class="w-6 h-6 text-blue-600" />
                            O que você encontra aqui
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300 ml-4">
                                <li>Informações institucionais e do tema do ano</li>
                                <li>Integração com módulos do ecossistema (blog, avisos, demandas, programas, portais)</li>
                                <li>Canais de contato e transparência</li>
                            </ul>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="envelope" style="duotone" class="w-6 h-6 text-blue-600" />
                            Contato
                        </h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                            <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                                @if($contactEmail !== '')
                                <li class="flex items-start gap-3">
                                    <x-icon name="envelope" style="duotone" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
                                    <div>
                                        <strong>E-mail:</strong>
                                        <a href="mailto:{{ $contactEmail }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $contactEmail }}</a>
                                    </div>
                                </li>
                                @endif
                                @if($contactPhone !== '')
                                <li class="flex items-start gap-3">
                                    <x-icon name="phone" style="duotone" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
                                    <div><strong>Telefone:</strong> {{ $contactPhone }}</div>
                                </li>
                                @endif
                                @if($contactAddr !== '')
                                <li class="flex items-start gap-3">
                                    <x-icon name="location-dot" style="duotone" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
                                    <div><strong>Endereço:</strong> {{ $contactAddr }}</div>
                                </li>
                                @endif
                                @if($contactEmail === '' && $contactPhone === '' && $contactAddr === '')
                                <li class="text-gray-600 dark:text-gray-400 text-sm">Dados de contato na homepage (Admin → Homepage → Contato).</li>
                                @endif
                            </ul>
                        </div>
                    </section>
                </div>
            </div>

            <div class="text-center mt-8 space-y-4">
                <a href="{{ route('homepage') }}" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                    <x-icon name="arrow-left" style="duotone" class="w-4 h-4" />
                    Voltar para a página inicial
                </a>
            </div>
        </div>
    </div>
</div>

@include('homepage::layouts.footer-homepage')
@endsection
