@if(module_enabled('Igrejas') && isset($igrejasChurches) && $igrejasChurches->isNotEmpty())
    @php
        $selected = old('church_ids', $selectedChurchIds ?? []);
        if (! is_array($selected)) {
            $selected = [];
        }
    @endphp
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Audiência por congregação</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Sem selecção = todas as igrejas. Com selecção = apenas utilizadores vinculados a essas congregações (e diretoria vê sempre tudo na gestão).</p>
        <select name="church_ids[]" multiple size="8"
                class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm">
            @foreach($igrejasChurches as $c)
                <option value="{{ $c->id }}" @selected(in_array((int) $c->id, array_map('intval', $selected), true))>{{ $c->name }}</option>
            @endforeach
        </select>
        @error('church_ids')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        @error('church_ids.*')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
@endif
