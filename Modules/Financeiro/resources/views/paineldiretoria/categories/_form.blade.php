@php
    /** @var \Modules\Financeiro\App\Models\FinCategory $category */
    use Modules\Financeiro\App\Models\FinCategory;
    $in =
        'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400';
    $lb = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
    $groups = [
        FinCategory::GROUP_RECEITAS_OPERACIONAIS => FinCategory::groupLabel(FinCategory::GROUP_RECEITAS_OPERACIONAIS),
        FinCategory::GROUP_RECEITAS_FINANCEIRAS => FinCategory::groupLabel(FinCategory::GROUP_RECEITAS_FINANCEIRAS),
        FinCategory::GROUP_APLICACAO_DIRETA => FinCategory::groupLabel(FinCategory::GROUP_APLICACAO_DIRETA),
        FinCategory::GROUP_DESPESAS_OPERACIONAIS => FinCategory::groupLabel(FinCategory::GROUP_DESPESAS_OPERACIONAIS),
        FinCategory::GROUP_DESPESAS_ADMINISTRATIVAS => FinCategory::groupLabel(
            FinCategory::GROUP_DESPESAS_ADMINISTRATIVAS,
        ),
        FinCategory::GROUP_OUTROS => FinCategory::groupLabel(FinCategory::GROUP_OUTROS),
    ];
@endphp
<div class="grid w-full gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Nome</label>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="{{ $in }}"
            maxlength="120">
        @error('name')
            <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="{{ $lb }}">Código (opcional)</label>
        <input type="text" name="code" value="{{ old('code', $category->code) }}"
            class="{{ $in }} font-mono uppercase" placeholder="EX: REC_DOACOES" @disabled($category->is_system)>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Apenas letras maiúsculas, números e underscore. Usado
            em exportações e integrações.</p>
        @error('code')
            <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="{{ $lb }}">Ordem</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
            max="65535" class="{{ $in }} tabular-nums">
        @error('sort_order')
            <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="{{ $lb }}">Grupo contabilístico</label>
        <select name="group_key" class="{{ $in }}">
            <option value="">—</option>
            @foreach ($groups as $val => $label)
                <option value="{{ $val }}" @selected(old('group_key', $category->group_key) === $val)>{{ $label }}</option>
            @endforeach
        </select>
        @error('group_key')
            <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="{{ $lb }}">Tipo</label>
        <select name="direction" required class="{{ $in }}" @disabled($category->is_system)>
            <option value="in" @selected(old('direction', $category->direction) === 'in')>Receita</option>
            <option value="out" @selected(old('direction', $category->direction) === 'out')>Despesa</option>
        </select>
        @error('direction')
            <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Descrição (opcional)</label>
        <textarea name="description" rows="3" class="{{ $in }} min-h-[5rem]">{{ old('description', $category->description) }}</textarea>
        @error('description')
            <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
        @enderror
    </div>
    <div class="md:col-span-2 flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" id="cat-active"
            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
            @checked(old('is_active', $category->is_active ?? true))>
        <label for="cat-active" class="text-sm font-medium text-gray-800 dark:text-gray-200">Categoria Ativa (aparece em
            lançamentos)</label>
    </div>
</div>
