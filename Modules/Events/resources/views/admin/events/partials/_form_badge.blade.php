@php
    $ev = $event ?? null;
    $badge = $ev ? $ev->badges()->first() : null;
@endphp

<div class="space-y-6" x-data="{ hasBadge: {{ $ev && $ev->hasBadgeEnabled() ? 'true' : 'false' }} }">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                <x-icon name="id-card-clip" style="duotone" class="w-6 h-6 text-indigo-600" />
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Design do Crachá</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Configure o layout para impressão dos crachás</p>
            </div>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="options[has_badge]" value="1" class="sr-only peer" x-model="hasBadge" {{ $ev && $ev->hasBadgeEnabled() ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Ativar Crachás</span>
        </label>
    </div>

    <div x-show="hasBadge" x-collapse>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 border border-gray-100 dark:border-gray-700 rounded-xl bg-gray-50/50 dark:bg-gray-800/50">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Orientação do Papel</label>
                    <select name="badge_orientation" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <option value="portrait" {{ $badge && $badge->orientation == 'portrait' ? 'selected' : '' }}>Retrato (Vertical)</option>
                        <option value="landscape" {{ $badge && $badge->orientation == 'landscape' ? 'selected' : '' }}>Paisagem (Horizontal)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tamanho do Papel</label>
                    <select name="badge_paper_size" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <option value="A4" {{ $badge && $badge->paper_size == 'A4' ? 'selected' : '' }}>A4</option>
                        <option value="Letter" {{ $badge && $badge->paper_size == 'Letter' ? 'selected' : '' }}>Carta (Letter)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Crachás por Página</label>
                    <input type="number" name="badge_per_page" value="{{ $badge ? $badge->badges_per_page : 8 }}" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                </div>
            </div>

            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Template HTML (Tailwind)</label>
                <textarea name="badge_template_html" rows="8" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 font-mono text-xs">{{ $badge ? $badge->template_html : '<div class="p-4 border-2 border-indigo-600 rounded-xl text-center">
    <h2 class="text-xl font-bold">{participant_name}</h2>
    <p class="text-indigo-600 font-medium">{church_name}</p>
    <div class="mt-4 flex justify-center">{qr_code}</div>
</div>' }}</textarea>
                <p class="text-[10px] text-gray-500">Tags: {participant_name}, {church_name}, {event_title}, {qr_code}</p>
            </div>
        </div>
    </div>
</div>
