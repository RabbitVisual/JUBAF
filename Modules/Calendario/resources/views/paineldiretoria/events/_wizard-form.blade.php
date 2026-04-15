@php
    $in = 'w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400';
    $lb = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400';
    $hint = 'mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400';
    $tc = $event->theme_config ?? [];
    $discountRule = $discountRule ?? null;
    $editMode = $event->exists;

    $scheduleItems = old('schedule_items');
    if ($scheduleItems === null) {
        if ($event->schedule && count($event->schedule)) {
            $scheduleItems = array_values(array_map(function ($s) {
                return ['time' => $s['time'] ?? '', 'label' => $s['label'] ?? ($s['title'] ?? '')];
            }, $event->schedule));
        } else {
            $scheduleItems = [['time' => '', 'label' => '']];
        }
    }
    $metaTips = old('meta_tips', data_get($event->metadata, 'tips'));
    $metaDress = old('meta_dress_code', data_get($event->metadata, 'dress_code'));

    $batchRowsInitial = old('batches');
    if ($batchRowsInitial === null) {
        $batchRowsInitial = $editMode && $event->batches->isNotEmpty()
            ? $event->batches->map(function ($row) {
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'price' => $row->price,
                    'sort_order' => $row->sort_order,
                    'sale_ends_at' => $row->sale_ends_at?->format('Y-m-d\TH:i'),
                ];
            })->values()->all()
            : [['id' => '', 'name' => '', 'price' => '0', 'sort_order' => 0, 'sale_ends_at' => '']];
    }

    $typePresets = [
        'evento' => 'Evento geral',
        'reuniao' => 'Reunião',
        'culto_especial' => 'Culto especial',
        'campanha' => 'Campanha / oferta',
        'formacao' => 'Formação',
        'assembleia' => 'Assembleia',
    ];
    $currentType = old('type', $event->type ?? 'evento');
    $typeIsPreset = array_key_exists($currentType, $typePresets);
@endphp

<div
    class="space-y-6"
    x-data="calEventWizard({
        scheduleRows: @js($scheduleItems),
        batchRows: @js($batchRowsInitial),
    })"
>
    <nav class="rounded-2xl border border-slate-200/90 bg-white p-3 shadow-sm dark:border-slate-700 dark:bg-slate-800" aria-label="Passos">
        <ol class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-stretch sm:justify-between sm:gap-2">
            @foreach([
                1 => ['O quê e quando', 'calendar-day'],
                2 => ['Inscrições e valores', 'ticket'],
                3 => ['Aparência pública', 'image'],
                4 => ['Programa e contactos', 'list-ol'],
            ] as $num => $meta)
                <li class="min-w-0 flex-1">
                    <button type="button" @click="step = {{ $num }}"
                        :class="step === {{ $num }}
                            ? 'border-emerald-500 bg-emerald-50 ring-2 ring-emerald-500/20 dark:border-emerald-600 dark:bg-emerald-950/40'
                            : 'border-slate-200 bg-slate-50 hover:border-emerald-200 dark:border-slate-600 dark:bg-slate-900'"
                        class="flex w-full items-center gap-2 rounded-xl border px-3 py-2.5 text-left transition dark:text-slate-100">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs font-black"
                            :class="step === {{ $num }} ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200'">{{ $num }}</span>
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-bold">{{ $meta[0] }}</span>
                        </span>
                    </button>
                </li>
            @endforeach
        </ol>
    </nav>

    {{-- 1 --}}
    <div x-show="step === 1" x-cloak class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="border-b border-slate-100 bg-linear-to-r from-emerald-50/80 to-white px-6 py-4 dark:border-slate-700 dark:from-emerald-950/30 dark:to-slate-800">
            <h2 class="text-base font-bold text-slate-900 dark:text-white">Informações principais</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Título, datas e quem pode ver o evento na agenda.</p>
        </div>
        <div class="grid gap-6 p-6 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="{{ $lb }}">Título <span class="text-rose-600">*</span></label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="{{ $in }}" placeholder="Ex.: CONJUBAF 2026">
                @error('title')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="{{ $lb }}">Descrição</label>
                <textarea name="description" rows="4" class="{{ $in }} min-h-[6rem]">{{ old('description', $event->description) }}</textarea>
            </div>
            <div>
                <label class="{{ $lb }}">Início <span class="text-rose-600">*</span></label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $event->starts_at?->format('Y-m-d\TH:i')) }}" required class="{{ $in }}">
                @error('starts_at')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $lb }}">Fim (opcional)</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $event->ends_at?->format('Y-m-d\TH:i')) }}" class="{{ $in }}">
            </div>
            <div class="md:col-span-2 flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-900/40">
                <input type="hidden" name="all_day" value="0">
                <input type="checkbox" name="all_day" value="1" id="all_day" @checked(old('all_day', $event->all_day)) class="h-4 w-4 rounded border-slate-300 text-emerald-600">
                <label for="all_day" class="text-sm font-medium text-slate-800 dark:text-slate-200">Dia inteiro</label>
            </div>
            <div>
                <label class="{{ $lb }}">Tipo</label>
                <select name="type" class="{{ $in }}">
                    @foreach($typePresets as $val => $label)
                        <option value="{{ $val }}" @selected($currentType === $val)>{{ $label }}</option>
                    @endforeach
                    @if(! $typeIsPreset && filled($currentType))
                        <option value="{{ $currentType }}" selected>Outro: {{ $currentType }}</option>
                    @endif
                </select>
            </div>
            <div>
                <label class="{{ $lb }}">Estado editorial</label>
                <select name="status" class="{{ $in }}">
                    @foreach(['published' => 'Publicado', 'draft' => 'Rascunho', 'waiting_approval' => 'Aguarda aprovação', 'cancelled' => 'Cancelado'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $event->status ?? 'published') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $lb }}">Visibilidade</label>
                <select name="visibility" class="{{ $in }}">
                    @foreach([
                        'publico' => 'Página pública do site',
                        'autenticado' => 'Só utilizadores com login',
                        'jovens' => 'Painel de jovens',
                        'lideres' => 'Painel de líderes',
                        'diretoria' => 'Só diretoria',
                    ] as $val => $label)
                        <option value="{{ $val }}" @selected(old('visibility', $event->visibility) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="{{ $hint }}">“Página pública” lista o evento em /eventos quando estiver publicado.</p>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $lb }}">Local ou link</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}" class="{{ $in }}" placeholder="Morada, sala, Zoom…">
            </div>
            @if($churches->isNotEmpty())
                <div class="md:col-span-2">
                    <label class="{{ $lb }}">Igreja (opcional)</label>
                    <select name="church_id" class="{{ $in }}">
                        <option value="">Toda a JUBAF</option>
                        @foreach($churches as $c)
                            <option value="{{ $c->id }}" @selected((int) old('church_id', $event->church_id) === (int) $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    {{-- 2 --}}
    <div x-show="step === 2" x-cloak class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="border-b border-slate-100 bg-linear-to-r from-sky-50/80 to-white px-6 py-4 dark:border-slate-700 dark:from-sky-950/25 dark:to-slate-800">
            <h2 class="text-base font-bold text-slate-900 dark:text-white">Inscrições e valores</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Ligue as inscrições só se precisar de lista ou pagamento.</p>
        </div>
        <div class="space-y-6 p-6">
            <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 px-4 py-3 dark:border-emerald-900/40 dark:bg-emerald-950/20">
                <div class="flex flex-wrap items-start gap-3">
                    <input type="hidden" name="registration_open" value="0">
                    <input type="checkbox" name="registration_open" value="1" id="reg_open" @checked(old('registration_open', $event->registration_open)) class="mt-1 h-4 w-4 rounded text-emerald-600">
                    <div>
                        <label for="reg_open" class="text-sm font-bold text-emerald-900 dark:text-emerald-100">Aceitar inscrições online</label>
                        <p class="{{ $hint }}">Jovens e líderes inscrevem-se nos painéis. Desligado = só divulgação.</p>
                    </div>
                </div>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="{{ $lb }}">Valor base (R$)</label>
                    <input type="number" step="0.01" min="0" name="registration_fee" value="{{ old('registration_fee', $event->registration_fee) }}" class="{{ $in }}">
                    <p class="{{ $hint }}">0 = gratuito. Usado se não houver lotes.</p>
                </div>
                <div>
                    <label class="{{ $lb }}">Prazo de inscrição</label>
                    <input type="datetime-local" name="registration_deadline" value="{{ old('registration_deadline', $event->registration_deadline?->format('Y-m-d\TH:i')) }}" class="{{ $in }}">
                </div>
                <div>
                    <label class="{{ $lb }}">Máx. participantes</label>
                    <input type="number" name="max_participants" min="1" value="{{ old('max_participants', $event->max_participants) }}" class="{{ $in }}" placeholder="Ilimitado">
                </div>
                <div>
                    <label class="{{ $lb }}">Inscrições por pessoa</label>
                    <input type="number" name="max_per_registration" min="1" max="50" value="{{ old('max_per_registration', $event->max_per_registration ?? 1) }}" class="{{ $in }}">
                </div>
            </div>
            <div class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-4 dark:border-indigo-900/40 dark:bg-indigo-950/25">
                <p class="text-sm font-bold text-indigo-900 dark:text-indigo-100">Código de desconto (opcional)</p>
                <p class="{{ $hint }} mb-3">Quem escrever o código ao inscrever-se recebe o desconto.</p>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="{{ $lb }}">Código</label>
                        <input type="text" name="pricing_discount_code" value="{{ old('pricing_discount_code', $discountRule?->config['code'] ?? '') }}" class="{{ $in }}" placeholder="JUBAF10" autocomplete="off">
                    </div>
                    <div>
                        <label class="{{ $lb }}">Desconto (%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="pricing_discount_percent" value="{{ old('pricing_discount_percent', $discountRule?->config['percent'] ?? '') }}" class="{{ $in }}" placeholder="10">
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-amber-100 bg-amber-50/50 p-4 dark:border-amber-900/40 dark:bg-amber-950/20">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="text-sm font-bold text-amber-900 dark:text-amber-100">Lotes de preço</p>
                    <button type="button" @click="addBatch()" class="inline-flex items-center gap-1 rounded-lg border border-amber-300 bg-white px-3 py-1.5 text-xs font-bold text-amber-900 dark:border-amber-700 dark:bg-slate-800 dark:text-amber-100">
                        <x-icon name="plus" class="h-3.5 w-3.5" style="solid" /> Adicionar lote
                    </button>
                </div>
                <p class="{{ $hint }} mb-3">Vários preços (ex.: early bird). Pode deixar só o valor base acima.</p>
                <div class="space-y-3">
                    <template x-for="(row, index) in batchRows" :key="'b'+index">
                        <div class="rounded-lg border border-white bg-white p-3 dark:border-slate-600 dark:bg-slate-800">
                            <div class="mb-2 flex justify-between">
                                <span class="text-xs font-bold text-slate-500">Lote <span x-text="index+1"></span></span>
                                <button type="button" @click="removeBatch(index)" class="text-xs font-bold text-rose-600">Remover</button>
                            </div>
                            <input type="hidden" :name="`batches[${index}][id]`" x-model="row.id">
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                <div class="sm:col-span-2">
                                    <label class="{{ $lb }}">Nome</label>
                                    <input type="text" :name="`batches[${index}][name]`" x-model="row.name" class="{{ $in }}" placeholder="1.º lote">
                                </div>
                                <div>
                                    <label class="{{ $lb }}">Preço R$</label>
                                    <input type="number" step="0.01" min="0" :name="`batches[${index}][price]`" x-model="row.price" class="{{ $in }}">
                                </div>
                                <div>
                                    <label class="{{ $lb }}">Ordem</label>
                                    <input type="number" :name="`batches[${index}][sort_order]`" x-model="row.sort_order" class="{{ $in }}">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="{{ $lb }}">Vendas até</label>
                                    <input type="datetime-local" :name="`batches[${index}][sale_ends_at]`" x-model="row.sale_ends_at" class="{{ $in }}">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="flex flex-wrap gap-6 rounded-xl border border-slate-100 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-900/40">
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $event->is_featured)) class="h-4 w-4 rounded text-emerald-600">
                    Destaque na homepage
                </label>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="hidden" name="requires_council_approval" value="0">
                    <input type="checkbox" name="requires_council_approval" value="1" @checked(old('requires_council_approval', $event->requires_council_approval)) class="h-4 w-4 rounded text-emerald-600">
                    Exige aprovação para publicar
                </label>
            </div>
        </div>
    </div>

    {{-- 3 --}}
    <div x-show="step === 3" x-cloak class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="border-b border-slate-100 bg-linear-to-r from-violet-50/80 to-white px-6 py-4 dark:border-slate-700 dark:from-violet-950/25 dark:to-slate-800">
            <h2 class="text-base font-bold text-slate-900 dark:text-white">Aparência pública</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Capa e cores da página do evento.</p>
        </div>
        <div class="space-y-6 p-6">
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="{{ $lb }}">Capa</label>
                    <input type="file" name="cover" accept="image/*" class="{{ $in }}">
                    @if($editMode && $event->cover_path)
                        <p class="{{ $hint }} mt-1"><a href="{{ asset('storage/'.$event->cover_path) }}" target="_blank" class="font-semibold text-emerald-700 underline">Ver capa actual</a></p>
                    @endif
                </div>
                <div>
                    <label class="{{ $lb }}">Banner (opcional)</label>
                    <input type="file" name="banner" accept="image/*" class="{{ $in }}">
                </div>
            </div>
            <div>
                <p class="{{ $lb }}">Modelo visual</p>
                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach(['corporate' => 'Institucional', 'modern' => 'Moderno', 'minimal' => 'Minimal'] as $val => $label)
                        <label class="flex cursor-pointer flex-col rounded-xl border-2 border-slate-200 p-4 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50 dark:border-slate-600 dark:has-[:checked]:border-emerald-600 dark:has-[:checked]:bg-emerald-950/30">
                            <input type="radio" name="theme" value="{{ $val }}" class="sr-only" @checked(old('theme', $tc['theme'] ?? 'corporate') === $val)>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="grid max-w-xl gap-6 md:grid-cols-2">
                <div>
                    <label class="{{ $lb }}">Cor principal</label>
                    <input type="color" name="primary_color" value="{{ old('primary_color', $tc['primary_color'] ?? '#1e40af') }}" class="h-12 w-full cursor-pointer rounded-xl border border-slate-200 dark:border-slate-600">
                </div>
                <div>
                    <label class="{{ $lb }}">Cor secundária</label>
                    <input type="color" name="secondary_color" value="{{ old('secondary_color', $tc['secondary_color'] ?? '#0f172a') }}" class="h-12 w-full cursor-pointer rounded-xl border border-slate-200 dark:border-slate-600">
                </div>
            </div>
        </div>
    </div>

    {{-- 4 --}}
    <div x-show="step === 4" x-cloak class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="border-b border-slate-100 bg-linear-to-r from-teal-50/80 to-white px-6 py-4 dark:border-slate-700 dark:from-teal-950/25 dark:to-slate-800">
            <h2 class="text-base font-bold text-slate-900 dark:text-white">Programa e contactos</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Horários da programação e quem os participantes podem contactar.</p>
        </div>
        <div class="space-y-6 p-6">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="text-sm font-bold text-slate-900 dark:text-white">Programa do dia</p>
                    <button type="button" @click="addScheduleRow()" class="inline-flex items-center gap-1 rounded-lg border border-teal-200 bg-teal-50 px-3 py-1.5 text-xs font-bold text-teal-900 dark:border-teal-800 dark:bg-teal-950/40 dark:text-teal-100">
                        <x-icon name="plus" class="h-3.5 w-3.5" style="solid" /> Linha
                    </button>
                </div>
                <p class="{{ $hint }} mb-3">Uma linha por momento (ex.: 09:00 — Abertura). Pode deixar vazio.</p>
                <div class="space-y-2">
                    <template x-for="(row, index) in scheduleRows" :key="'s'+index">
                        <div class="flex flex-wrap items-end gap-2 rounded-lg border border-slate-100 bg-slate-50/80 p-2 dark:border-slate-700 dark:bg-slate-900/50">
                            <div class="w-28">
                                <label class="{{ $lb }}">Hora</label>
                                <input type="text" :name="`schedule_items[${index}][time]`" x-model="row.time" class="{{ $in }}" placeholder="09:00">
                            </div>
                            <div class="min-w-[12rem] flex-1">
                                <label class="{{ $lb }}">Actividade</label>
                                <input type="text" :name="`schedule_items[${index}][label]`" x-model="row.label" class="{{ $in }}" placeholder="Abertura / palestra / lanche">
                            </div>
                            <button type="button" @click="removeScheduleRow(index)" class="rounded-lg px-2 py-1 text-xs font-bold text-rose-600 hover:bg-rose-50">✕</button>
                        </div>
                    </template>
                </div>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="{{ $lb }}">Dicas para quem vai (opcional)</label>
                    <textarea name="meta_tips" rows="2" class="{{ $in }}" placeholder="Ex.: Levar água, roupa confortável…">{{ $metaTips }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="{{ $lb }}">Dress code (opcional)</label>
                    <input type="text" name="meta_dress_code" value="{{ $metaDress }}" class="{{ $in }}" placeholder="Ex.: roupa branca">
                </div>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="{{ $lb }}">Nome contacto</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name', $event->contact_name) }}" class="{{ $in }}">
                </div>
                <div>
                    <label class="{{ $lb }}">E-mail</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $event->contact_email) }}" class="{{ $in }}">
                </div>
                <div>
                    <label class="{{ $lb }}">Telefone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $event->contact_phone) }}" class="{{ $in }}">
                </div>
                <div>
                    <label class="{{ $lb }}">WhatsApp</label>
                    <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $event->contact_whatsapp) }}" class="{{ $in }}">
                </div>
            </div>
            @if(module_enabled('Blog'))
                <div>
                    <label class="{{ $lb }}">Artigo do blog (ID)</label>
                    <input type="number" name="blog_post_id" value="{{ old('blog_post_id', $event->blog_post_id) }}" class="{{ $in }}" placeholder="Opcional — ID na lista de posts">
                    <p class="{{ $hint }}">Liga um artigo já publicado ao evento.</p>
                </div>
            @endif
            @if(module_enabled('Avisos'))
                <div>
                    <label class="{{ $lb }}">Aviso (ID)</label>
                    <input type="number" name="aviso_id" value="{{ old('aviso_id', $event->aviso_id) }}" class="{{ $in }}" placeholder="Opcional">
                </div>
            @endif
            <details class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 p-4 dark:border-slate-600 dark:bg-slate-900/40">
                <summary class="cursor-pointer text-sm font-bold text-slate-700 dark:text-slate-200">Avançado: JSON (só se precisar)</summary>
                <p class="{{ $hint }} mb-2">Para equipas técnicas — campos em JSON legados.</p>
                <label class="{{ $lb }}">form_fields JSON</label>
                <textarea name="form_fields_json" rows="2" class="{{ $in }} font-mono text-xs">{{ old('form_fields_json', $event->form_fields ? json_encode($event->form_fields, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
                <label class="{{ $lb }} mt-2">metadata JSON extra</label>
                <textarea name="metadata_json" rows="2" class="{{ $in }} font-mono text-xs">{{ old('metadata_json', '') }}</textarea>
            </details>
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700 dark:bg-slate-900/50">
        <div class="flex flex-wrap gap-2">
            <button type="button" @click="step = Math.max(1, step - 1)" x-show="step > 1" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                ← Anterior
            </button>
            <button type="button" @click="step = Math.min(4, step + 1)" x-show="step < 4" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-emerald-700">
                Seguinte →
            </button>
        </div>
        <p class="text-center text-xs text-slate-500 dark:text-slate-400 sm:text-right" x-show="step < 4">Passo <span x-text="step"></span> de 4 — use Seguinte para continuar.</p>
        <p class="text-center text-xs font-semibold text-emerald-700 dark:text-emerald-400 sm:text-right" x-show="step === 4">Último passo — guarde o evento abaixo.</p>
    </div>
</div>

@push('styles')
<style>[x-cloak]{display:none !important;}</style>
@endpush

@push('scripts')
<script>
function calEventWizard(opts) {
    return {
        step: 1,
        scheduleRows: Array.isArray(opts.scheduleRows) && opts.scheduleRows.length ? opts.scheduleRows : [{ time: '', label: '' }],
        batchRows: Array.isArray(opts.batchRows) && opts.batchRows.length ? opts.batchRows : [{ id: '', name: '', price: '0', sort_order: 0, sale_ends_at: '' }],
        addBatch() {
            this.batchRows.push({ id: '', name: '', price: '0', sort_order: this.batchRows.length, sale_ends_at: '' });
        },
        removeBatch(i) {
            if (this.batchRows.length > 1) this.batchRows.splice(i, 1);
        },
        addScheduleRow() {
            this.scheduleRows.push({ time: '', label: '' });
        },
        removeScheduleRow(i) {
            if (this.scheduleRows.length > 1) this.scheduleRows.splice(i, 1);
        },
    };
}
</script>
@endpush
