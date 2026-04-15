@php
    /** @var array $configsGrouped */
    $driverOptions = [
        'mercadopago' => 'Mercado Pago',
        'stripe' => 'Stripe',
        'pagarme' => 'Pagar.me',
        'cora_parceria' => 'Cora (Parceria API)',
        'cora_mtls' => 'Cora (Integração direta / mTLS)',
    ];
    $items = collect($configsGrouped['gateway'] ?? [])->keyBy('key');
@endphp
<div class="mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Gateway de pagamentos</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-3xl">
        Define o provedor e a moeda por defeito utilizados quando o módulo Gateway cria preferências de pagamento.
        As contas e credenciais de cada PSP são geridas na diretoria em
        @if (Route::has('diretoria.gateway.dashboard'))
            <a href="{{ route('diretoria.gateway.dashboard') }}" class="text-indigo-600 hover:underline dark:text-indigo-400">Pagamentos (PSP)</a>.
        @else
            <span class="font-medium text-gray-700 dark:text-gray-300">Pagamentos (PSP)</span> (módulo Gateway).
        @endif
    </p>
</div>

@if ($items->isEmpty())
    <p class="text-sm text-amber-700 dark:text-amber-300">Guarde uma vez para criar as chaves de gateway na base de dados, ou use «Inicializar padrões».</p>
@else
    <div class="space-y-6">
        @if ($cfg = $items->get('gateway.default_driver'))
            <div class="group">
                <label for="config_gateway_default_driver" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{ $cfg->description ?? $cfg->key }}
                </label>
                <select id="config_gateway_default_driver"
                        name="configs[{{ $cfg->key }}]"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full max-w-md p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                    @foreach ($driverOptions as $key => $label)
                        <option value="{{ $key }}" @selected((string) $cfg->value === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if ($cfg = $items->get('gateway.default_currency'))
            @include('admin::config.partials.config-field', ['config' => $cfg])
        @endif
    </div>
@endif

<div class="mt-8 rounded-lg border border-emerald-200/80 bg-emerald-50/60 p-4 text-sm text-emerald-900 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-100">
    <p class="font-semibold">Documentação dos provedores</p>
    <ul class="mt-2 list-disc list-inside space-y-1 text-emerald-800/90 dark:text-emerald-200/90">
        <li><a href="https://www.mercadopago.com.br/developers/pt/docs" target="_blank" rel="noopener noreferrer" class="underline">Mercado Pago</a></li>
        <li><a href="https://stripe.com/docs" target="_blank" rel="noopener noreferrer" class="underline">Stripe</a></li>
        <li><a href="https://docs.pagar.me/" target="_blank" rel="noopener noreferrer" class="underline">Pagar.me</a></li>
        <li><a href="https://developers.cora.com.br/docs/instrucoes-iniciais" target="_blank" rel="noopener noreferrer" class="underline">Cora</a></li>
    </ul>
</div>
