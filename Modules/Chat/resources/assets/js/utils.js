/**
 * ==========================================================================
 * UTILITÁRIOS DO MÓDULO CHAT - VERTEX SEMAGRI
 * ==========================================================================
 */

/**
 * Debounce - Atrasa a execução de uma função
 * @param {Function} func - Função a ser executada
 * @param {number} wait - Tempo de espera em ms
 * @returns {Function}
 */
export function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func.apply(this, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Throttle - Limita a frequência de execução de uma função
 * @param {Function} func - Função a ser executada
 * @param {number} limit - Intervalo mínimo entre execuções em ms
 * @returns {Function}
 */
export function throttle(func, limit = 300) {
    let inThrottle;
    return function executedFunction(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

/**
 * Escapa HTML para prevenir XSS
 * @param {string} text - Texto a ser escapado
 * @returns {string}
 */
export function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Formata data para exibição relativa
 * @param {string|Date} date - Data a ser formatada
 * @returns {string}
 */
export function formatRelativeTime(date) {
    const now = new Date();
    const then = new Date(date);
    const diffMs = now - then;
    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHour = Math.floor(diffMin / 60);
    const diffDay = Math.floor(diffHour / 24);

    if (diffSec < 60) return 'agora';
    if (diffMin < 60) return `${diffMin}m`;
    if (diffHour < 24) return `${diffHour}h`;
    if (diffDay < 7) return `${diffDay}d`;

    return then.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit'
    });
}

/**
 * Formata hora para exibição
 * @param {string|Date} date - Data a ser formatada
 * @returns {string}
 */
export function formatTime(date) {
    return new Date(date).toLocaleTimeString('pt-BR', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Formata data completa
 * @param {string|Date} date - Data a ser formatada
 * @returns {string}
 */
export function formatDate(date) {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Formata CPF para exibição
 * @param {string} cpf - CPF a ser formatado
 * @returns {string}
 */
export function formatCpf(cpf) {
    if (!cpf) return '';
    const cleaned = cpf.replace(/\D/g, '');
    if (cleaned.length !== 11) return cpf;
    return cleaned.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}

/**
 * Mascara CPF para exibição pública
 * @param {string} cpf - CPF a ser mascarado
 * @returns {string}
 */
export function maskCpf(cpf) {
    if (!cpf) return '';
    const cleaned = cpf.replace(/\D/g, '');
    if (cleaned.length !== 11) return '***.***.***-**';
    return `${cleaned.substring(0, 3)}.***.***-${cleaned.substring(9)}`;
}

/**
 * Valida CPF
 * @param {string} cpf - CPF a ser validado
 * @returns {boolean}
 */
export function validateCpf(cpf) {
    const cleaned = cpf.replace(/\D/g, '');

    if (cleaned.length !== 11) return false;
    if (/^(\d)\1+$/.test(cleaned)) return false;

    let sum = 0;
    for (let i = 0; i < 9; i++) {
        sum += parseInt(cleaned.charAt(i)) * (10 - i);
    }
    let digit = 11 - (sum % 11);
    if (digit > 9) digit = 0;
    if (parseInt(cleaned.charAt(9)) !== digit) return false;

    sum = 0;
    for (let i = 0; i < 10; i++) {
        sum += parseInt(cleaned.charAt(i)) * (11 - i);
    }
    digit = 11 - (sum % 11);
    if (digit > 9) digit = 0;
    if (parseInt(cleaned.charAt(10)) !== digit) return false;

    return true;
}

/**
 * Gera um ID único
 * @returns {string}
 */
export function generateId() {
    return `${Date.now()}-${Math.random().toString(36).substring(2, 11)}`;
}

/**
 * Trunca texto com ellipsis
 * @param {string} text - Texto a ser truncado
 * @param {number} maxLength - Tamanho máximo
 * @returns {string}
 */
export function truncate(text, maxLength = 50) {
    if (!text || text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

/**
 * Faz scroll suave para o final de um elemento
 * @param {HTMLElement} element - Elemento para scroll
 */
export function scrollToBottom(element) {
    if (!element) return;
    element.scrollTo({
        top: element.scrollHeight,
        behavior: 'smooth'
    });
}

/**
 * Verifica se está no modo escuro
 * @returns {boolean}
 */
export function isDarkMode() {
    return document.documentElement.classList.contains('dark');
}

/**
 * Obtém o token CSRF
 * @returns {string}
 */
export function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

/**
 * Faz uma requisição fetch com tratamento de erros
 * @param {string} url - URL da requisição
 * @param {Object} options - Opções do fetch
 * @returns {Promise<Object>}
 */
export async function fetchJson(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
        },
        credentials: 'same-origin',
    };

    const mergedOptions = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers,
        },
    };

    try {
        const response = await fetch(url, mergedOptions);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || `HTTP error! status: ${response.status}`);
        }

        return data;
    } catch (error) {
        console.error('Fetch error:', error);
        throw error;
    }
}

/**
 * Armazena dados no localStorage com tratamento de erros
 * @param {string} key - Chave
 * @param {any} value - Valor
 */
export function setStorage(key, value) {
    try {
        localStorage.setItem(key, JSON.stringify(value));
    } catch (e) {
        console.warn('localStorage not available:', e);
    }
}

/**
 * Recupera dados do localStorage
 * @param {string} key - Chave
 * @param {any} defaultValue - Valor padrão
 * @returns {any}
 */
export function getStorage(key, defaultValue = null) {
    try {
        const item = localStorage.getItem(key);
        return item ? JSON.parse(item) : defaultValue;
    } catch (e) {
        console.warn('localStorage not available:', e);
        return defaultValue;
    }
}

/**
 * Remove dados do localStorage
 * @param {string} key - Chave
 */
export function removeStorage(key) {
    try {
        localStorage.removeItem(key);
    } catch (e) {
        console.warn('localStorage not available:', e);
    }
}

/**
 * Verifica se o navegador suporta notificações
 * @returns {boolean}
 */
export function supportsNotifications() {
    return 'Notification' in window;
}

/**
 * Solicita permissão para notificações
 * @returns {Promise<boolean>}
 */
export async function requestNotificationPermission() {
    if (!supportsNotifications()) return false;

    const permission = await Notification.requestPermission();
    return permission === 'granted';
}

/**
 * Exibe uma notificação
 * @param {string} title - Título
 * @param {Object} options - Opções
 */
export function showNotification(title, options = {}) {
    if (!supportsNotifications() || Notification.permission !== 'granted') {
        return;
    }

    const defaultOptions = {
        icon: '/favicon.svg',
        badge: '/favicon.svg',
        tag: 'chat-notification',
        renotify: true,
    };

    new Notification(title, { ...defaultOptions, ...options });
}

/**
 * Reproduz um som de notificação
 * @param {string} soundUrl - URL do som
 */
export function playNotificationSound(soundUrl = '/sounds/chat/notification.mp3') {
    try {
        const audio = new Audio(soundUrl);
        audio.volume = 0.5;
        audio.play().catch(() => {
            // Autoplay bloqueado pelo navegador
        });
    } catch (e) {
        console.warn('Could not play notification sound:', e);
    }
}

