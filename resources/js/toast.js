/**
 * Sistema de Notificações Toast Profissionais VERTEXSEMAGRI
 * Substitui alert() por notificações toast elegantes
 */

/**
 * Exibe uma notificação toast
 * @param {string} message - Mensagem a ser exibida
 * @param {string} type - Tipo: 'success', 'error', 'warning', 'info' (padrão: 'info')
 * @param {number} duration - Duração em milissegundos (padrão: 5000, 0 = não fecha automaticamente)
 * @returns {HTMLElement} - Elemento do toast criado
 */
export function showToast(message, type = 'info', duration = 5000) {
    const toastId = 'vertex-toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);

    const icons = {
        success: `<svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>`,
        error: `<svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
        </svg>`,
        warning: `<svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>`,
        info: `<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>`
    };

    const colors = {
        success: {
            bg: 'bg-emerald-50 dark:bg-emerald-900/20',
            border: 'border-emerald-200 dark:border-emerald-800',
            text: 'text-emerald-800 dark:text-emerald-200',
            icon: 'text-emerald-600 dark:text-emerald-400'
        },
        error: {
            bg: 'bg-red-50 dark:bg-red-900/20',
            border: 'border-red-200 dark:border-red-800',
            text: 'text-red-800 dark:text-red-200',
            icon: 'text-red-600 dark:text-red-400'
        },
        warning: {
            bg: 'bg-amber-50 dark:bg-amber-900/20',
            border: 'border-amber-200 dark:border-amber-800',
            text: 'text-amber-800 dark:text-amber-200',
            icon: 'text-amber-600 dark:text-amber-400'
        },
        info: {
            bg: 'bg-blue-50 dark:bg-blue-900/20',
            border: 'border-blue-200 dark:border-blue-800',
            text: 'text-blue-800 dark:text-blue-200',
            icon: 'text-blue-600 dark:text-blue-400'
        }
    };

    const color = colors[type] || colors.info;
    const icon = icons[type] || icons.info;

    // Criar container de toasts se não existir
    let toastContainer = document.getElementById('vertex-toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'vertex-toast-container';
        toastContainer.className = 'fixed top-4 right-4 z-50 space-y-3 max-w-sm w-full';
        document.body.appendChild(toastContainer);
    }

    const toastHTML = `
        <div id="${toastId}" class="${color.bg} ${color.border} border-l-4 rounded-lg shadow-lg p-4 transform transition-all duration-300 ease-in-out opacity-0 translate-x-full">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${icon}
                </div>
                <div class="ml-3 flex-1">
                    <p class="${color.text} text-sm font-medium">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button type="button" onclick="document.getElementById('${toastId}').remove()" class="inline-flex ${color.text} hover:opacity-75 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    const toast = document.getElementById(toastId);

    // Animar entrada
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-x-full');
        toast.classList.add('opacity-100', 'translate-x-0');
    }, 10);

    // Fechar automaticamente se duration > 0
    if (duration > 0) {
        setTimeout(() => {
            closeToast(toast);
        }, duration);
    }

    return toast;
}

/**
 * Fecha um toast com animação
 * @param {HTMLElement} toast - Elemento do toast
 */
function closeToast(toast) {
    if (!toast) return;

    toast.classList.remove('opacity-100', 'translate-x-0');
    toast.classList.add('opacity-0', 'translate-x-full');

    setTimeout(() => {
        toast.remove();

        // Remover container se não houver mais toasts
        const container = document.getElementById('vertex-toast-container');
        if (container && container.children.length === 0) {
            container.remove();
        }
    }, 300);
}

// Funções de conveniência
export const toast = {
    success: (message, duration) => showToast(message, 'success', duration),
    error: (message, duration) => showToast(message, 'error', duration),
    warning: (message, duration) => showToast(message, 'warning', duration),
    info: (message, duration) => showToast(message, 'info', duration),
};

// Exportar para uso global
window.showToast = showToast;
window.toast = toast;

