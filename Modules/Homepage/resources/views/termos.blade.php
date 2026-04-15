@extends('homepage::layouts.homepage')

@section('title')
Termos de Uso — {{ \App\Support\SiteBranding::siteName() }}
@endsection

@section('content')
@include('homepage::layouts.navbar-homepage')

@php
    $siteName = \App\Support\SiteBranding::siteName();
    $contactEmail = \App\Models\SystemConfig::get('homepage_email', '');
    $contactPhone = \App\Models\SystemConfig::get('homepage_telefone', '');
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <x-icon name="file-contract" style="duotone" class="w-4 h-4" />
                    Termos e condições
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-gray-900 dark:text-white mb-4">
                    Termos de Uso
                </h1>
                <p class="text-base md:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                    Condições para utilização do site <strong>{{ $siteName }}</strong> e dos serviços digitais associados.
                </p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 md:p-8 lg:p-10 space-y-8">
                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="check-double" style="duotone" class="w-6 h-6 text-blue-600" />
                            1. Aceitação dos termos
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Ao acessar este site e utilizar suas funcionalidades, você concorda com estes Termos de Uso.
                                Se não concordar, interrompa o uso deste site.
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="handshake" style="duotone" class="w-6 h-6 text-blue-600" />
                            2. Finalidade do site
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                                O site <strong>{{ $siteName }}</strong> reúne informações e ferramentas da Juventude Batista Feirense (JUBAF)
                                e de módulos integrados (quando habilitados), como blog, avisos, demandas, programas e áreas públicas.
                            </p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300 ml-4">
                                <li>Consulta de conteúdos institucionais e comunicados</li>
                                <li>Formulários e áreas autenticadas conforme cada módulo</li>
                                <li>Transparência e participação, respeitando a legislação vigente</li>
                            </ul>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="user-check" style="duotone" class="w-6 h-6 text-blue-600" />
                            3. Responsabilidades do usuário
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300 ml-4">
                                <li>Fornecer dados verdadeiros quando solicitado</li>
                                <li>Manter sigilo de senhas e credenciais</li>
                                <li>Não utilizar o sistema para fins ilícitos ou que prejudiquem terceiros</li>
                                <li>Respeitar direitos autorais e a comunidade</li>
                            </ul>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="copyright" style="duotone" class="w-6 h-6 text-blue-600" />
                            4. Propriedade intelectual
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Marcas, textos, layout e integrações são protegidos por lei. O uso indevido pode ser objeto de medidas cabíveis.
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="triangle-exclamation" style="duotone" class="w-6 h-6 text-blue-600" />
                            5. Limitação de responsabilidade
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Os serviços são oferecidos no estado em que se encontram. Não nos responsabilizamos por indisponibilidades
                                temporárias, falhas de rede ou conteúdo de sites externos linkados.
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="pen-to-square" style="duotone" class="w-6 h-6 text-blue-600" />
                            6. Alterações
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                Estes termos podem ser atualizados. A data da última revisão é indicada abaixo.
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mt-4">
                                <strong>Última atualização:</strong> {{ date('d/m/Y') }}
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold font-poppins text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <x-icon name="envelope" style="duotone" class="w-6 h-6 text-blue-600" />
                            7. Contato
                        </h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                                Para dúvidas sobre estes termos, utilize os canais configurados na homepage ou:
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
                                    <div>
                                        <strong>Telefone:</strong>
                                        <span>{{ $contactPhone }}</span>
                                    </div>
                                </li>
                                @endif
                                @if($contactEmail === '' && $contactPhone === '')
                                <li class="text-gray-600 dark:text-gray-400 text-sm">Configure e-mail e telefone em Admin → Homepage → Contato.</li>
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
