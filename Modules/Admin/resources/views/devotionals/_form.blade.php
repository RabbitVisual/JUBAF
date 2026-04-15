@php
    /** @var \App\Models\Devotional $devotional */
    $versions = module_enabled('Bible')
        ? \Modules\Bible\App\Models\BibleVersion::query()->where('is_active', true)->orderByDesc('is_default')->orderBy('name')->get(['id', 'name', 'abbreviation'])
        : collect();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Título <span class="text-red-500">*</span></label>
            <input type="text" name="title" required value="{{ old('title', $devotional->title) }}" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white" />
            @error('title')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Slug (opcional)</label>
            <input type="text" name="slug" value="{{ old('slug', $devotional->slug) }}" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white" />
            @error('slug')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Data do devocional</label>
            <input type="date" name="devotional_date" value="{{ old('devotional_date', $devotional->devotional_date?->format('Y-m-d')) }}" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white" />
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Tema (opcional)</label>
            <input type="text" name="theme" value="{{ old('theme', $devotional->theme) }}" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white" />
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Estado <span class="text-red-500">*</span></label>
            <select name="status" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white">
                <option value="{{ \App\Models\Devotional::STATUS_DRAFT }}" @selected(old('status', $devotional->status) === \App\Models\Devotional::STATUS_DRAFT)>Rascunho</option>
                <option value="{{ \App\Models\Devotional::STATUS_PUBLISHED }}" @selected(old('status', $devotional->status) === \App\Models\Devotional::STATUS_PUBLISHED)>Publicado</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Capa (imagem)</label>
            <input type="file" name="cover" accept="image/*" class="block w-full text-sm" />
            @error('cover')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Vídeo (ficheiro MP4/WebM)</label>
            <input type="file" name="video" accept="video/mp4,video/webm" class="block w-full text-sm" />
            @error('video')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">URL de vídeo (opcional, ex. hospedagem externa segura)</label>
            <input type="url" name="video_url" value="{{ old('video_url', $devotional->video_url) }}" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white" placeholder="https://..." />
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Referência bíblica <span class="text-red-500">*</span></label>
            <div class="flex flex-wrap gap-2">
                <input type="text" id="scripture_reference" name="scripture_reference" required value="{{ old('scripture_reference', $devotional->scripture_reference) }}" class="flex-1 min-w-[12rem] rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white" placeholder="Salmos 23:1-3" />
                @if($versions->isNotEmpty())
                <select id="bible_version_id" name="bible_version_id" class="rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 text-sm text-gray-900 dark:text-white">
                    <option value="">Versão padrão</option>
                    @foreach($versions as $v)
                        <option value="{{ $v->id }}" @selected(old('bible_version_id', $devotional->bible_version_id) == $v->id)>{{ $v->name }} ({{ $v->abbreviation }})</option>
                    @endforeach
                </select>
                <button type="button" id="btn-fetch-scripture" class="rounded-xl bg-slate-800 dark:bg-slate-600 text-white px-4 py-2.5 text-sm font-semibold hover:opacity-90">Carregar texto</button>
                @endif
            </div>
            <p id="fetch-scripture-msg" class="text-xs mt-1 text-gray-500"></p>
            @error('scripture_reference')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Texto da passagem</label>
            <textarea id="scripture_text" name="scripture_text" rows="8" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white font-serif text-sm">{{ old('scripture_text', $devotional->scripture_text) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Reflexão <span class="text-red-500">*</span></label>
            <textarea name="body" required rows="10" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white">{{ old('body', $devotional->body) }}</textarea>
            @error('body')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="rounded-xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/50 dark:bg-amber-950/20 p-4 space-y-3">
            <p class="text-sm font-bold text-amber-900 dark:text-amber-200">Autor</p>
            <select name="author_type" id="author_type" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white">
                <option value="{{ \App\Models\Devotional::AUTHOR_USER }}" @selected(old('author_type', $devotional->author_type) === \App\Models\Devotional::AUTHOR_USER)>Utilizador</option>
                <option value="{{ \App\Models\Devotional::AUTHOR_BOARD_MEMBER }}" @selected(old('author_type', $devotional->author_type) === \App\Models\Devotional::AUTHOR_BOARD_MEMBER)>Membro da diretoria</option>
                <option value="{{ \App\Models\Devotional::AUTHOR_PASTOR_GUEST }}" @selected(old('author_type', $devotional->author_type) === \App\Models\Devotional::AUTHOR_PASTOR_GUEST)>Pastor / convidado</option>
            </select>
            <div id="box-user" class="author-box">
                <label class="block text-xs font-semibold mb-1">Utilizador</label>
                <select name="user_id" class="w-full rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected(old('user_id', $devotional->user_id) == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div id="box-board" class="author-box hidden">
                <label class="block text-xs font-semibold mb-1">Diretoria</label>
                <select name="board_member_id" class="w-full rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                    <option value="">—</option>
                    @foreach($boardMembers as $bm)
                        <option value="{{ $bm->id }}" @selected(old('board_member_id', $devotional->board_member_id) == $bm->id)>{{ $bm->full_name }} — {{ $bm->public_title }}</option>
                    @endforeach
                </select>
                @error('board_member_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div id="box-guest" class="author-box hidden space-y-2">
                <div>
                    <label class="block text-xs font-semibold mb-1">Nome</label>
                    <input type="text" name="guest_author_name" value="{{ old('guest_author_name', $devotional->guest_author_name) }}" class="w-full rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm" />
                    @error('guest_author_name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Cargo / título</label>
                    <input type="text" name="guest_author_title" value="{{ old('guest_author_title', $devotional->guest_author_title) }}" class="w-full rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm" />
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const type = document.getElementById('author_type');
    const boxUser = document.getElementById('box-user');
    const boxBoard = document.getElementById('box-board');
    const boxGuest = document.getElementById('box-guest');
    function syncAuthor() {
        const v = type.value;
        boxUser.classList.toggle('hidden', v !== '{{ \App\Models\Devotional::AUTHOR_USER }}');
        boxBoard.classList.toggle('hidden', v !== '{{ \App\Models\Devotional::AUTHOR_BOARD_MEMBER }}');
        boxGuest.classList.toggle('hidden', v !== '{{ \App\Models\Devotional::AUTHOR_PASTOR_GUEST }}');
    }
    type.addEventListener('change', syncAuthor);
    syncAuthor();

    const btn = document.getElementById('btn-fetch-scripture');
    if (btn) {
        btn.addEventListener('click', function() {
            const ref = document.getElementById('scripture_reference').value;
            const vid = document.getElementById('bible_version_id')?.value || '';
            const msg = document.getElementById('fetch-scripture-msg');
            msg.textContent = 'A carregar…';
            fetch('{{ route($routePrefix . '.fetch-scripture') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ref: ref, version_id: vid ? parseInt(vid, 10) : null }),
            }).then(r => r.json()).then(data => {
                if (data.ok && data.data) {
                    document.getElementById('scripture_text').value = data.data.plain_text;
                    if (data.data.bible_version_id && document.getElementById('bible_version_id')) {
                        document.getElementById('bible_version_id').value = data.data.bible_version_id;
                    }
                    msg.textContent = 'Texto carregado (' + data.data.reference + ').';
                    msg.className = 'text-xs mt-1 text-emerald-600';
                } else {
                    msg.textContent = data.message || 'Erro.';
                    msg.className = 'text-xs mt-1 text-red-600';
                }
            }).catch(() => {
                msg.textContent = 'Erro de rede.';
                msg.className = 'text-xs mt-1 text-red-600';
            });
        });
    }
});
</script>
@endpush
