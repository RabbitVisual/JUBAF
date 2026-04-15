/**
 * ==========================================================================
 * SISTEMA DE TOAST/NOTIFICAÇÕES - VERTEX SEMAGRI
 * ==========================================================================
 */

import { escapeHtml, generateId } from './utils.js';

class ToastManager {
    constructor() {
        this.container = null;
        this.toasts = new Map();
        this.defaultDuration = 5000;
        this.init();
    }

    init() {
        // Criar container se não existir
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'chat-toast-container';
            this.container.style.cssText = `
                position: fixed;
                bottom: 24px;
                right: 24px;
                z-index: 10001;
                display: flex;
                flex-direction: column;
                gap: 12px;
                max-width: 400px;
            `;
            document.body.appendChild(this.container);
        }
    }

    /**
     * Exibe um toast
     * @param {string} message - Mensagem
     * @param {string} type - Tipo (success, error, warning, info)
     * @param {Object} options - Opções adicionais
     * @returns {string} - ID do toast
     */
    show(message, type = 'info', options = {}) {
        const id = generateId();
        const duration = options.duration ?? this.defaultDuration;
        const title = options.title || this.getDefaultTitle(type);

        const toast = document.createElement('div');
        toast.id = `toast-${id}`;
        toast.className = `chat-toast ${type}`;
        toast.innerHTML = this.getToastHtml(id, title, message, type);
        toast.style.cssText = `
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border-left: 4px solid ${this.getTypeColor(type)};
            animation: slideInRight 0.3s ease;
            max-width: 100%;
        `;

        // Adicionar ao container
        this.container.appendChild(toast);
        this.toasts.set(id, toast);

        // Configurar evento de fechar
        const closeBtn = toast.querySelector('.toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.hide(id));
        }

        // Auto-hide
        if (duration > 0) {
            setTimeout(() => this.hide(id), duration);
        }

        return id;
    }

    /**
     * Esconde um toast
     * @param {string} id - ID do toast
     */
    hide(id) {
        const toast = this.toasts.get(id);
        if (!toast) return;

        toast.style.animation = 'slideInRight 0.3s ease reverse';
        setTimeout(() => {
            toast.remove();
            this.toasts.delete(id);
        }, 280);
    }

    /**
     * Esconde todos os toasts
     */
    hideAll() {
        this.toasts.forEach((_, id) => this.hide(id));
    }

    /**
     * Toast de sucesso
     */
    success(message, options = {}) {
        return this.show(message, 'success', options);
    }

    /**
     * Toast de erro
     */
    error(message, options = {}) {
        return this.show(message, 'error', { duration: 8000, ...options });
    }

    /**
     * Toast de aviso
     */
    warning(message, options = {}) {
        return this.show(message, 'warning', options);
    }

    /**
     * Toast de informação
     */
    info(message, options = {}) {
        return this.show(message, 'info', options);
    }

    getDefaultTitle(type) {
        const titles = {
            success: 'Sucesso',
            error: 'Erro',
            warning: 'Atenção',
            info: 'Informação',
        };
        return titles[type] || 'Notificação';
    }

    getTypeColor(type) {
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6',
        };
        return colors[type] || colors.info;
    }

    getTypeIcon(type) {
        const icons = {
            success: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`,
            error: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>`,
            warning: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>`,
            info: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>`,
        };
        return icons[type] || icons.info;
    }

    getToastHtml(id, title, message, type) {
        const iconBg = {
            success: 'rgba(16, 185, 129, 0.1)',
            error: 'rgba(239, 68, 68, 0.1)',
            warning: 'rgba(245, 158, 11, 0.1)',
            info: 'rgba(59, 130, 246, 0.1)',
        };

        return `
            <div style="
                display: flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: ${iconBg[type] || iconBg.info};
                color: ${this.getTypeColor(type)};
                flex-shrink: 0;
            ">
                <div style="width: 20px; height: 20px;">
                    ${this.getTypeIcon(type)}
                </div>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="
                    font-size: 14px;
                    font-weight: 600;
                    color: #1e293b;
                    margin-bottom: 2px;
                ">${escapeHtml(title)}</div>
                <div style="
                    font-size: 14px;
                    color: #64748b;
                ">${escapeHtml(message)}</div>
            </div>
            <button class="toast-close" style="
                display: flex;
                align-items: center;
                justify-content: center;
                width: 28px;
                height: 28px;
                border-radius: 50%;
                background: transparent;
                border: none;
                color: #94a3b8;
                cursor: pointer;
                flex-shrink: 0;
                transition: all 0.2s;
            " onmouseover="this.style.background='rgba(0,0,0,0.05)'" onmouseout="this.style.background='transparent'">
                <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
    }
}

// Instância global
const toast = new ToastManager();

export default toast;
export { ToastManager };

