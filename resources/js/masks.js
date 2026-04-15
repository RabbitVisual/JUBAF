// Importar jQuery e jQuery Mask Plugin
import $ from 'jquery';
import 'jquery-mask-plugin';

// Tornar jQuery disponível globalmente
window.$ = window.jQuery = $;

// Função para aplicar máscaras
export function initMasks() {
    // Máscara de telefone brasileiro
    if ($('#solicitante_telefone').length) {
        $('#solicitante_telefone').mask('(00) 00000-0000');
    }
    
    // Máscara de CEP
    if ($('#cep').length) {
        $('#cep').mask('00000-000');
    }
    
    // Máscara de CPF
    if ($('.cpf-mask').length) {
        $('.cpf-mask').mask('000.000.000-00');
    }
    
    // Máscara de CPF específica para login
    if ($('#cpf').length) {
        $('#cpf').mask('000.000.000-00');
    }
    
    // Máscara de CNPJ
    if ($('.cnpj-mask').length) {
        $('.cnpj-mask').mask('00.000.000/0000-00');
    }
}

// Inicializar máscaras quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMasks);
} else {
    initMasks();
}

