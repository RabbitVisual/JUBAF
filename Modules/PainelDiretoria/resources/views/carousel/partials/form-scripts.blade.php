@vite(['resources/js/quill-editor.js'])
<script>
(function () {
    function syncAsideFromMainPreview() {
        var wrap = document.getElementById('aside-preview-image');
        var fallback = document.getElementById('aside-preview-no-image');
        var main = document.getElementById('previewImg');
        var aside = document.getElementById('aside-preview-img');
        var showToggle = document.querySelector('[data-show-image-toggle]');
        var wantImage = showToggle ? showToggle.checked : true;
        if (!wrap || !fallback) return;
        if (main && aside) {
            var msrc = main.getAttribute('src') || '';
            if (msrc) aside.setAttribute('src', msrc);
        }
        var src = aside ? aside.getAttribute('src') || '' : '';
        var hasSrc = src.length > 5;
        if (wantImage && hasSrc) {
            wrap.classList.remove('hidden');
            fallback.classList.add('hidden');
        } else {
            wrap.classList.add('hidden');
            fallback.classList.remove('hidden');
        }
    }

    function bindDropzone() {
        var dz = document.querySelector('[data-carousel-dropzone]');
        var input = document.querySelector('[data-carousel-file-input]');
        if (!dz || !input) return;

        ['dragenter', 'dragover'].forEach(function (ev) {
            dz.addEventListener(ev, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dz.classList.add('ring-2', 'ring-pink-400', 'dark:ring-pink-600');
            });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            dz.addEventListener(ev, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dz.classList.remove('ring-2', 'ring-pink-400', 'dark:ring-pink-600');
            });
        });
        dz.addEventListener('drop', function (e) {
            var f = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
            if (!f || !f.type || f.type.indexOf('image') !== 0) return;
            var dt = new DataTransfer();
            dt.items.add(f);
            input.files = dt.files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    function previewFile(input) {
        var preview = document.getElementById('imagePreview');
        var previewImg = document.getElementById('previewImg');
        var label = document.getElementById('previewImgLabel');
        if (!preview || !previewImg) return;

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                previewImg.setAttribute('src', e.target.result);
                preview.classList.remove('hidden');
                if (label) label.textContent = 'Pré-visualização';
                syncAsideFromMainPreview();
            };
            reader.readAsDataURL(input.files[0]);
        } else if (previewImg.dataset.originalSrc) {
            previewImg.setAttribute('src', previewImg.dataset.originalSrc);
            preview.classList.remove('hidden');
            if (label) label.textContent = 'Imagem atual';
            syncAsideFromMainPreview();
        } else {
            preview.classList.add('hidden');
            previewImg.removeAttribute('src');
            syncAsideFromMainPreview();
        }
    }

    function bindFileInput() {
        var input = document.querySelector('[data-carousel-file-input]');
        if (!input) return;
        input.addEventListener('change', function () {
            previewFile(input);
        });
    }

    function bindQuillLivePreview() {
        var tOut = document.getElementById('live-preview-title');
        var dOut = document.getElementById('live-preview-desc');
        if (!tOut && !dOut) return;

        function sync() {
            var qTitle = document.querySelector('#quill-title .ql-editor');
            var qDesc = document.querySelector('#quill-description .ql-editor');
            if (tOut && qTitle) {
                var th = qTitle.innerHTML.replace(/^\s*<p><br><\/p>\s*$/i, '');
                if (!th || th === '<p><br></p>') {
                    tOut.innerHTML = '<span class="text-slate-500">Seu título aparecerá aqui</span>';
                } else {
                    tOut.innerHTML = qTitle.innerHTML;
                }
            }
            if (dOut && qDesc) {
                dOut.innerHTML = qDesc.innerHTML;
            }
        }

        setTimeout(function () {
            sync();
            var qTitle = document.querySelector('#quill-title .ql-editor');
            var qDesc = document.querySelector('#quill-description .ql-editor');
            if (qTitle) qTitle.addEventListener('input', sync);
            if (qDesc) qDesc.addEventListener('input', sync);
            [400, 900].forEach(function (ms) {
                setTimeout(sync, ms);
            });
        }, 250);
    }

    function bindLinkPreview() {
        var linkIn = document.querySelector('[data-live-link]');
        var textIn = document.querySelector('[data-live-link-text]');
        var btn = document.getElementById('live-preview-btn');
        if (!btn) return;

        function sync() {
            var url = linkIn && linkIn.value ? linkIn.value.trim() : '';
            var label = textIn && textIn.value ? textIn.value.trim() : 'Saiba mais';
            btn.textContent = label || 'Saiba mais';
            if (url) {
                btn.setAttribute('href', url);
                btn.classList.remove('hidden');
            } else {
                btn.setAttribute('href', '#');
                btn.classList.add('hidden');
            }
        }
        if (linkIn) linkIn.addEventListener('input', sync);
        if (textIn) textIn.addEventListener('input', sync);
        sync();
    }

    function bindShowImageToggle() {
        var toggle = document.querySelector('[data-show-image-toggle]');
        if (!toggle) return;
        toggle.addEventListener('change', function () {
            syncAsideFromMainPreview();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindDropzone();
        bindFileInput();
        bindQuillLivePreview();
        bindLinkPreview();
        bindShowImageToggle();
        syncAsideFromMainPreview();
    });
})();
</script>
