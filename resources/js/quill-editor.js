import Quill from 'quill';
import 'quill/dist/quill.snow.css';

// Inicializar Quill Editor
document.addEventListener('DOMContentLoaded', function() {
    // Editor para Título
    const titleEditor = document.getElementById('quill-title');
    if (titleEditor) {
        const titleQuill = new Quill('#quill-title', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'font': [] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            },
            placeholder: 'Digite o título do slide...',
        });

        const titleHiddenInput = document.getElementById('title');
        if (titleHiddenInput) {
            if (titleHiddenInput.value) {
                titleQuill.root.innerHTML = titleHiddenInput.value;
            }

            titleQuill.on('text-change', function() {
                titleHiddenInput.value = titleQuill.root.innerHTML;
            });

            const form = titleHiddenInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    titleHiddenInput.value = titleQuill.root.innerHTML;
                });
            }
        }
    }

    // Editor para Descrição
    const descriptionEditor = document.getElementById('quill-description');
    
    if (descriptionEditor) {
        // Configuração do Quill com toolbar completa
        const quill = new Quill('#quill-description', {
            theme: 'snow',
            modules: {
                toolbar: [
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
                ]
            },
            placeholder: 'Digite o texto do slide com formatação rica...',
        });

        // Sincronizar conteúdo do Quill com textarea hidden
        const hiddenInput = document.getElementById('description');
        if (hiddenInput) {
            // Carregar conteúdo inicial se existir
            if (hiddenInput.value) {
                quill.root.innerHTML = hiddenInput.value;
            }

            // Atualizar textarea quando Quill mudar
            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML;
            });

            // Atualizar também no evento de form submit
            const form = hiddenInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    hiddenInput.value = quill.root.innerHTML;
                });
            }
        }

        // Adicionar estilos customizados para dark mode e layout
        const style = document.createElement('style');
        style.textContent = `
            .quill-editor-wrapper {
                position: relative;
                width: 100%;
                margin-bottom: 1rem;
            }
            .quill-editor-wrapper .ql-toolbar.ql-snow {
                border: 2px solid rgb(209 213 219);
                border-radius: 0.75rem 0.75rem 0 0;
                background: white;
            }
            .dark .quill-editor-wrapper .ql-toolbar.ql-snow {
                border-color: rgb(51 65 85);
                background: rgb(51 65 85);
            }
            .quill-editor-wrapper .ql-container.ql-snow {
                border: 2px solid rgb(209 213 219);
                border-top: none;
                border-radius: 0 0 0.75rem 0.75rem;
                background: white;
                font-size: 1rem;
            }
            .dark .quill-editor-wrapper .ql-container.ql-snow {
                border-color: rgb(51 65 85);
                background: rgb(30 41 59);
                color: white;
            }
            .quill-editor-wrapper .ql-editor {
                min-height: 150px;
                color: rgb(17 24 39);
            }
            #quill-description .ql-editor {
                min-height: 250px;
            }
            .dark .quill-editor-wrapper .ql-editor {
                color: white;
            }
            .quill-editor-wrapper .ql-editor.ql-blank::before {
                color: rgb(156 163 175);
            }
            .dark .quill-editor-wrapper .ql-editor.ql-blank::before {
                color: rgb(148 163 184);
            }
            .quill-editor-wrapper .ql-snow .ql-stroke {
                stroke: rgb(107 114 128);
            }
            .dark .quill-editor-wrapper .ql-snow .ql-stroke {
                stroke: rgb(203 213 225);
            }
            .quill-editor-wrapper .ql-snow .ql-fill {
                fill: rgb(107 114 128);
            }
            .dark .quill-editor-wrapper .ql-snow .ql-fill {
                fill: rgb(203 213 225);
            }
            .quill-editor-wrapper .ql-snow .ql-picker-label {
                color: rgb(107 114 128);
            }
            .dark .quill-editor-wrapper .ql-snow .ql-picker-label {
                color: rgb(203 213 225);
            }
        `;
        document.head.appendChild(style);
    }
});

