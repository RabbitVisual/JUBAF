/**
 * Editor visual para o corpo das atas (Secretaria) — Quill.
 * Não expõe HTML ao utilizador; o conteúdo guardado continua a ser HTML válido para PDF/visualização.
 */
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', () => {
    const mount = document.getElementById('quill-minute-body');
    if (! mount) {
        return;
    }

    const hidden = document.getElementById('minute-body');
    if (! hidden) {
        return;
    }

    const quill = new Quill('#quill-minute-body', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ header: [2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ indent: '-1' }, { indent: '+1' }],
                ['blockquote', 'link'],
                ['clean'],
            ],
        },
        placeholder:
            'Escreva aqui o texto da ata, como num documento de Word: títulos, listas e negrito. '
            + 'Não precisa de saber código — use os botões acima.',
    });

    if (hidden.value && hidden.value.trim() !== '') {
        quill.root.innerHTML = hidden.value;
    }

    const sync = () => {
        hidden.value = quill.root.innerHTML;
    };

    quill.on('text-change', sync);

    const form = hidden.closest('form');
    if (form) {
        form.addEventListener('submit', sync);
    }

    const style = document.createElement('style');
    style.textContent = `
        #quill-minute-body.quill-editor-wrapper .ql-toolbar.ql-snow {
            border: 2px solid rgb(209 213 219);
            border-radius: 0.75rem 0.75rem 0 0;
            background: white;
        }
        .dark #quill-minute-body.quill-editor-wrapper .ql-toolbar.ql-snow {
            border-color: rgb(51 65 85);
            background: rgb(51 65 85);
        }
        #quill-minute-body.quill-editor-wrapper .ql-container.ql-snow {
            border: 2px solid rgb(209 213 219);
            border-top: none;
            border-radius: 0 0 0.75rem 0.75rem;
            background: white;
            font-size: 1rem;
        }
        .dark #quill-minute-body.quill-editor-wrapper .ql-container.ql-snow {
            border-color: rgb(51 65 85);
            background: rgb(30 41 59);
            color: white;
        }
        #quill-minute-body .ql-editor {
            min-height: 320px;
            color: rgb(17 24 39);
        }
        .dark #quill-minute-body .ql-editor {
            color: rgb(248 250 252);
        }
        #quill-minute-body .ql-editor.ql-blank::before {
            color: rgb(156 163 175);
            font-style: normal;
        }
        .dark #quill-minute-body .ql-editor.ql-blank::before {
            color: rgb(148 163 184);
        }
        #quill-minute-body .ql-snow .ql-stroke {
            stroke: rgb(107 114 128);
        }
        .dark #quill-minute-body .ql-snow .ql-stroke {
            stroke: rgb(203 213 225);
        }
        #quill-minute-body .ql-snow .ql-fill {
            fill: rgb(107 114 128);
        }
        .dark #quill-minute-body .ql-snow .ql-fill {
            fill: rgb(203 213 225);
        }
        #quill-minute-body .ql-snow .ql-picker-label {
            color: rgb(107 114 128);
        }
        .dark #quill-minute-body .ql-snow .ql-picker-label {
            color: rgb(203 213 225);
        }
    `;
    document.head.appendChild(style);
});
