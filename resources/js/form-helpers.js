/**
 * Helpers para formulários - substitui confirms inline por modais profissionais
 */

document.addEventListener('DOMContentLoaded', function() {
    // Interceptar todos os forms com onsubmit que usam confirm()
    document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
        const originalOnsubmit = form.getAttribute('onsubmit');

        // Extrair mensagem do confirm
        const match = originalOnsubmit.match(/confirm\(['"]([^'"]+)['"]\)/);
        if (match) {
            const message = match[1];

            // Remover onsubmit original
            form.removeAttribute('onsubmit');

            // Adicionar novo handler
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                let confirmed = false;
                if (window.showConfirm) {
                    confirmed = await window.showConfirm(message, 'Confirmar Ação', 'Confirmar', 'Cancelar', 'warning');
                } else {
                    confirmed = confirm(message);
                }
                
                if (confirmed) {
                    form.submit();
                }
            });
        }
    });

    // Interceptar botões com onclick que usam confirm()
    document.querySelectorAll('button[onclick*="confirm"], a[onclick*="confirm"]').forEach(element => {
        const originalOnclick = element.getAttribute('onclick');

        // Extrair mensagem do confirm
        const match = originalOnclick.match(/confirm\(['"]([^'"]+)['"]\)/);
        if (match) {
            const message = match[1];

            // Remover onclick original
            element.removeAttribute('onclick');

            // Adicionar novo handler
            element.addEventListener('click', async function(e) {
                e.preventDefault();
                
                let confirmed = false;
                if (window.showConfirm) {
                    confirmed = await window.showConfirm(message, 'Confirmar Ação', 'Confirmar', 'Cancelar', 'warning');
                } else {
                    confirmed = confirm(message);
                }
                
                if (confirmed) {
                    // Executar ação original (pode ser submit de form ou navegação)
                    if (element.type === 'submit' || element.closest('form')) {
                        const form = element.closest('form');
                        if (form) {
                            form.submit();
                        }
                    } else if (element.tagName === 'A' && element.href) {
                        window.location.href = element.href;
                    }
                }
            });
        }
    });
});

