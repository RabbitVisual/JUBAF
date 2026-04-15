@php
    /** @var string $groupKey */
    /** @var array $configsGrouped */
@endphp
<div class="mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $pageTitle ?? ucfirst(str_replace('_', ' ', $groupKey)) }}</h2>
    @if(!empty($pageLead))
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $pageLead }}</p>
    @endif
</div>

@if(isset($configsGrouped[$groupKey]) && count($configsGrouped[$groupKey]))
    <div class="space-y-6">
        @foreach($configsGrouped[$groupKey] as $config)
            @include('admin::config.partials.config-field', ['config' => $config])
        @endforeach
    </div>
@else
    <div class="flex flex-col items-center justify-center py-12 text-center">
        <div class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
            <x-icon name="information-circle" class="w-8 h-8 text-gray-400" />
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Nenhuma configuração aqui</h3>
        <p class="text-gray-500 dark:text-gray-400 mt-1 max-w-sm">Não há configurações disponíveis para esta secção.</p>
    </div>
@endif
