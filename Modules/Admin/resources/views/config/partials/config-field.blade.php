@props(['config'])
<div class="group">
    <label for="config_{{ $config->key }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
        {{ $config->description ?? $config->key }}
        @if($config->key === 'google_maps.api_key')
            <span class="ml-2 text-xs text-amber-500 bg-amber-50 dark:bg-amber-900/30 px-2 py-0.5 rounded border border-amber-200 dark:border-amber-800">Requerido</span>
        @endif
    </label>

    @if($config->type === 'boolean')
        <div class="flex items-center">
            <input type="hidden" name="configs[{{ $config->key }}]" value="0">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="configs[{{ $config->key }}]" value="1" class="sr-only peer" {{ $config->value == '1' ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300" x-text="{{ $config->value == '1' ? 'true' : 'false' }} ? 'Ativado' : 'Desativado'"></span>
            </label>
        </div>
    @elseif($config->type === 'password')
        <div class="relative" x-data="{ show: false }">
            <input :type="show ? 'text' : 'password'"
                   id="config_{{ $config->key }}"
                   name="configs[{{ $config->key }}]"
                   value="{{ $config->value }}"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 pr-10 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500 transition-shadow hover:shadow-sm">
            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 cursor-pointer">
                <x-icon x-show="!show" name="eye" class="w-5 h-5" />
                <x-icon x-show="show" name="eye-slash" class="w-5 h-5" style="display: none;" />
            </button>
        </div>
        @if($config->key === 'google_maps.api_key')
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Obtenha sua chave no <a href="https://console.cloud.google.com/" target="_blank" class="text-indigo-600 hover:underline dark:text-indigo-400">Google Cloud Console</a>. Habilite "Maps JavaScript API".</p>
        @endif
    @elseif($config->type === 'text')
        <textarea id="config_{{ $config->key }}"
                  name="configs[{{ $config->key }}]"
                  rows="3"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500 transition-shadow hover:shadow-sm">{{ $config->value }}</textarea>
    @elseif($config->type === 'integer')
        <input type="number"
               id="config_{{ $config->key }}"
               name="configs[{{ $config->key }}]"
               value="{{ $config->value }}"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500 transition-shadow hover:shadow-sm">
    @else
        <input type="text"
               id="config_{{ $config->key }}"
               name="configs[{{ $config->key }}]"
               value="{{ $config->value }}"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500 transition-shadow hover:shadow-sm">
    @endif
</div>
