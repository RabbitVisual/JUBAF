@php
    use App\Support\SiteBranding;
    $taglineRow = collect($configs['branding'] ?? [])->firstWhere('key', SiteBranding::KEY_SITE_TAGLINE);
    $taglineValue = $taglineRow?->value ?? SiteBranding::siteTagline();
@endphp

<div id="branding" class="space-y-8">
    <div class="rounded-xl border border-blue-100 dark:border-blue-900/40 bg-blue-50/40 dark:bg-blue-950/20 p-4 text-sm text-gray-700 dark:text-gray-300">
        <p class="font-medium text-gray-900 dark:text-white mb-1">Logos oficiais JUBAF</p>
        <p>Os arquivos padrão ficam em <code class="text-xs bg-white/80 dark:bg-slate-800 px-1 rounded">public/images/logo/</code>. Ao enviar novos ficheiros, eles passam a ser servidos a partir do storage público até restaurar os padrões.</p>
    </div>

    <form action="{{ route('admin.config.branding.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
            <label for="branding_site_tagline" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slogan / descrição curta</label>
            <textarea id="branding_site_tagline" name="branding_site_tagline" rows="2" maxlength="500"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:text-white">{{ old('branding_site_tagline', $taglineValue) }}</textarea>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Usado em meta descrição e textos auxiliares do site.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                'logo_default' => ['label' => 'Logo padrão (colorido)', 'url' => SiteBranding::logoDefaultUrl()],
                'logo_light' => ['label' => 'Logo claro (fundo escuro)', 'url' => SiteBranding::logoLightUrl()],
                'logo_dark' => ['label' => 'Logo escuro (fundo claro)', 'url' => SiteBranding::logoDarkUrl()],
            ] as $field => $meta)
                <div class="space-y-2 rounded-xl border border-gray-200 dark:border-slate-600 p-4 bg-gray-50/50 dark:bg-slate-800/50">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $meta['label'] }}</p>
                    <div class="flex justify-center rounded-lg bg-white dark:bg-slate-900 p-4 min-h-[100px] items-center border border-gray-100 dark:border-slate-700">
                        @if($meta['url'])
                            <img src="{{ $meta['url'] }}" alt="" class="max-h-20 w-auto object-contain">
                        @endif
                    </div>
                    <input type="file" name="{{ $field }}" accept=".png,.jpg,.jpeg,.webp,.svg,image/png,image/jpeg,image/webp,image/svg+xml"
                           class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                </div>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                <x-icon name="upload" class="w-5 h-5" />
                Guardar slogan e logos
            </button>
        </div>
    </form>

    <form action="{{ route('admin.config.branding.restore') }}" method="POST" onsubmit="return confirm('Restaurar os três logos para os ficheiros oficiais em public/images/logo/?');" class="pt-4 border-t border-gray-200 dark:border-slate-700">
        @csrf
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-slate-700 dark:text-gray-200 dark:border-slate-600 dark:hover:bg-slate-600">
            <x-icon name="rotate-right" class="w-5 h-5" />
            Restaurar logos oficiais (public/images/logo)
        </button>
    </form>
</div>
