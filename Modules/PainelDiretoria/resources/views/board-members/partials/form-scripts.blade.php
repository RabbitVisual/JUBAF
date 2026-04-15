{{-- @var \App\Models\BoardMember $boardMember --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const elName = document.getElementById('full_name');
        const elTitle = document.getElementById('public_title');
        const elGroup = document.getElementById('group_label');
        const elBio = document.getElementById('bio_short');
        const elOrder = document.getElementById('sort_order');
        const elActive = document.getElementById('is_active');
        const elPhoto = document.getElementById('board-member-photo-input');
        const labelActive = document.getElementById('is-active-label');

        const pvName = document.getElementById('bm-preview-name');
        const pvTitle = document.getElementById('bm-preview-title');
        const pvGroup = document.getElementById('bm-preview-group');
        const pvBio = document.getElementById('bm-preview-bio');
        const pvOrder = document.getElementById('bm-preview-order');
        const pvActive = document.getElementById('bm-preview-active');
        const pvImg = document.getElementById('bm-preview-avatar-img');
        const pvPh = document.getElementById('bm-preview-avatar-placeholder');

        function syncActiveLabel() {
            if (!labelActive || !elActive) return;
            labelActive.textContent = elActive.checked ? 'Ativo no site' : 'Oculto no site';
        }

        function syncActiveBadge() {
            if (!pvActive) return;
            const on = elActive && elActive.checked;
            pvActive.textContent = on ? 'Visível' : 'Oculto';
            pvActive.className =
                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ' +
                (on ?
                    'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200' :
                    'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300');
        }

        function syncPreview() {
            if (pvName) pvName.textContent = (elName && elName.value.trim()) || 'Nome do membro';
            if (pvTitle) pvTitle.textContent = (elTitle && elTitle.value.trim()) || 'Cargo público';
            if (pvGroup) {
                const g = elGroup && elGroup.value.trim();
                pvGroup.textContent = g || '';
                pvGroup.classList.toggle('hidden', !g);
            }
            if (pvBio) {
                const b = elBio && elBio.value.trim();
                pvBio.textContent = b || 'A bio curta aparecerá aqui.';
            }
            if (pvOrder) {
                const raw = elOrder && elOrder.value !== '' ? elOrder.value : '0';
                const n = raw === '' ? '0' : raw;
                pvOrder.textContent = 'Ordem: ' + n;
            }
            syncActiveLabel();
            syncActiveBadge();
        }

        ['input', 'change'].forEach(function(ev) {
            [elName, elTitle, elGroup, elBio, elOrder, elActive].forEach(function(el) {
                if (el) el.addEventListener(ev, syncPreview);
            });
        });
        syncPreview();

        if (elPhoto && pvImg && pvPh) {
            elPhoto.addEventListener('change', function() {
                const f = elPhoto.files && elPhoto.files[0];
                if (!f) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    pvImg.src = e.target.result;
                    pvImg.classList.remove('hidden');
                    pvPh.classList.add('hidden');
                };
                reader.readAsDataURL(f);
            });
        }
    });
</script>
