@php
    /** @var array $configsGrouped */
    $items = collect($configsGrouped['recaptcha'] ?? [])->sortBy('key');
@endphp
<div class="mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Google reCAPTCHA v3</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-3xl">
        Protege formulários públicos contra bots. As chaves são gravadas na base de dados e sincronizam com o ficheiro
        <code class="text-xs bg-gray-100 dark:bg-slate-700 px-1 rounded">.env</code> (variáveis
        <code class="text-xs bg-gray-100 dark:bg-slate-700 px-1 rounded">RECAPTCHA_*</code>).
        Crie um site em <a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:underline dark:text-indigo-400">reCAPTCHA Admin</a>
        (tipo v3) e cole a site key e a secret key abaixo.
    </p>
</div>

@if ($items->isEmpty())
    <p class="text-sm text-amber-700 dark:text-amber-300">As chaves de reCAPTCHA ainda não foram inicializadas. Use «Inicializar padrões» no topo ou guarde esta página após preencher.</p>
@else
    <div class="space-y-6">
        @foreach ($items as $config)
            @include('admin::config.partials.config-field', ['config' => $config])
        @endforeach
    </div>
@endif

<div class="mt-8 rounded-lg border border-slate-200 bg-slate-50/80 p-4 text-sm text-slate-600 dark:border-slate-600 dark:bg-slate-900/40 dark:text-slate-300">
    <p class="font-medium text-slate-800 dark:text-slate-100">Impacto</p>
    <p class="mt-1">Com reCAPTCHA desligado, os formulários que o suportam não exigem token. Com reCAPTCHA ligado e chaves em falha, os envios podem falhar — verifique os registos da aplicação após alterações.</p>
</div>
