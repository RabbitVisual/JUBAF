import Quill from 'quill';
import 'quill/dist/quill.snow.css';

/**
 * Escape text for safe HTML insertion from API content.
 * @param {string} s
 */
function escapeHtml(s) {
    const div = document.createElement('div');
    div.textContent = s;
    return div.innerHTML;
}

/**
 * Build blockquote HTML for Bible find payload (matches API shape).
 * @param {object} payload
 */
function buildBibleQuoteHtml(payload) {
    const ref = payload.reference || '';
    const verses = Array.isArray(payload.verses) ? payload.verses : [];
    const body = verses
        .map((v) => {
            const n = v.verse_number != null ? String(v.verse_number) : '';
            const t = v.text != null ? String(v.text) : '';
            return `<p><sup>${escapeHtml(n)}</sup> ${escapeHtml(t)}</p>`;
        })
        .join('');
    return `<blockquote><p><strong>${escapeHtml(ref)}</strong></p>${body}</blockquote><p><br></p>`;
}

/**
 * @param {import('quill').default} quill
 */
function initBlogBibleAssist(quill) {
    const root = document.getElementById('blog-bible-assist');
    if (!root || !quill) {
        return;
    }

    const findUrl = root.dataset.findUrl;
    if (!findUrl) {
        return;
    }

    const input = document.getElementById('blog-bible-ref-input');
    const searchBtn = document.getElementById('blog-bible-ref-search');
    const statusEl = document.getElementById('blog-bible-ref-status');
    const previewEl = document.getElementById('blog-bible-ref-preview');
    const insertBtn = document.getElementById('blog-bible-insert');

    /** @type {object|null} */
    let lastPayload = null;

    function setStatus(msg, isError) {
        if (!statusEl) {
            return;
        }
        statusEl.textContent = msg;
        statusEl.classList.toggle('hidden', !msg);
        statusEl.classList.toggle('text-red-600', !!isError);
        statusEl.classList.toggle('dark:text-red-400', !!isError);
        statusEl.classList.toggle('text-slate-500', !isError);
    }

    async function runSearch() {
        const ref = (input?.value || '').trim();
        if (!ref) {
            setStatus('Indique uma referência bíblica.', true);
            return;
        }
        setStatus('A carregar…', false);
        if (previewEl) {
            previewEl.classList.add('hidden');
            previewEl.innerHTML = '';
        }
        if (insertBtn) {
            insertBtn.classList.add('hidden');
        }
        lastPayload = null;

        try {
            const url = new URL(findUrl, window.location.origin);
            url.searchParams.set('ref', ref);
            const response = await fetch(url.toString(), {
                headers: { Accept: 'application/json' },
            });
            const json = await response.json().catch(() => ({}));
            if (!response.ok) {
                const msg = json.message || 'Referência não encontrada.';
                setStatus(msg, true);
                return;
            }
            const payload = json.data;
            if (!payload || !payload.verses) {
                setStatus('Resposta inválida da API.', true);
                return;
            }
            lastPayload = payload;
            setStatus('', false);
            if (previewEl) {
                previewEl.innerHTML = buildBibleQuoteHtml(payload);
                previewEl.classList.remove('hidden');
            }
            if (insertBtn) {
                insertBtn.classList.remove('hidden');
            }
        } catch (e) {
            console.error(e);
            setStatus('Erro ao consultar a Bíblia.', true);
        }
    }

    searchBtn?.addEventListener('click', runSearch);
    input?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            runSearch();
        }
    });

    insertBtn?.addEventListener('click', () => {
        if (!lastPayload) {
            return;
        }
        const html = buildBibleQuoteHtml(lastPayload);
        const range = quill.getSelection(true);
        const index = range ? range.index : Math.max(0, quill.getLength() - 1);
        quill.clipboard.dangerouslyPasteHTML(index, html);
        const hiddenInput = document.getElementById('content');
        if (hiddenInput) {
            hiddenInput.value = quill.root.innerHTML;
        }
        quill.focus();
    });
}

// Custom image handler for blog (bound by Quill toolbar; `this` is toolbar)
function imageHandler() {
    const quill = this.quill;
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = async () => {
        const file = input.files[0];
        if (file) {
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Imagem muito grande. Máximo 5MB.');
                return;
            }

            const editorEl = document.getElementById('blog-content-editor');
            const uploadUrl = editorEl?.dataset?.uploadUrl || '/admin/blog/upload-image';

            // Show loading
            const range = quill.getSelection();
            quill.insertText(range.index, '⏳ Carregando imagem...');

            try {
                const formData = new FormData();
                formData.append('image', file);

                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    quill.deleteText(range.index, '⏳ Carregando imagem...'.length);
                    quill.insertEmbed(range.index, 'image', data.url);
                    quill.setSelection(range.index + 1);
                } else {
                    throw new Error('Upload failed');
                }
            } catch (error) {
                console.error('Upload failed:', error);
                quill.deleteText(range.index, '⏳ Carregando imagem...'.length);
                alert('Erro ao fazer upload da imagem.');
            }
        }
    };
}

// Initialize Blog Editor
document.addEventListener('DOMContentLoaded', function() {
    // Main content editor
    const contentEditor = document.getElementById('blog-content-editor');

    if (contentEditor) {
        const toolbarOptions = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'font': [] }],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'script': 'sub'}, { 'script': 'super' }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            [{ 'align': [] }],
            ['blockquote', 'code-block'],
            ['link', 'image', 'video'],
            ['clean']
        ];

        const quill = new Quill('#blog-content-editor', {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: toolbarOptions,
                    handlers: {
                        image: imageHandler
                    }
                },
                history: {
                    delay: 1000,
                    maxStack: 50,
                    userOnly: false
                }
            },
            placeholder: 'Digite o conteúdo completo do post com formatação rica...'
        });

        // Reference for image handler
        quill.imageHandler = imageHandler.bind({ quill });

        // Sync with hidden textarea
        const hiddenInput = document.getElementById('content');
        if (hiddenInput) {
            // Load initial content
            if (hiddenInput.value) {
                try {
                    // Try to parse as HTML
                    quill.root.innerHTML = hiddenInput.value;
                } catch (e) {
                    // Fallback to plain text
                    quill.setText(hiddenInput.value);
                }
            }

            // Update hidden input on change
            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML;
            });

            // Update on form submit
            const form = hiddenInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    hiddenInput.value = quill.root.innerHTML;
                });
            }
        }

        // Basic change handler
        quill.on('text-change', function() {
            if (hiddenInput) {
                hiddenInput.value = quill.root.innerHTML;
            }
        });

        initBlogBibleAssist(quill);
    }

    // Excerpt editor (simpler version)
    const excerptEditor = document.getElementById('blog-excerpt-editor');

    if (excerptEditor) {
        const excerptQuill = new Quill('#blog-excerpt-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            },
            placeholder: 'Breve resumo do post...',
        });

        const excerptHiddenInput = document.getElementById('excerpt');
        if (excerptHiddenInput) {
            if (excerptHiddenInput.value) {
                excerptQuill.root.innerHTML = excerptHiddenInput.value;
            }

            excerptQuill.on('text-change', function() {
                excerptHiddenInput.value = excerptQuill.root.innerHTML;
            });

            const form = excerptHiddenInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    excerptHiddenInput.value = excerptQuill.root.innerHTML;
                });
            }
        }
    }

    // Gallery management with storejs
    initializeGalleryManager();

    // Add custom styles for better UX
    addEditorStyles();
});

// Gallery Manager with Carrossel
function initializeGalleryManager() {
    const galleryInput = document.getElementById('gallery_images');
    const galleryPreview = document.getElementById('gallery-preview');
    const galleryImages = [];

    if (galleryInput && galleryPreview) {
        galleryInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);

            // Limit to 10 images
            if (files.length > 10) {
                alert('Máximo de 10 imagens permitidas na galeria.');
                return;
            }

            // Clear previous previews
            galleryPreview.innerHTML = '';

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imageData = {
                            file: file,
                            dataUrl: e.target.result,
                            index: index
                        };
                        galleryImages.push(imageData);
                        createGalleryPreview(imageData);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Save to localStorage for persistence
            localStorage.setItem('blog-gallery-draft', JSON.stringify(galleryImages.map(img => ({
                name: img.file.name,
                size: img.file.size,
                dataUrl: img.dataUrl
            }))));
        });

        // Load from localStorage if exists
        const savedGallery = localStorage.getItem('blog-gallery-draft');
        if (savedGallery) {
            try {
                const parsedGallery = JSON.parse(savedGallery);
                parsedGallery.forEach(item => {
                    createGalleryPreview(item);
                });
            } catch (e) {
                localStorage.removeItem('blog-gallery-draft');
            }
        }
    }

    function createGalleryPreview(imageData) {
        const previewItem = document.createElement('div');
        previewItem.className = 'relative group';
        previewItem.innerHTML = `
            <img src="${imageData.dataUrl}" alt="Gallery image" class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 dark:border-slate-600">
            <button type="button" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity" onclick="removeGalleryImage(${imageData.index})">
                ×
            </button>
        `;
        galleryPreview.appendChild(previewItem);
    }
}

// Remove gallery image
window.removeGalleryImage = function(index) {
    const galleryPreview = document.getElementById('gallery-preview');
    const items = galleryPreview.children;
    if (items[index]) {
        galleryPreview.removeChild(items[index]);
    }

    // Update localStorage
    const savedGallery = localStorage.getItem('blog-gallery-draft');
    if (savedGallery) {
        try {
            const parsedGallery = JSON.parse(savedGallery);
            parsedGallery.splice(index, 1);
            localStorage.setItem('blog-gallery-draft', JSON.stringify(parsedGallery));
        } catch (e) {
            localStorage.removeItem('blog-gallery-draft');
        }
    }
};

// Add custom styles for editor
function addEditorStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .blog-editor-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 1rem;
        }
        .blog-editor-wrapper .ql-toolbar.ql-snow {
            border: 2px solid rgb(209 213 219);
            border-radius: 0.75rem 0.75rem 0 0;
            background: white;
        }
        .dark .blog-editor-wrapper .ql-toolbar.ql-snow {
            border-color: rgb(51 65 85);
            background: rgb(51 65 85);
        }
        .blog-editor-wrapper .ql-container.ql-snow {
            border: 2px solid rgb(209 213 219);
            border-top: none;
            border-radius: 0 0 0.75rem 0.75rem;
            background: white;
            font-size: 1rem;
            min-height: 300px;
        }
        .dark .blog-editor-wrapper .ql-container.ql-snow {
            border-color: rgb(51 65 85);
            background: rgb(30 41 59);
            color: white;
        }
        .blog-editor-wrapper .ql-editor {
            min-height: 300px;
            color: rgb(17 24 39);
            padding: 1rem;
        }
        .dark .blog-editor-wrapper .ql-editor {
            color: white;
        }
        .blog-editor-wrapper .ql-editor.ql-blank::before {
            color: rgb(156 163 175);
            font-style: normal;
        }
        .dark .blog-editor-wrapper .ql-editor.ql-blank::before {
            color: rgb(148 163 184);
        }
        .blog-editor-wrapper .ql-snow .ql-stroke {
            stroke: rgb(107 114 128);
        }
        .dark .blog-editor-wrapper .ql-snow .ql-stroke {
            stroke: rgb(203 213 225);
        }
        .blog-editor-wrapper .ql-snow .ql-fill {
            fill: rgb(107 114 128);
        }
        .dark .blog-editor-wrapper .ql-snow .ql-fill {
            fill: rgb(203 213 225);
        }
        .blog-editor-wrapper .ql-snow .ql-picker-label {
            color: rgb(107 114 128);
        }
        .dark .blog-editor-wrapper .ql-snow .ql-picker-label {
            color: rgb(203 213 225);
        }

        /* Gallery styles */
        #gallery-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

    `;
    document.head.appendChild(style);
}

