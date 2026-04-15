@php
    /** @var \Modules\Calendario\App\Models\CalendarEvent $event */
    $in = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400';
    $lb = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
    $tc = $event->theme_config ?? [];
    $discountRule = $discountRule ?? null;
@endphp
<div class="grid w-full gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Título</label>
        <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="{{ $in }}" placeholder="Ex.: CONJUBAF 2026 — sessão plenária">
        @error('title')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Descrição</label>
        <textarea name="description" rows="4" class="{{ $in }} min-h-[6rem]">{{ old('description', $event->description) }}</textarea>
        @error('description')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Início</label>
        <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $event->starts_at?->format('Y-m-d\TH:i')) }}" required class="{{ $in }}">
        @error('starts_at')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Fim (opcional)</label>
        <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $event->ends_at?->format('Y-m-d\TH:i')) }}" class="{{ $in }}">
        @error('ends_at')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2 flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-900/40">
        <input type="hidden" name="all_day" value="0">
        <input type="checkbox" name="all_day" value="1" id="all_day" @checked(old('all_day', $event->all_day)) class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800">
        <label for="all_day" class="text-sm font-medium text-gray-700 dark:text-gray-300">Dia inteiro</label>
    </div>
    <div>
        <label class="{{ $lb }}">Visibilidade</label>
        <select name="visibility" class="{{ $in }}">
            @foreach([
                'publico' => 'Público (site JUBAF)',
                'autenticado' => 'Qualquer utilizador autenticado',
                'diretoria' => 'Painel da diretoria',
                'lideres' => 'Painel de líderes',
                'jovens' => 'Painel de jovens',
            ] as $val => $label)
                <option value="{{ $val }}" @selected(old('visibility', $event->visibility) === $val)>{{ $label }}</option>
            @endforeach
        </select>
        @error('visibility')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Tipo</label>
        <input type="text" name="type" value="{{ old('type', $event->type) }}" class="{{ $in }}" placeholder="evento, assembleia, prazo…">
        @error('type')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Local</label>
        <input type="text" name="location" value="{{ old('location', $event->location) }}" class="{{ $in }}" placeholder="Endereço ou link (ex.: Zoom)">
        @error('location')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    @if($churches->isNotEmpty())
        <div class="md:col-span-2">
            <label class="{{ $lb }}">Igreja (opcional — restringe quem vê no painel jovens/líder)</label>
            <select name="church_id" class="{{ $in }}">
                <option value="">— Todas / regional (JUBAF) —</option>
                @foreach($churches as $c)
                    <option value="{{ $c->id }}" @selected((int) old('church_id', $event->church_id) === (int) $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            @error('church_id')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
        </div>
    @endif
    <div class="md:col-span-2 flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3 dark:border-emerald-900/40 dark:bg-emerald-950/20">
        <input type="hidden" name="registration_open" value="0">
        <input type="checkbox" name="registration_open" value="1" id="reg_open" @checked(old('registration_open', $event->registration_open)) class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800">
        <label for="reg_open" class="text-sm font-medium text-gray-800 dark:text-gray-200">Inscrições abertas (painéis jovens / líderes)</label>
    </div>
    <div>
        <label class="{{ $lb }}">Máx. participantes</label>
        <input type="number" name="max_participants" min="1" value="{{ old('max_participants', $event->max_participants) }}" class="{{ $in }} tabular-nums" placeholder="Vazio = ilimitado">
        @error('max_participants')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Taxa inscrição (R$)</label>
        <input type="number" step="0.01" min="0" name="registration_fee" value="{{ old('registration_fee', $event->registration_fee) }}" class="{{ $in }} tabular-nums" placeholder="Opcional (base, se não usar lotes)">
        @error('registration_fee')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Prazo inscrição</label>
        <input type="datetime-local" name="registration_deadline" value="{{ old('registration_deadline', $event->registration_deadline?->format('Y-m-d\TH:i')) }}" class="{{ $in }}">
        @error('registration_deadline')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Estado editorial</label>
        <select name="status" class="{{ $in }}">
            @foreach([
                'published' => 'Publicado',
                'draft' => 'Rascunho',
                'waiting_approval' => 'Aguarda aprovação',
                'cancelled' => 'Cancelado',
            ] as $val => $label)
                <option value="{{ $val }}" @selected(old('status', $event->status ?? 'published') === $val)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2 flex flex-wrap gap-6 rounded-xl border border-gray-100 bg-gray-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-900/40">
        <label class="flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $event->is_featured)) class="h-4 w-4 rounded border-gray-300 text-emerald-600">
            Destaque na homepage
        </label>
        <label class="flex items-center gap-2 text-sm font-medium text-gray-800 dark:text-gray-200">
            <input type="hidden" name="requires_council_approval" value="0">
            <input type="checkbox" name="requires_council_approval" value="1" @checked(old('requires_council_approval', $event->requires_council_approval)) class="h-4 w-4 rounded border-gray-300 text-emerald-600">
            Exige aprovação da diretoria antes de publicar
        </label>
    </div>
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Capa (imagem)</label>
        <input type="file" name="cover" accept="image/*" class="{{ $in }}">
        @error('cover')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Banner (opcional)</label>
        <input type="file" name="banner" accept="image/*" class="{{ $in }}">
        @error('banner')<p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="{{ $lb }}">Tema público</label>
        <select name="theme" class="{{ $in }}">
            @foreach(['minimal' => 'Minimal', 'corporate' => 'Institucional', 'modern' => 'Moderno'] as $val => $label)
                <option value="{{ $val }}" @selected(old('theme', $tc['theme'] ?? 'corporate') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="{{ $lb }}">Cor primária</label>
        <input type="text" name="primary_color" value="{{ old('primary_color', $tc['primary_color'] ?? '#1e40af') }}" class="{{ $in }}" placeholder="#1e40af">
    </div>
    <div>
        <label class="{{ $lb }}">Cor secundária</label>
        <input type="text" name="secondary_color" value="{{ old('secondary_color', $tc['secondary_color'] ?? '#0f172a') }}" class="{{ $in }}">
    </div>
    <div>
        <label class="{{ $lb }}">Contacto (nome)</label>
        <input type="text" name="contact_name" value="{{ old('contact_name', $event->contact_name) }}" class="{{ $in }}">
    </div>
    <div>
        <label class="{{ $lb }}">E-mail contacto</label>
        <input type="email" name="contact_email" value="{{ old('contact_email', $event->contact_email) }}" class="{{ $in }}">
    </div>
    <div>
        <label class="{{ $lb }}">WhatsApp</label>
        <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $event->contact_whatsapp) }}" class="{{ $in }}">
    </div>
    @if(module_enabled('Blog'))
        <div class="md:col-span-2">
            <label class="{{ $lb }}">Post do blog (ID opcional)</label>
            <input type="number" name="blog_post_id" value="{{ old('blog_post_id', $event->blog_post_id) }}" class="{{ $in }}" placeholder="ID do artigo">
        </div>
    @endif
    @if(module_enabled('Avisos'))
        <div class="md:col-span-2">
            <label class="{{ $lb }}">Aviso ligado (ID opcional)</label>
            <input type="number" name="aviso_id" value="{{ old('aviso_id', $event->aviso_id) }}" class="{{ $in }}">
        </div>
    @endif
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Programa (JSON)</label>
        <textarea name="schedule_json" rows="4" class="{{ $in }} font-mono text-xs" placeholder='[{"time":"09:00","label":"Abertura"}]'>{{ old('schedule_json', $event->schedule ? json_encode($event->schedule, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="{{ $lb }}">Metadata (JSON — dicas, dress code)</label>
        <textarea name="metadata_json" rows="3" class="{{ $in }} font-mono text-xs" placeholder='{"tips":"Levar água"}'>{{ old('metadata_json', $event->metadata ? json_encode($event->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
    </div>
    <div class="md:col-span-2 rounded-xl border border-indigo-100 bg-indigo-50/50 p-4 dark:border-indigo-900/40 dark:bg-indigo-950/20">
        <p class="text-xs font-bold uppercase tracking-wide text-indigo-800 dark:text-indigo-200">Desconto (código promocional)</p>
        <div class="mt-3 grid gap-3 md:grid-cols-2">
            <div>
                <label class="{{ $lb }}">Código</label>
                <input type="text" name="pricing_discount_code" value="{{ old('pricing_discount_code', $discountRule?->config['code'] ?? '') }}" class="{{ $in }}" placeholder="Ex.: JUBAF10">
            </div>
            <div>
                <label class="{{ $lb }}">Percentagem off</label>
                <input type="number" step="0.01" min="0" max="100" name="pricing_discount_percent" value="{{ old('pricing_discount_percent', $discountRule?->config['percent'] ?? '') }}" class="{{ $in }}">
            </div>
        </div>
    </div>
    @if($event->exists)
        @php
            $batchRows = old('batches');
            if ($batchRows === null) {
                $batchRows = $event->batches->isNotEmpty()
                    ? $event->batches->map(function ($row) {
                        return [
                            'id' => $row->id,
                            'name' => $row->name,
                            'price' => $row->price,
                            'sort_order' => $row->sort_order,
                            'sale_ends_at' => $row->sale_ends_at,
                        ];
                    })->values()->all()
                    : [['id' => '', 'name' => '', 'price' => '0', 'sort_order' => 0, 'sale_ends_at' => null]];
            }
        @endphp
        <div class="md:col-span-2 space-y-4 rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-600 dark:bg-slate-900/40">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-700 dark:text-slate-300">Lotes de venda (opcional)</p>
            @foreach($batchRows as $i => $b)
                <div class="grid gap-2 rounded-lg border border-white/80 bg-white p-3 dark:border-slate-700 dark:bg-slate-800 md:grid-cols-6">
                    <input type="hidden" name="batches[{{ $i }}][id]" value="{{ $b['id'] ?? '' }}">
                    <div class="md:col-span-2">
                        <label class="{{ $lb }}">Nome</label>
                        <input type="text" name="batches[{{ $i }}][name]" value="{{ $b['name'] ?? '' }}" class="{{ $in }}" placeholder="Lote 1">
                    </div>
                    <div>
                        <label class="{{ $lb }}">Preço R$</label>
                        <input type="number" step="0.01" min="0" name="batches[{{ $i }}][price]" value="{{ $b['price'] ?? '' }}" class="{{ $in }}">
                    </div>
                    <div>
                        <label class="{{ $lb }}">Ordem</label>
                        <input type="number" name="batches[{{ $i }}][sort_order]" value="{{ $b['sort_order'] ?? 0 }}" class="{{ $in }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="{{ $lb }}">Venda até</label>
                        <input type="datetime-local" name="batches[{{ $i }}][sale_ends_at]" value="{{ ! empty($b['sale_ends_at']) ? \Carbon\Carbon::parse($b['sale_ends_at'])->format('Y-m-d\TH:i') : '' }}" class="{{ $in }}">
                    </div>
                </div>
            @endforeach
            <p class="text-xs text-slate-500">Pode adicionar mais lotes gravando e editando de novo (bloco repetido por lote).</p>
        </div>
    @endif
</div>
