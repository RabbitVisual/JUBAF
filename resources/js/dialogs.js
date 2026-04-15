/**
 * Diálogos modais JUBAF (substitui alert/confirm/prompt nativos)
 * z-index acima do loading global (#global-loading-overlay em z-[10030]).
 */

function hideGlobalLoading() {
    window.dispatchEvent(new CustomEvent('vertex-loading-hide'));
    window.dispatchEvent(new CustomEvent('jub-loading-hide'));
}

/** z-[10050]: acima do overlay de loading; backdrop escurecido por baixo do painel */
const JUBAF_MODAL_ROOT = 'fixed inset-0 z-[10050] overflow-y-auto';
const JUBAF_MODAL_BACKDROP = 'fixed inset-0 z-0 bg-slate-900/55 backdrop-blur-sm transition-opacity';
const JUBAF_MODAL_PANEL =
    'relative z-10 inline-block w-full max-w-lg transform overflow-hidden rounded-2xl border text-left align-bottom shadow-2xl transition-all dark:bg-slate-800 sm:my-8 sm:align-middle border-slate-200 bg-white dark:border-slate-600';

/**
 * Exibe um modal de alerta profissional
 * @param {string} message - Mensagem a ser exibida
 * @param {string} title - Título do modal (opcional)
 * @param {string} type - Tipo: 'info', 'success', 'warning', 'error' (padrão: 'info')
 * @returns {Promise<void>}
 */
export function showAlert(message, title = 'Atenção', type = 'info') {
    return new Promise((resolve) => {
        hideGlobalLoading();
        const modalId = 'vertex-alert-modal-' + Date.now();
        const icons = {
            info: `<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>`,
            success: `<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`,
            warning: `<svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>`,
            error: `<svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>`
        };

        const colors = {
            info: {
                bg: 'bg-blue-50 dark:bg-blue-900/20',
                border: 'border-blue-200 dark:border-blue-800',
                text: 'text-blue-800 dark:text-blue-200',
                button: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 dark:bg-blue-600 dark:hover:bg-blue-500'
            },
            success: {
                bg: 'bg-emerald-50 dark:bg-emerald-900/20',
                border: 'border-emerald-200 dark:border-emerald-800',
                text: 'text-emerald-800 dark:text-emerald-200',
                button: 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500 dark:bg-emerald-600 dark:hover:bg-emerald-700'
            },
            warning: {
                bg: 'bg-blue-50 dark:bg-blue-900/20',
                border: 'border-blue-200 dark:border-blue-800',
                text: 'text-blue-900 dark:text-blue-100',
                button: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 dark:bg-blue-600 dark:hover:bg-blue-500'
            },
            error: {
                bg: 'bg-red-50 dark:bg-red-900/20',
                border: 'border-red-200 dark:border-red-800',
                text: 'text-red-800 dark:text-red-200',
                button: 'bg-red-600 hover:bg-red-700 focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-700'
            }
        };

        const color = colors[type] || colors.info;
        const icon = icons[type] || icons.info;

        const modalHTML = `
            <div id="${modalId}" class="${JUBAF_MODAL_ROOT}" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex min-h-full items-end justify-center px-4 pt-4 pb-20 text-center sm:items-center sm:block sm:p-0">
                    <div class="${JUBAF_MODAL_BACKDROP}" aria-hidden="true" id="${modalId}-overlay"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="${JUBAF_MODAL_PANEL} sm:max-w-lg sm:w-full">
                        <div class="${color.bg} ${color.border} border-l-4 p-5">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">${icon}</div>
                                <div class="min-w-0 flex-1 text-left">
                                    <h3 class="text-lg font-semibold leading-snug ${color.text}" id="modal-title">${title}</h3>
                                    <div class="mt-2 text-sm ${color.text}">
                                        <p>${message}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-600 dark:bg-slate-900/40 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" onclick="this.closest('[id^=vertex-alert-modal]').remove(); document.body.style.overflow = '';" class="w-full inline-flex justify-center rounded-xl border border-transparent px-4 py-2.5 text-base font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-800 sm:ml-3 sm:w-auto sm:text-sm ${color.button} transition-colors">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        document.body.style.overflow = 'hidden';

        const modal = document.getElementById(modalId);
        const overlay = document.getElementById(modalId + '-overlay');
        
        const closeModal = () => {
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.remove();
                document.body.style.overflow = '';
                resolve();
            }, 200);
        };

        // Fechar ao clicar no overlay
        if (overlay) {
            overlay.addEventListener('click', closeModal);
        }

        // Fechar com Escape
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                closeModal();
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);

        // Fechar ao clicar no botão
        const button = modal.querySelector('button');
        if (button) {
            button.addEventListener('click', closeModal);
        }
    });
}

/**
 * Exibe um modal de confirmação profissional
 * @param {string} message - Mensagem a ser exibida
 * @param {string} title - Título do modal (opcional)
 * @param {string} confirmText - Texto do botão de confirmação (padrão: 'Confirmar')
 * @param {string} cancelText - Texto do botão de cancelar (padrão: 'Cancelar')
 * @param {string} type - Tipo: 'warning', 'danger', 'info' (padrão: 'warning')
 * @returns {Promise<boolean>} - true se confirmado, false se cancelado
 */
export function showConfirm(message, title = 'Confirmar Ação', confirmText = 'Confirmar', cancelText = 'Cancelar', type = 'warning') {
    return new Promise((resolve) => {
        hideGlobalLoading();
        const modalId = 'vertex-confirm-modal-' + Date.now();

        const icons = {
            warning: `<svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>`,
            danger: `<svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>`,
            info: `<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>`
        };

        const colors = {
            warning: {
                bg: 'bg-blue-50 dark:bg-blue-900/20',
                border: 'border-blue-200 dark:border-blue-800',
                text: 'text-blue-900 dark:text-blue-100',
                confirmButton:
                    'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 dark:bg-blue-600 dark:hover:bg-blue-500'
            },
            danger: {
                bg: 'bg-red-50 dark:bg-red-900/20',
                border: 'border-red-200 dark:border-red-800',
                text: 'text-red-800 dark:text-red-200',
                confirmButton: 'bg-red-600 hover:bg-red-700 focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-700'
            },
            info: {
                bg: 'bg-blue-50 dark:bg-blue-900/20',
                border: 'border-blue-200 dark:border-blue-800',
                text: 'text-blue-800 dark:text-blue-200',
                confirmButton: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 dark:bg-blue-600 dark:hover:bg-blue-500'
            }
        };

        const color = colors[type] || colors.warning;
        const icon = icons[type] || icons.warning;

        const modalHTML = `
            <div id="${modalId}" class="${JUBAF_MODAL_ROOT}" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex min-h-full items-end justify-center px-4 pt-4 pb-20 text-center sm:items-center sm:block sm:p-0">
                    <div class="${JUBAF_MODAL_BACKDROP}" aria-hidden="true" id="${modalId}-overlay"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="${JUBAF_MODAL_PANEL} sm:max-w-lg sm:w-full">
                        <div class="${color.bg} ${color.border} border-l-4 p-5">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">${icon}</div>
                                <div class="min-w-0 flex-1 text-left">
                                    <h3 class="text-lg font-semibold leading-snug ${color.text}" id="modal-title">${title}</h3>
                                    <div class="mt-2 text-sm ${color.text}">
                                        <p>${message}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-600 dark:bg-slate-900/40 sm:px-6 sm:flex sm:flex-row-reverse sm:gap-3">
                            <button type="button" data-confirm="true" class="w-full inline-flex justify-center rounded-xl border border-transparent px-4 py-2.5 text-base font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-800 sm:ml-3 sm:w-auto sm:text-sm ${color.confirmButton} transition-colors">
                                ${confirmText}
                            </button>
                            <button type="button" data-cancel="true" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-base font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 sm:mt-0 sm:ml-0 sm:w-auto sm:text-sm transition-colors">
                                ${cancelText}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        document.body.style.overflow = 'hidden';

        const modal = document.getElementById(modalId);
        const overlay = document.getElementById(modalId + '-overlay');
        
        const closeModal = (result) => {
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.remove();
                document.body.style.overflow = '';
                resolve(result);
            }, 200);
        };

        // Fechar ao clicar no overlay
        if (overlay) {
            overlay.addEventListener('click', () => closeModal(false));
        }

        // Botão confirmar
        const confirmBtn = modal.querySelector('[data-confirm="true"]');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => closeModal(true));
        }
        
        // Botão cancelar
        const cancelBtn = modal.querySelector('[data-cancel="true"]');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => closeModal(false));
        }

        // Fechar com Escape
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                closeModal(false);
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);
    });
}

/**
 * Exibe um modal de prompt profissional (input de texto)
 * @param {string} message - Mensagem a ser exibida
 * @param {string} title - Título do modal (opcional)
 * @param {string} defaultValue - Valor padrão do input (opcional)
 * @param {string} placeholder - Placeholder do input (opcional)
 * @param {string} confirmText - Texto do botão de confirmação (padrão: 'Confirmar')
 * @param {string} cancelText - Texto do botão de cancelar (padrão: 'Cancelar')
 * @returns {Promise<string|null>} - Valor digitado ou null se cancelado
 */
export function showPrompt(message, title = 'Informe o valor', defaultValue = '', placeholder = '', confirmText = 'Confirmar', cancelText = 'Cancelar') {
    return new Promise((resolve) => {
        hideGlobalLoading();
        const modalId = 'vertex-prompt-modal-' + Date.now();

        const modalHTML = `
            <div id="${modalId}" class="${JUBAF_MODAL_ROOT}" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex min-h-full items-end justify-center px-4 pt-4 pb-20 text-center sm:items-center sm:block sm:p-0">
                    <div class="${JUBAF_MODAL_BACKDROP}" aria-hidden="true" id="${modalId}-overlay"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="${JUBAF_MODAL_PANEL} sm:max-w-lg sm:w-full">
                        <div class="border-b border-slate-200 bg-white px-4 pt-5 pb-4 dark:border-slate-600 dark:bg-slate-800 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start sm:gap-4">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/40 sm:mx-0 sm:h-11 sm:w-11">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </div>
                                <div class="mt-3 w-full text-center sm:mt-0 sm:text-left">
                                    <h3 class="text-lg font-semibold leading-6 text-slate-900 dark:text-white" id="modal-title">${title}</h3>
                                    <div class="mt-2">
                                        <p class="mb-4 text-sm text-slate-600 dark:text-slate-400">${message}</p>
                                        <input type="text" id="${modalId}-input" value="${defaultValue}" placeholder="${placeholder}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-600 dark:bg-slate-900/40 sm:px-6 sm:flex sm:flex-row-reverse sm:gap-3">
                            <button type="button" data-confirm="true" class="w-full inline-flex justify-center rounded-xl border border-transparent bg-blue-600 px-4 py-2.5 text-base font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-slate-800 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                ${confirmText}
                            </button>
                            <button type="button" data-cancel="true" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-base font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 sm:mt-0 sm:ml-0 sm:w-auto sm:text-sm transition-colors">
                                ${cancelText}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        document.body.style.overflow = 'hidden';

        const modal = document.getElementById(modalId);
        const overlay = document.getElementById(modalId + '-overlay');
        const input = document.getElementById(modalId + '-input');
        
        // Focar no input
        if (input) {
            setTimeout(() => input.focus(), 100);
        }

        const closeModal = (result) => {
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.remove();
                document.body.style.overflow = '';
                resolve(result);
            }, 200);
        };

        // Fechar ao clicar no overlay
        if (overlay) {
            overlay.addEventListener('click', () => closeModal(null));
        }

        // Botão confirmar
        const confirmBtn = modal.querySelector('[data-confirm="true"]');
        if (confirmBtn && input) {
            confirmBtn.addEventListener('click', () => {
                closeModal(input.value);
            });
        }

        // Botão cancelar
        const cancelBtn = modal.querySelector('[data-cancel="true"]');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                closeModal(null);
            });
        }

        // Enter para confirmar
        if (input) {
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    closeModal(input.value);
                }
            });
        }

        // Fechar com Escape
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                closeModal(null);
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);
    });
}

// Exportar para uso global
window.showAlert = showAlert;
window.showConfirm = showConfirm;
window.showPrompt = showPrompt;

// Substituir funções nativas (opcional - pode ser removido se preferir usar explicitamente)
// window.alert = showAlert;
// window.confirm = showConfirm;
// window.prompt = showPrompt;

