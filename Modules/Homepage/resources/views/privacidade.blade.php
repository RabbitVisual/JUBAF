@extends('homepage::layouts.homepage')

@section('title')
Política de Privacidade — {{ \App\Support\SiteBranding::siteName() }}
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
                    <x-icon name="shield-check" style="duotone" class="w-4 h-4" />
                    LGPD
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-gray-900 dark:text-white mb-4">
                    Política de Privacidade
                </h1>
                <p class="text-base md:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                    Como o site <strong>{{ $siteName }}</strong> trata dados pessoais, em linha com a Lei nº 13.709/2018 (LGPD).
                </p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 md:p-8 lg:p-10 space-y-8">
                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="file-contract" style="duotone" class="w-6 h-6 text-blue-600" />
                            1. Introdução
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Esta política descreve o tratamento de dados no ecossistema digital da Juventude Batista Feirense (JUBAF),
                                incluindo autenticação, formulários e módulos opcionais (blog, avisos, demandas, programas, entre outros).
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="database" style="duotone" class="w-6 h-6 text-blue-600" />
                            2. Dados coletados
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">Podemos tratar, conforme o uso do sistema:</p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300 ml-4">
                                <li>Identificação e contato (nome, e-mail, telefone, CPF quando exigido por módulo)</li>
                                <li>Dados de cadastros e interações em funcionalidades específicas</li>
                                <li>Logs técnicos (IP, data/hora, navegador) para segurança e auditoria</li>
                            </ul>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="bullseye" style="duotone" class="w-6 h-6 text-blue-600" />
                            3. Finalidades
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300 ml-4">
                                <li>Prestação de serviços solicitados e gestão de conteúdo institucional</li>
                                <li>Comunicação sobre cadastros, demandas ou avisos, quando aplicável</li>
                                <li>Segurança, prevenção a fraudes e cumprimento legal</li>
                                <li>Métricas agregadas para melhoria da plataforma</li>
                            </ul>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="share-nodes" style="duotone" class="w-6 h-6 text-blue-600" />
                            4. Compartilhamento
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Dados não são vendidos. Compartilhamento ocorre apenas para cumprimento legal, ordem judicial,
                                proteção de direitos ou com prestadores de serviço sob obrigação de confidencialidade (hospedagem, e-mail transacional, etc.).
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="lock" style="duotone" class="w-6 h-6 text-blue-600" />
                            5. Segurança
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Adotamos medidas técnicas e administrativas razoáveis: controle de acesso, HTTPS, backups e boas práticas de desenvolvimento.
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="user-shield" style="duotone" class="w-6 h-6 text-blue-600" />
                            6. Seus direitos (LGPD)
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300 ml-4">
                                <li>Confirmação de tratamento e acesso aos dados</li>
                                <li>Correção de dados incompletos ou desatualizados</li>
                                <li>Anonimização, bloqueio ou eliminação quando aplicável</li>
                                <li>Portabilidade e informação sobre compartilhamentos</li>
                                <li>Revogação de consentimento, quando a base for o consentimento</li>
                            </ul>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="envelope" style="duotone" class="w-6 h-6 text-blue-600" />
                            7. Contato e encarregado
                        </h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                                Para exercer direitos ou dúvidas sobre privacidade:
                            </p>
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
                                @if($contactEmail === '' && $contactPhone === '')
                                <li class="text-gray-600 dark:text-gray-400 text-sm">Configure os canais em Admin → Homepage → Contato.</li>
                                @endif
                            </ul>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="rotate" style="duotone" class="w-6 h-6 text-blue-600" />
                            8. Atualizações
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                <strong>Última atualização:</strong> {{ date('d/m/Y') }}
                            </p>
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
