/**
 * ==========================================================================
 * MÓDULO CHAT - VERTEX SEMAGRI
 * ==========================================================================
 * Arquivo principal de JavaScript do módulo de Chat
 *
 * @author VERTEX SEMAGRI
 * @version 2.0.0
 */

// Importar utilitários
import * as utils from './utils.js';

// Importar sistema de toast
import toast from './toast.js';

// Importar sistema de chat
import ChatSystem from './chat-system.js';

// Importar widget público
import ChatWidget from './widget.js';

// Exportar módulos
export { utils, toast, ChatSystem, ChatWidget };

// Disponibilizar globalmente
if (typeof window !== 'undefined') {
    window.ChatModule = {
        utils,
        toast,
        ChatSystem,
        ChatWidget,
    };

    // Atalhos convenientes
    window.showToast = toast.show.bind(toast);
    window.toastSuccess = toast.success.bind(toast);
    window.toastError = toast.error.bind(toast);
    window.toastWarning = toast.warning.bind(toast);
    window.toastInfo = toast.info.bind(toast);
}

// Auto-inicialização do widget (se configurado)
document.addEventListener('DOMContentLoaded', () => {
    // Verificar se deve inicializar o widget público
    const widgetConfig = window.chatWidgetConfig;
    if (widgetConfig && widgetConfig.autoInit) {
        const widget = new ChatWidget(widgetConfig);
        widget.init();
        window.chatWidget = widget;
    }

    console.log('[ChatModule] Carregado com sucesso');
});

