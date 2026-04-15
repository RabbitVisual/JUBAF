<footer class="bg-white dark:bg-gradient-to-br dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 text-gray-600 dark:text-gray-300 mt-20 border-t border-gray-200 dark:border-slate-700">
    <div class="container mx-auto px-4 py-12">
        <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8">
            <div class="space-y-4">
                <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-12 mb-4 w-auto max-w-[200px] object-contain dark:hidden">
                <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-12 mb-4 w-auto max-w-[200px] object-contain hidden dark:block">
                <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">
                    @php
                        $footerDescricao = \App\Models\SystemConfig::get('homepage_footer_descricao', 'Juventude Batista Feirense — caminhando juntos no tema SOMOS UM.');
                    @endphp
                    {{ $footerDescricao }}
                </p>
                <div class="flex gap-4 pt-2">
                    @php
                        $facebookUrl = \App\Models\SystemConfig::get('homepage_footer_facebook_url', '');
                        $instagramUrl = \App\Models\SystemConfig::get('homepage_footer_instagram_url', '');
                        $whatsapp = \App\Models\SystemConfig::get('homepage_footer_whatsapp', '');
                    @endphp
                    @if($facebookUrl)
                    <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-gray-200 dark:bg-slate-700 hover:bg-blue-600 dark:hover:bg-blue-600 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg text-gray-700 dark:text-gray-300" aria-label="Facebook">
                        <x-icon name="facebook" style="brands" class="w-5 h-5" />
                    </a>
                    @endif
                    @if($instagramUrl)
                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-gray-200 dark:bg-slate-700 hover:bg-blue-600 dark:hover:bg-blue-600 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg text-gray-700 dark:text-gray-300" aria-label="Instagram">
                        <x-icon name="instagram" style="brands" class="w-5 h-5" />
                    </a>
                    @endif
                    @if($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-gray-200 dark:bg-slate-700 hover:bg-blue-600 dark:hover:bg-blue-600 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg text-gray-700 dark:text-gray-300" aria-label="WhatsApp">
                        <x-icon name="whatsapp" style="brands" class="w-5 h-5" />
                    </a>
                    @endif
                </div>
            </div>

            <div>
                <h5 class="text-gray-900 dark:text-white font-bold text-lg mb-4 flex items-center gap-2">
                    <x-icon name="link" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    Links Úteis
                </h5>
                <ul class="space-y-3">
                    <li><a href="#inicio" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="house" style="duotone" class="w-4 h-4" />
                        Início
                    </a></li>
                    <li><a href="#servicos" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="grid-2" style="duotone" class="w-4 h-4" />
                        Atividades
                    </a></li>
                    <li><a href="{{ route('homepage') }}#servicos-publicos" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="globe" style="duotone" class="w-4 h-4" />
                        Acesso rápido
                    </a></li>
                    <li><a href="{{ route('contato') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="paper-plane" style="duotone" class="w-4 h-4" />
                        Contato
                    </a></li>
                    @if((bool) \App\Models\SystemConfig::get('homepage_navbar_diretoria_enabled', true) && (bool) \App\Models\SystemConfig::get('homepage_public_diretoria_enabled', true) && \Illuminate\Support\Facades\Route::has('homepage.diretoria'))
                    <li><a href="{{ route('homepage.diretoria') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="users" style="duotone" class="w-4 h-4" />
                        Diretoria
                    </a></li>
                    @endif
                    @if((bool) \App\Models\SystemConfig::get('homepage_navbar_devotionals_enabled', true) && (bool) \App\Models\SystemConfig::get('homepage_public_devotionals_enabled', true) && \Illuminate\Support\Facades\Route::has('devocionais.index'))
                    <li><a href="{{ route('devocionais.index') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="book-open" style="duotone" class="w-4 h-4" />
                        Devocionais
                    </a></li>
                    @endif
                    @if((bool) \App\Models\SystemConfig::get('homepage_navbar_radio_enabled', true) && (bool) \App\Models\SystemConfig::get('homepage_public_radio_enabled', true) && \Illuminate\Support\Facades\Route::has('radio'))
                    <li><a href="{{ route('radio') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="tower-broadcast" style="duotone" class="w-4 h-4" />
                        Rádio 3:16
                    </a></li>
                    @endif
                    @if(module_enabled('Calendario') && \Illuminate\Support\Facades\Route::has('eventos.index'))
                    <li><a href="{{ route('eventos.index') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-teal-700 dark:hover:text-teal-400 transition-colors">
                        <x-icon name="calendar-days" style="duotone" class="w-4 h-4" />
                        Eventos
                    </a></li>
                    @endif
                    @php
                        $siteExterno = \App\Models\SystemConfig::get('homepage_footer_site_prefeitura', '');
                        $siteExternoLabel = \App\Models\SystemConfig::get('homepage_footer_external_link_label', 'Site institucional');
                    @endphp
                    @if($siteExterno)
                    <li><a href="{{ $siteExterno }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="building-columns" style="duotone" class="w-4 h-4" />
                        {{ $siteExternoLabel }}
                    </a></li>
                    @endif
                </ul>
            </div>

            <div>
                <h5 class="text-gray-900 dark:text-white font-bold text-lg mb-4 flex items-center gap-2">
                    <x-icon name="scale-balanced" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    Informações Legais
                </h5>
                <ul class="space-y-3">
                    <li><a href="{{ route('termos') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="file-contract" style="duotone" class="w-4 h-4" />
                        Termos de Uso
                    </a></li>
                    <li><a href="{{ route('privacidade') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="shield-halved" style="duotone" class="w-4 h-4" />
                        Política de Privacidade
                    </a></li>
                    <li><a href="{{ route('sobre') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="circle-info" style="duotone" class="w-4 h-4" />
                        Sobre Nós
                    </a></li>
                    <li><a href="{{ route('desenvolvedor') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        <x-icon name="user-gear" style="duotone" class="w-4 h-4" />
                        Desenvolvedor
                    </a></li>
                </ul>
            </div>

            <div>
                <h5 class="text-gray-900 dark:text-white font-bold text-lg mb-4 flex items-center gap-2">
                    <x-icon name="heart" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    JUBAF
                </h5>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2 text-gray-600 dark:text-gray-400 text-sm">
                        <x-icon name="check-circle" style="duotone" class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" />
                        <span>Tema: SOMOS UM</span>
                    </li>
                    <li class="flex items-start gap-2 text-gray-600 dark:text-gray-400 text-sm">
                        <x-icon name="check-circle" style="duotone" class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" />
                        <span>Juventude Batista Feirense</span>
                    </li>
                    <li class="flex items-start gap-2 text-gray-600 dark:text-gray-400 text-sm">
                        <x-icon name="check-circle" style="duotone" class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" />
                        <span>Unidade com a ASBAF</span>
                    </li>
                    <li class="flex items-start gap-2 text-gray-600 dark:text-gray-400 text-sm">
                        <x-icon name="check-circle" style="duotone" class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" />
                        <span>Fé, comunhão e missão</span>
                    </li>
                </ul>
            </div>

            <div>
                <h5 class="text-gray-900 dark:text-white font-bold text-lg mb-4 flex items-center gap-2">
                    <x-icon name="headset" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    Contato
                </h5>
                <ul class="space-y-4">
                    @php
                        $email = \App\Models\SystemConfig::get('homepage_email', '');
                        $telefone = \App\Models\SystemConfig::get('homepage_telefone', '');
                        $endereco = \App\Models\SystemConfig::get('homepage_endereco', '');
                    @endphp
                    @if($email)
                    <li class="flex items-start gap-3">
                        <x-icon name="envelope" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                        <a href="mailto:{{ $email }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors break-all text-sm">
                            {{ $email }}
                        </a>
                    </li>
                    @endif
                    @if($telefone)
                    <li class="flex items-start gap-3">
                        <x-icon name="phone-volume" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $telefone) }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors text-sm">
                            {{ $telefone }}
                        </a>
                    </li>
                    @endif
                    @if($endereco)
                    <li class="flex items-start gap-3">
                        <x-icon name="map-location-dot" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                        <span class="text-gray-600 dark:text-gray-300 text-sm">{{ $endereco }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-200 dark:border-slate-700">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col gap-4">
                @php
                    $footerOrg = \App\Models\SystemConfig::get('homepage_footer_org_line', 'JUBAF — Juventude Batista Feirense');
                @endphp
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <p class="flex items-center gap-2 text-center">
                        <x-icon name="copyright" style="duotone" class="w-4 h-4" />
                        {{ date('Y') }} {{ $footerOrg }}. Todos os direitos reservados.
                    </p>
                    <p class="text-center md:text-right">
                        {{ \App\Support\SiteBranding::siteTagline() }}
                    </p>
                </div>
                @if((bool) \App\Models\SystemConfig::get('homepage_footer_credit_visible', false))
                <div class="flex flex-col md:flex-row justify-center items-center gap-2 pt-4 border-t border-gray-200 dark:border-slate-700 text-xs text-gray-400 dark:text-gray-500">
                    @php
                        $creditOrg = \App\Models\SystemConfig::get('homepage_footer_credit_organization', \App\Models\SystemConfig::get('homepage_footer_vertex_company', ''));
                        $creditName = \App\Models\SystemConfig::get('homepage_footer_credit_contact_name', \App\Models\SystemConfig::get('homepage_footer_vertex_ceo', ''));
                        $creditEmail = \App\Models\SystemConfig::get('homepage_footer_credit_email', \App\Models\SystemConfig::get('homepage_footer_vertex_email', ''));
                        $creditPhone = \App\Models\SystemConfig::get('homepage_footer_credit_phone', \App\Models\SystemConfig::get('homepage_footer_vertex_phone', ''));
                    @endphp
                    @if($creditOrg !== '')
                    <p class="text-center">
                        © {{ date('Y') }} <span class="text-gray-600 dark:text-gray-400 font-semibold">{{ $creditOrg }}</span>
                    </p>
                    @endif
                    @if($creditName !== '')
                    <span class="hidden md:inline">•</span>
                    <p class="text-center">
                        <span class="text-gray-600 dark:text-gray-400 font-semibold">{{ $creditName }}</span>
                    </p>
                    @endif
                    @if($creditEmail !== '')
                    <span class="hidden md:inline">•</span>
                    <p class="text-center">
                        <a href="mailto:{{ $creditEmail }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $creditEmail }}</a>
                    </p>
                    @endif
                    @if($creditPhone !== '')
                    <span class="hidden md:inline">•</span>
                    <p class="text-center">
                        <a href="tel:{{ $creditPhone }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $creditPhone }}</a>
                    </p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</footer>
