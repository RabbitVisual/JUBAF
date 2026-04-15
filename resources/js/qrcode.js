// QR Code Generator usando biblioteca qrcode instalada via npm
import QRCode from 'qrcode';

/**
 * Gera um QR Code e exibe em um elemento canvas
 * @param {string} text - Texto/URL para codificar
 * @param {string|HTMLElement} canvasId - ID do canvas ou elemento canvas
 * @param {object} options - Opções do QR Code
 */
export async function generateQRCode(text, canvasId, options = {}) {
    const defaultOptions = {
        width: 200,
        margin: 2,
        color: {
            dark: '#000000',
            light: '#FFFFFF'
        },
        errorCorrectionLevel: 'M'
    };

    const finalOptions = { ...defaultOptions, ...options };

    try {
        const canvas = typeof canvasId === 'string'
            ? document.getElementById(canvasId)
            : canvasId;

        if (!canvas) {
            console.error('Elemento canvas não encontrado:', canvasId);
            return;
        }

        await QRCode.toCanvas(canvas, text, finalOptions);
        return canvas;
    } catch (error) {
        console.error('Erro ao gerar QR Code:', error);
        throw error;
    }
}

/**
 * Gera QR Code como Data URL (para usar em img src)
 * @param {string} text - Texto/URL para codificar
 * @param {object} options - Opções do QR Code
 * @returns {Promise<string>} Data URL do QR Code
 */
export async function generateQRCodeDataURL(text, options = {}) {
    const defaultOptions = {
        width: 200,
        margin: 2,
        color: {
            dark: '#000000',
            light: '#FFFFFF'
        },
        errorCorrectionLevel: 'M'
    };

    const finalOptions = { ...defaultOptions, ...options };

    try {
        return await QRCode.toDataURL(text, finalOptions);
    } catch (error) {
        console.error('Erro ao gerar QR Code Data URL:', error);
        throw error;
    }
}

/**
 * Inicializa QR Codes na página
 */
export function initQRCodes() {
    document.querySelectorAll('[data-qrcode]').forEach(element => {
        const text = element.getAttribute('data-qrcode');
        const canvasId = element.getAttribute('data-qrcode-canvas') || element.id;

        if (text && canvasId) {
            generateQRCode(text, canvasId).catch(error => {
                console.error('Falha ao gerar QR Code:', error);
            });
        }
    });
}

// Auto-inicializar quando DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initQRCodes);
} else {
    initQRCodes();
}

// Exportar para uso global
window.generateQRCode = generateQRCode;
window.generateQRCodeDataURL = generateQRCodeDataURL;

