{{--
    Capa + foto principal + até 3 miniaturas. Dentro do <form> principal — NÃO usar <form> aninhado (HTML inválido).
    @props user, accent (indigo|violet|emerald|slate|blue), variant (card|hero), showIdentity (hero: nome/funções na faixa branca)
--}}
@props([
    'user',
    'accent' => 'indigo',
    'variant' => 'card',
    'showIdentity' => false,
])

@php
    $uid = 'pf_'.bin2hex(random_bytes(4));
    $photos = $user->relationLoaded('profilePhotos')
        ? $user->profilePhotos
        : $user->profilePhotos()->orderBy('sort_order')->orderBy('id')->get();
    $gradients = [
        'blue' => 'from-blue-600 to-blue-800',
        'indigo' => 'from-indigo-600 to-blue-800',
        'violet' => 'from-violet-600 to-purple-800',
        'emerald' => 'from-emerald-600 to-teal-800',
        'slate' => 'from-gray-700 to-gray-900',
    ];
    $grad = $gradients[$accent] ?? $gradients['indigo'];
    $coverUrl = user_cover_url($user);
    $avatarUrl = user_photo_url($user);
    $maxPhotos = \App\Services\UserProfileMediaService::MAX_PROFILE_PHOTOS;
    $remainingSlots = max(0, $maxPhotos - $photos->count());
    $canAddMore = $remainingSlots > 0;
    $coverPosX = max(0, min(100, (int) ($user->cover_position_x ?? 50)));
    $coverPosY = max(0, min(100, (int) ($user->cover_position_y ?? 50)));
    $accentRange = match ($accent) {
        'emerald' => 'accent-emerald-600 dark:accent-emerald-400',
        'violet' => 'accent-violet-600 dark:accent-violet-400',
        'slate' => 'accent-gray-600 dark:accent-gray-400',
        'blue' => 'accent-blue-600 dark:accent-blue-400',
        default => 'accent-[#1877F2] dark:accent-indigo-400',
    };
    $heroRing = match ($accent) {
        'emerald' => 'border border-emerald-200/80 ring-1 ring-emerald-500/10 dark:border-emerald-900/50 dark:ring-emerald-500/20',
        default => 'ring-1 ring-black/[0.08] dark:ring-white/10',
    };
@endphp

@if($variant === 'hero')
{{-- Estilo “capa Facebook”: bloco único, capa + avatar a sobrepor a base. --}}
<div {{ $attributes->merge(['class' => 'w-full overflow-hidden rounded-3xl bg-white shadow-lg shadow-gray-900/5 '.$heroRing.' dark:bg-gray-900']) }}>
    <input type="hidden" name="cover_position_x" id="{{ $uid }}_cpx" value="{{ $coverPosX }}">
    <input type="hidden" name="cover_position_y" id="{{ $uid }}_cpy" value="{{ $coverPosY }}">
    {{-- Faixa de capa --}}
    <div class="relative isolate h-48 w-full overflow-hidden bg-gray-200 sm:h-52 md:h-60 dark:bg-gray-800">
        @if($coverUrl)
            <img src="{{ $coverUrl }}" alt="" class="absolute inset-0 h-full w-full object-cover" style="object-position: {{ user_cover_object_position($user) }}" id="{{ $uid }}_cover_img">
        @else
            <div id="{{ $uid }}_cover_ph" class="absolute inset-0 bg-gradient-to-br {{ $grad }}"></div>
        @endif
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent"></div>
        <label class="pointer-events-auto absolute bottom-3 right-3 z-10 inline-flex cursor-pointer items-center gap-2 rounded-xl bg-white/95 px-3 py-2 text-xs font-bold text-gray-800 shadow-lg backdrop-blur-sm transition hover:bg-white dark:bg-gray-900/95 dark:text-gray-100 dark:hover:bg-gray-800">
            <x-icon name="camera" class="h-3.5 w-3.5" />
            <span class="hidden sm:inline">Alterar capa</span>
            <span class="sm:hidden">Capa</span>
            <input type="file" name="cover_photo" class="hidden" accept="image/*" onchange="window.jubafCoverStudioPreview('{{ $uid }}', this)">
        </label>
    </div>

    {{-- Faixa branca: avatar sobrepõe a capa + identidade + miniaturas --}}
    <div class="relative border-t border-gray-200/90 bg-white px-4 pb-6 pt-0 dark:border-gray-700 dark:bg-gray-900 sm:px-8 sm:pb-8">
        <div id="{{ $uid }}_cover_position_ui" class="{{ $coverUrl ? '' : 'hidden' }} mb-4 mt-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-800/80">
            <p class="mb-2 text-xs font-semibold text-gray-700 dark:text-gray-300">Enquadramento da capa</p>
            <p class="mb-3 text-[11px] leading-snug text-gray-500 dark:text-gray-400">Ajuste o enquadramento se a imagem for cortada. Depois confirme com <strong class="text-gray-700 dark:text-gray-300">Salvar alterações</strong> no final da página.</p>
            <div class="space-y-3">
                <div>
                    <div class="mb-1 flex justify-between text-[11px] text-gray-500 dark:text-gray-400">
                        <span>Esquerda</span>
                        <span>Direita</span>
                    </div>
                    <input type="range" id="{{ $uid }}_range_x" min="0" max="100" value="{{ $coverPosX }}" class="h-2 w-full cursor-pointer appearance-none rounded-full bg-gray-200 {{ $accentRange }}" oninput="window.jubafCoverPositionApply('{{ $uid }}')">
                </div>
                <div>
                    <div class="mb-1 flex justify-between text-[11px] text-gray-500 dark:text-gray-400">
                        <span>Topo</span>
                        <span>Base</span>
                    </div>
                    <input type="range" id="{{ $uid }}_range_y" min="0" max="100" value="{{ $coverPosY }}" class="h-2 w-full cursor-pointer appearance-none rounded-full bg-gray-200 {{ $accentRange }}" oninput="window.jubafCoverPositionApply('{{ $uid }}')">
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:gap-10">
            <div class="relative z-10 -mt-16 shrink-0 self-center lg:self-end lg:-mt-[5.25rem]">
                <div class="group/avatar relative mx-auto h-[128px] w-[128px] overflow-hidden rounded-full border-[5px] border-white bg-gray-200 shadow-xl ring-2 ring-gray-200/80 dark:border-gray-900 dark:bg-gray-700 dark:ring-gray-700 sm:h-[168px] sm:w-[168px] sm:border-[6px]">
                    @if($avatarUrl)
                        <img id="{{ $uid }}_main" src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <div id="{{ $uid }}_main_ph" class="flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-400 to-gray-600 text-4xl font-bold text-white sm:text-5xl">
                            {{ strtoupper(mb_substr($user->first_name ?? $user->name, 0, 1)) }}
                        </div>
                    @endif
                    @if($canAddMore)
                        <label for="{{ $uid }}_photos" class="absolute inset-0 flex cursor-pointer items-center justify-center rounded-full bg-black/40 opacity-0 transition-opacity group-hover/avatar:opacity-100" title="Adicionar fotos de perfil">
                            <x-icon name="camera" class="h-8 w-8 text-white drop-shadow sm:h-9 sm:w-9" />
                        </label>
                        <input type="file" name="profile_photos[]" id="{{ $uid }}_photos" class="hidden" accept="image/*" @if($remainingSlots > 1) multiple @endif onchange="window.jubafProfileStudioPreview('{{ $uid }}', this)">
                    @endif
                </div>
            </div>

            <div class="min-w-0 flex-1 space-y-5 pb-0 sm:pb-1">
                @if ($showIdentity)
                    <div class="space-y-3 text-center lg:pt-2 lg:text-left">
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400">Conta e presença JUBAF</p>
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            @forelse ($user->roles as $role)
                                {{ jubaf_role_label($role->name) }}@if (!$loop->last)<span class="mx-1 text-gray-400">·</span>@endif
                            @empty
                                <span class="text-gray-500">Utilizador</span>
                            @endforelse
                        </p>
                        <div class="flex flex-wrap items-center justify-center gap-2 lg:justify-start">
                            <span class="inline-flex max-w-full items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                                <x-icon name="envelope" class="h-3.5 w-3.5 shrink-0 opacity-80" style="duotone" />
                                <span class="truncate">{{ $user->email }}</span>
                            </span>
                            @if ($user->church)
                                <span class="inline-flex max-w-full items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">
                                    <x-icon name="church" class="h-3.5 w-3.5 shrink-0 opacity-90" style="duotone" />
                                    <span class="truncate">{{ $user->church->name }}</span>
                                </span>
                            @endif
                        </div>
                        <div class="flex flex-wrap justify-center gap-2 lg:justify-start">
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-left dark:border-gray-600 dark:bg-gray-800/80">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Atualizado</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->updated_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-left dark:border-gray-600 dark:bg-gray-800/80">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Funções</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->roles->count() }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="border-t border-gray-200 pt-4 dark:border-gray-700 lg:border-t-0 lg:pt-0">
                <p class="mb-3 text-center text-xs text-gray-500 dark:text-gray-400 lg:text-left">
                    Galeria · até {{ $maxPhotos }} fotos. Clique numa miniatura para definir a principal. Use <strong class="font-semibold text-gray-700 dark:text-gray-300">Salvar alterações</strong> para enviar capa e fotos novas.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-2 lg:justify-start">
                    @foreach($photos as $photo)
                        @php $thumbUrl = profile_photo_asset($photo); @endphp
                        <div class="relative group/th">
                            <button type="button"
                                class="block h-11 w-11 overflow-hidden rounded-full border-[3px] shadow-sm transition sm:h-12 sm:w-12 {{ $photo->is_active ? ($accent === 'emerald' ? 'border-emerald-500 ring-2 ring-emerald-500/25 dark:border-emerald-400' : 'border-[#1877F2] ring-2 ring-[#1877F2]/25 dark:border-blue-400 dark:ring-blue-500/30') : 'border-gray-200 opacity-90 hover:opacity-100 dark:border-gray-600' }}"
                                title="{{ $photo->is_active ? 'Foto principal' : 'Definir como principal' }}"
                                onclick="window.jubafProfilePhotoAction('post', @js(route('account.profile.photo.activate', $photo)))">
                                @if($thumbUrl)
                                    <img src="{{ $thumbUrl }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </button>
                            <button type="button"
                                class="absolute -right-0.5 -top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow opacity-0 transition hover:bg-red-600 group-hover/th:opacity-100 sm:h-6 sm:w-6"
                                title="Remover foto"
                                onclick="window.jubafProfilePhotoAction('delete', @js(route('account.profile.photo.destroy', $photo)))">
                                <x-icon name="xmark" class="h-2.5 w-2.5 sm:h-3 sm:w-3" />
                            </button>
                        </div>
                    @endforeach
                    @if($canAddMore)
                        <label for="{{ $uid }}_photos" class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-full border-2 border-dashed border-gray-300 bg-gray-50 text-gray-400 transition hover:border-emerald-500 hover:text-emerald-600 dark:border-gray-600 dark:bg-gray-800 dark:hover:border-emerald-400 dark:hover:text-emerald-300 sm:h-12 sm:w-12" title="Adicionar fotos">
                            <x-icon name="plus" class="h-5 w-5" />
                        </label>
                    @endif
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
{{-- card variant (admin / diretoria) — estilo “capa + avatar sobreposto” --}}
<div {{ $attributes->merge(['class' => 'rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden relative group bg-white dark:bg-gray-800 ring-1 ring-black/[0.04] dark:ring-white/5']) }}>
    <input type="hidden" name="cover_position_x" id="{{ $uid }}_cpx" value="{{ $coverPosX }}">
    <input type="hidden" name="cover_position_y" id="{{ $uid }}_cpy" value="{{ $coverPosY }}">
    <div class="relative h-40 md:h-48 group/cover">
        @if($coverUrl)
            <img src="{{ $coverUrl }}" alt="" id="{{ $uid }}_cover_img" class="absolute inset-0 h-full w-full object-cover" style="object-position: {{ user_cover_object_position($user) }}">
        @else
            <div id="{{ $uid }}_cover_ph" class="absolute inset-0 bg-gradient-to-br {{ $grad }}"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/35 to-transparent pointer-events-none"></div>
        <label class="absolute bottom-3 right-3 inline-flex cursor-pointer items-center gap-2 rounded-xl bg-white/95 px-3 py-2 text-xs font-bold text-gray-800 shadow-lg backdrop-blur-sm transition hover:bg-white dark:bg-gray-900/92 dark:text-white">
            <x-icon name="image" class="w-3.5 h-3.5" />
            Capa
            <input type="file" name="cover_photo" class="hidden" accept="image/*" onchange="window.jubafCoverStudioPreview('{{ $uid }}', this)">
        </label>
    </div>
    <div id="{{ $uid }}_cover_position_ui" class="{{ $coverUrl ? '' : 'hidden' }} border-b border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/50">
        <p class="mb-2 text-xs font-semibold text-gray-700 dark:text-gray-300">Enquadramento da capa</p>
        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
            <div>
                <div class="mb-0.5 flex justify-between text-[10px] text-gray-500"><span>Esquerda</span><span>Direita</span></div>
                <input type="range" id="{{ $uid }}_range_x" min="0" max="100" value="{{ $coverPosX }}" class="h-1.5 w-full cursor-pointer accent-indigo-600 dark:accent-indigo-400" oninput="window.jubafCoverPositionApply('{{ $uid }}')">
            </div>
            <div>
                <div class="mb-0.5 flex justify-between text-[10px] text-gray-500"><span>Topo</span><span>Base</span></div>
                <input type="range" id="{{ $uid }}_range_y" min="0" max="100" value="{{ $coverPosY }}" class="h-1.5 w-full cursor-pointer accent-indigo-600 dark:accent-indigo-400" oninput="window.jubafCoverPositionApply('{{ $uid }}')">
            </div>
        </div>
    </div>
    <div class="px-6 md:px-8 pb-8 text-center relative">
        <div class="relative -mt-[4.5rem] mb-5 inline-block group/avatar">
            <div class="w-36 h-36 rounded-[2rem] border-[6px] border-white dark:border-gray-800 bg-gray-100 dark:bg-gray-700 relative overflow-hidden shadow-2xl transition-transform duration-300 group-hover/avatar:scale-[1.02]">
                @if($avatarUrl)
                    <img id="{{ $uid }}_main" src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                @else
                    <div id="{{ $uid }}_main_ph" class="h-full w-full flex items-center justify-center text-4xl font-bold text-indigo-500 bg-indigo-50 dark:bg-indigo-900/20">
                        {{ strtoupper(mb_substr($user->first_name ?? $user->name, 0, 1)) }}
                    </div>
                @endif
                @if($canAddMore)
                    <label for="{{ $uid }}_photos" class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/45 opacity-0 transition-opacity group-hover/avatar:opacity-100">
                        <x-icon name="camera" class="w-9 h-9 text-white drop-shadow-md" />
                    </label>
                    <input type="file" name="profile_photos[]" id="{{ $uid }}_photos" class="hidden" accept="image/*" @if($remainingSlots > 1) multiple @endif onchange="window.jubafProfileStudioPreview('{{ $uid }}', this)">
                @endif
            </div>
            <div class="absolute bottom-2 right-2 flex h-8 w-8 items-center justify-center rounded-2xl border-[3px] border-white dark:border-gray-800 bg-emerald-500 shadow-md" title="Conta ativa">
                <x-icon name="check" class="h-3.5 w-3.5 text-white" />
            </div>
        </div>

        <div class="mb-4 flex flex-wrap items-center justify-center gap-2.5">
            @foreach($photos as $photo)
                @php $thumbUrl = profile_photo_asset($photo); @endphp
                <div class="relative group/th">
                    <button type="button"
                        class="block h-[52px] w-[52px] overflow-hidden rounded-full border-[3px] shadow-md transition {{ $photo->is_active ? 'border-blue-500 ring-4 ring-blue-500/25 dark:border-blue-400' : 'border-gray-200 opacity-75 hover:opacity-100 dark:border-gray-600' }}"
                        title="{{ $photo->is_active ? 'Foto principal' : 'Usar como principal' }}"
                        onclick="window.jubafProfilePhotoAction('post', @js(route('account.profile.photo.activate', $photo)))">
                        @if($thumbUrl)
                            <img src="{{ $thumbUrl }}" alt="" class="h-full w-full object-cover">
                        @endif
                    </button>
                    <button type="button"
                        class="absolute -right-1 -top-1 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white shadow-md opacity-0 transition hover:bg-red-600 group-hover/th:opacity-100"
                        title="Remover"
                        onclick="window.jubafProfilePhotoAction('delete', @js(route('account.profile.photo.destroy', $photo)))">
                        <x-icon name="xmark" class="h-3 w-3" />
                    </button>
                </div>
            @endforeach
            @if($canAddMore)
                <label for="{{ $uid }}_photos" class="flex h-[52px] w-[52px] cursor-pointer items-center justify-center rounded-full border-2 border-dashed border-gray-300 bg-gray-50 text-gray-400 transition hover:border-blue-400 hover:text-blue-600 dark:border-gray-600 dark:bg-gray-900 dark:hover:border-blue-500" title="Adicionar até {{ $remainingSlots }} foto(s)">
                    <x-icon name="plus" class="h-6 w-6" />
                </label>
            @endif
        </div>
        <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-1 leading-relaxed max-w-sm mx-auto">
            <strong class="text-gray-700 dark:text-gray-300">1–{{ $maxPhotos }} fotos</strong> — clique numa miniatura para definir a principal. Novas imagens: escolha os ficheiros e <strong class="text-gray-700 dark:text-gray-300">Salvar</strong>.
        </p>
    </div>
</div>
@endif

@once
    @push('scripts')
    <script>
        window.jubafProfilePhotoAction = function (method, url) {
            if (method === 'delete' && !window.confirm('Remover esta foto?')) {
                return;
            }
            var meta = document.querySelector('meta[name="csrf-token"]');
            var token = meta ? meta.getAttribute('content') : '';
            if (!token || !url) {
                return;
            }
            var f = document.createElement('form');
            f.method = 'POST';
            f.action = url;
            f.style.display = 'none';
            var t = document.createElement('input');
            t.type = 'hidden';
            t.name = '_token';
            t.value = token;
            f.appendChild(t);
            if (method === 'delete') {
                var m = document.createElement('input');
                m.type = 'hidden';
                m.name = '_method';
                m.value = 'DELETE';
                f.appendChild(m);
            }
            document.body.appendChild(f);
            f.submit();
        };
        window.jubafCoverPositionApply = function (uid) {
            var img = document.getElementById(uid + '_cover_img');
            var rx = document.getElementById(uid + '_range_x');
            var ry = document.getElementById(uid + '_range_y');
            var hx = document.getElementById(uid + '_cpx');
            var hy = document.getElementById(uid + '_cpy');
            var x = rx ? parseInt(rx.value, 10) : (hx ? parseInt(hx.value, 10) : 50);
            var y = ry ? parseInt(ry.value, 10) : (hy ? parseInt(hy.value, 10) : 50);
            if (isNaN(x)) { x = 50; }
            if (isNaN(y)) { y = 50; }
            if (hx) { hx.value = x; }
            if (hy) { hy.value = y; }
            if (img) { img.style.objectPosition = x + '% ' + y + '%'; }
        };
        window.jubafCoverStudioPreview = function (uid, input) {
            if (!input.files || !input.files[0]) return;
            var r = new FileReader();
            var ui = document.getElementById(uid + '_cover_position_ui');
            r.onload = function (e) {
                var img = document.getElementById(uid + '_cover_img');
                if (img) {
                    img.src = e.target.result;
                    var rx = document.getElementById(uid + '_range_x');
                    var ry = document.getElementById(uid + '_range_y');
                    if (rx) { rx.value = 50; }
                    if (ry) { ry.value = 50; }
                    if (ui) { ui.classList.remove('hidden'); }
                    window.jubafCoverPositionApply(uid);
                    return;
                }
                var wrap = document.getElementById(uid + '_cover_ph');
                if (wrap && wrap.parentNode) {
                    img = document.createElement('img');
                    img.id = uid + '_cover_img';
                    img.className = 'absolute inset-0 h-full w-full object-cover';
                    img.alt = '';
                    wrap.parentNode.insertBefore(img, wrap);
                    wrap.style.display = 'none';
                    img.src = e.target.result;
                    var rx = document.getElementById(uid + '_range_x');
                    var ry = document.getElementById(uid + '_range_y');
                    if (rx) { rx.value = 50; }
                    if (ry) { ry.value = 50; }
                    if (ui) { ui.classList.remove('hidden'); }
                    window.jubafCoverPositionApply(uid);
                }
            };
            r.readAsDataURL(input.files[0]);
        };
        window.jubafProfileStudioPreview = function (uid, input) {
            if (!input.files || !input.files[0]) return;
            var r = new FileReader();
            r.onload = function (e) {
                var main = document.getElementById(uid + '_main');
                var ph = document.getElementById(uid + '_main_ph');
                if (main && main.tagName === 'IMG') {
                    main.src = e.target.result;
                } else if (ph) {
                    var im = document.createElement('img');
                    im.id = uid + '_main';
                    im.src = e.target.result;
                    im.className = 'h-full w-full object-cover';
                    im.alt = '';
                    ph.parentNode.replaceChild(im, ph);
                }
            };
            r.readAsDataURL(input.files[0]);
        };
    </script>
    @endpush
@endonce
