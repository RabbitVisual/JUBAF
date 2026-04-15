@php
    /** @var \Modules\Financeiro\App\Models\FinTransaction $transaction */
    $in = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20';
    $lb = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
@endphp
<div class="grid w-full gap-6 md:grid-cols-2">
    <div>
        <label class="{{ $lb }}">Tipo</label>
        <select name="direction" required class="{{ $in }}">
            <option value="in" @selected(old('direction', $transaction->direction) === 'in')>Receita</option>
            <option value="out" @selected(old('direction', $transaction->direction) === 'out')>Despesa</option>
        </select>
        @error('direction')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Categoria</label>
        <select name="category_id" required class="{{ $in }}">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected((int) old('category_id', $transaction->category_id) === (int) $cat->id)>{{ $cat->name }} ({{ $cat->direction === 'in' ? 'receita' : 'despesa' }})</option>
            @endforeach
        </select>
        @error('category_id')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Data</label>
        <input type="date" name="occurred_on" value="{{ old('occurred_on', $transaction->occurred_on?->format('Y-m-d')) }}" required class="{{ $in }}">
        @error('occurred_on')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Valor (R$)</label>
        <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}" required class="{{ $in }} tabular-nums" inputmode="decimal">
        @error('amount')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Âmbito</label>
        <select name="scope" id="tx-scope" required class="{{ $in }}">
            @php
                $scopeVal = old('scope', $transaction->scope === 'nacional' ? 'regional' : $transaction->scope);
            @endphp
            <option value="regional" @selected($scopeVal === 'regional')>Regional (tesouraria JUBAF / campo ASBAF)</option>
            <option value="igreja" @selected($scopeVal === 'igreja')>Por igreja (congregação específica)</option>
        </select>
        <p class="mt-2 text-xs leading-relaxed text-gray-500 dark:text-gray-400">Use <strong>regional</strong> para o livro central da JUBAF; use <strong>por igreja</strong> quando o movimento pertence a uma congregação arrolada.</p>
        @error('scope')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    @if($churches->isNotEmpty())
        <div id="church-wrap" class="md:col-span-2">
            <label class="{{ $lb }}">Igreja</label>
            <select name="church_id" class="{{ $in }}">
                <option value="">—</option>
                @foreach($churches as $c)
                    <option value="{{ $c->id }}" @selected((int) old('church_id', $transaction->church_id) === (int) $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            @error('church_id')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
        </div>
    @endif
    <div>
        <label class="{{ $lb }}">Referência interna</label>
        <input type="text" name="reference" value="{{ old('reference', $transaction->reference) }}" class="{{ $in }}" placeholder="REQ, processo interno…">
        @error('reference')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Documento fiscal (NF / recibo)</label>
        <input type="text" name="document_ref" value="{{ old('document_ref', $transaction->document_ref) }}" class="{{ $in }}" placeholder="Nº nota, recibo…">
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Para conferência com contabilidade e assembleia.</p>
        @error('document_ref')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Descrição</label>
        <textarea name="description" rows="3" class="{{ $in }} min-h-[5.5rem]">{{ old('description', $transaction->description) }}</textarea>
        @error('description')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    @if(isset($minutes) && $minutes->isNotEmpty())
        <div class="md:col-span-2">
            <label class="{{ $lb }}">Ata (Secretaria) — evidência</label>
            <select name="secretaria_minute_id" class="{{ $in }}">
                <option value="">— Nenhuma —</option>
                @foreach($minutes as $m)
                    <option value="{{ $m->id }}" @selected((int) old('secretaria_minute_id', $transaction->secretaria_minute_id) === (int) $m->id)>
                        @if($m->protocol_number){{ $m->protocol_number }} — @endif{{ \Illuminate\Support\Str::limit($m->title, 80) }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Vincula o lançamento a uma ata publicada ou arquivada (auditoria).</p>
            @error('secretaria_minute_id')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
        </div>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scope = document.getElementById('tx-scope');
        const wrap = document.getElementById('church-wrap');
        if (!scope || !wrap) return;
        function sync() {
            wrap.style.display = scope.value === 'igreja' ? 'block' : 'none';
            if (scope.value !== 'igreja') {
                const sel = wrap.querySelector('select[name="church_id"]');
                if (sel) sel.value = '';
            }
        }
        scope.addEventListener('change', sync);
        sync();
    });
</script>
