// Blog Admin JavaScript - Modal de Exclusão Otimizado
(function () {
    'use strict';


    let deletePostId = null;
    let deleteForm = null;
    let isInitialized = false;
    let cachedCsrfToken = null;

    /**
     * Tenta encontrar o token CSRF usando múltiplas estratégias com debug detalhado
     * @returns {string|null}
     */
    function findCsrfToken() {

        // Debug: Mostrar todas as meta tags encontradas

        // Estratégia 1: Meta tag (Padrão Laravel)
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');

        if (csrfMeta) {
            if (csrfMeta.content && csrfMeta.content.trim()) {
                return csrfMeta.content.trim();
            } else {
                console.warn('⚠️ Meta tag encontrada mas conteúdo vazio');
            }
        }

        // Estratégia 2: Input hidden existente em outros formulários
        const tokenInput = document.querySelector('input[name="_token"]');

        if (tokenInput) {
            if (tokenInput.value && tokenInput.value.trim()) {
                return tokenInput.value.trim();
            } else {
                console.warn('⚠️ Input encontrado mas valor vazio');
            }
        }

        // Estratégia 3: Variação comum de nome (csrf-token)
        const altTokenInput = document.querySelector('input[name="csrf-token"]');
        if (altTokenInput && altTokenInput.value && altTokenInput.value.trim()) {
            return altTokenInput.value.trim();
        }

        // Estratégia 4: Buscar em todos os inputs hidden
        const allHiddenInputs = document.querySelectorAll('input[type="hidden"]');

        for (let input of allHiddenInputs) {
            if (input.value && input.value.trim() && input.value.length > 10) {
                // Verificar se parece ser um token CSRF (contém caracteres aleatórios)
                if (input.value.match(/^[A-Za-z0-9+/=]{20,}$/)) {
                    return input.value.trim();
                }
            }
        }

        console.error('❌ NENHUM token CSRF encontrado após todas as estratégias');

        return null;
    }

    /**
     * Espera o token estar disponível (Retry Pattern com Promises)
     * @param {number} maxAttempts
     * @param {number} interval
     * @returns {Promise<string>}
     */
    function waitForCsrfToken(maxAttempts = 10, interval = 200) {
        return new Promise((resolve, reject) => {
            // Se já temos em cache, retorna imediatamente
            if (cachedCsrfToken) return resolve(cachedCsrfToken);

            let attempts = 0;

            const check = () => {
                attempts++;
                const token = findCsrfToken();

                if (token) {
                    cachedCsrfToken = token;
                    resolve(token);
                } else if (attempts >= maxAttempts) {
                    reject(new Error('Não foi possível encontrar o token CSRF após várias tentativas. Verifique se <meta name="csrf-token"> está presente no <head>.'));
                } else {
                    setTimeout(check, interval);
                }
            };

            check();
        });

    }

    // Função global para chamar o modal
    window.confirmDelete = async function (postId, postTitle) {

        if (!postId || !postTitle) {
            console.error('❌ Parâmetros inválidos para confirmDelete');
            return;
        }

        // Verificar se o DOM está pronto
        if (document.readyState !== 'complete') {
            console.warn('⚠️ DOM ainda não está completo. Aguardando...');
            await new Promise(resolve => {
                if (document.readyState === 'complete') {
                    resolve();
                } else {
                    window.addEventListener('load', resolve);
                    // Timeout de segurança
                    setTimeout(resolve, 3000);
                }
            });
        }

        deletePostId = postId;

        // Debug: Verificar se estamos no contexto correto

        // Atualizar textos do modal
        const titleEl = document.getElementById('delete-modal-title');
        const messageEl = document.getElementById('delete-modal-message');
        const modal = document.getElementById('delete-modal');

        if (titleEl) titleEl.textContent = 'Excluir Post';
        if (messageEl) messageEl.innerHTML = `Tem certeza que deseja excluir o post <strong>"${postTitle}"</strong>? Esta ação não pode ser desfeita.`;

        // Limpar formulário antigo se existir
        if (deleteForm && deleteForm.parentNode) {
            deleteForm.parentNode.removeChild(deleteForm);
        }

        try {
            // Estratégia alternativa: tentar obter token diretamente primeiro
            let token = findCsrfToken();

            if (!token) {
                // Tenta obter o token esperando
                token = await waitForCsrfToken(3, 200); // 3 tentativas de 200ms
            }

            if (token) {
                createAndAppendForm(postId, token);

                // Mostrar modal
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');
                    // Focar no botão cancelar para acessibilidade
                    const cancelBtn = document.getElementById('cancel-delete');
                    if (cancelBtn) cancelBtn.focus();
                }
            } else {
                throw new Error('Token CSRF não pôde ser obtido');
            }

        } catch (error) {
            console.error('Erro completo:', error);
            alert('Erro de Segurança: O sistema não conseguiu verificar sua autenticidade (Token CSRF ausente). Por favor, recarregue a página.');
        }
    };

    /**
     * URL DELETE do post: vem de <meta name="blog-post-destroy-url-template"> (admin vs diretoria).
     */
    function resolvePostDestroyUrl(postId) {
        const meta = document.querySelector('meta[name="blog-post-destroy-url-template"]');
        if (meta && meta.content && meta.content.includes('__ID__')) {
            return meta.content.replace('__ID__', String(postId));
        }
        return `/admin/blog/${postId}`;
    }

    function createAndAppendForm(postId, token) {
        deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = resolvePostDestroyUrl(postId);
        deleteForm.style.display = 'none';
        deleteForm.id = `delete-form-${postId}`;

        // Input CSRF
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = token;
        deleteForm.appendChild(csrfInput);

        // Input Method Spoofing (DELETE)
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        deleteForm.appendChild(methodInput);

        document.body.appendChild(deleteForm);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        }

        // Limpeza do DOM
        if (deleteForm && deleteForm.parentNode) {
            deleteForm.parentNode.removeChild(deleteForm);
            deleteForm = null;
        }
        deletePostId = null;
    }

    function executeDelete() {
        if (!deleteForm) {
            alert('Erro: Formulário não encontrado. Tente fechar e abrir o modal novamente.');
            return;
        }

        // Verificar token uma última vez
        const inputToken = deleteForm.querySelector('input[name="_token"]');
        if (!inputToken || !inputToken.value) {
            // Tenta recuperar de emergência
            const emergencyToken = findCsrfToken();
            if (emergencyToken) {
                if (inputToken) inputToken.value = emergencyToken;
                else {
                    const newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = '_token';
                    newInput.value = emergencyToken;
                    deleteForm.appendChild(newInput);
                }
            } else {
                alert('Sessão expirada ou inválida. Recarregue a página.');
                return;
            }
        }

        // Feedback visual (Loading)
        if (window.showGlobalLoading) {
            window.showGlobalLoading('Excluindo post...');
        }

        // Fallback de timeout para loading infinito
        setTimeout(() => {
            if (window.forceHideGlobalLoading) window.forceHideGlobalLoading();
        }, 10000);

        deleteForm.submit();
    }

    function attachEventListeners() {
        const cancelBtn = document.getElementById('cancel-delete');
        const confirmBtn = document.getElementById('confirm-delete');
        const modal = document.getElementById('delete-modal');

        // Remove listeners antigos (cloneNode hack) para evitar duplicação se init rodar 2x
        if (cancelBtn) {
            const newCancel = cancelBtn.cloneNode(true);
            cancelBtn.parentNode.replaceChild(newCancel, cancelBtn);
            newCancel.addEventListener('click', closeDeleteModal);
        }

        if (confirmBtn) {
            const newConfirm = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirm, confirmBtn);
            newConfirm.addEventListener('click', function (e) {
                e.preventDefault(); // Prevenir comportamento padrão
                executeDelete();
            });
        }

        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === this) closeDeleteModal();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    }

    function initializeBlogAdmin() {
        if (isInitialized) return;


        // Tenta buscar o token assim que carregar para deixar em cache
        const initialToken = findCsrfToken();
        if (initialToken) {
            cachedCsrfToken = initialToken;
        } else {
            console.warn('⚠️ Token CSRF não encontrado na inicialização - será procurado quando necessário');
        }

        attachEventListeners();
        isInitialized = true;
    }

    // Inicialização segura - múltiplas estratégias
    function safeInitialize() {
        // Estratégia 1: Se o documento já está pronto, inicializa imediatamente
        if (document.readyState === 'complete') {
            initializeBlogAdmin();
            return;
        }

        // Estratégia 2: Aguardar DOMContentLoaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                // Aguardar um pouco mais para garantir que tudo esteja carregado
                setTimeout(initializeBlogAdmin, 100);
            });
        } else {
            // Document está em 'interactive' - aguardar um pouco
            setTimeout(initializeBlogAdmin, 200);
        }

        // Estratégia 3: Fallback com window.load
        window.addEventListener('load', function () {
            if (!isInitialized) {
                initializeBlogAdmin();
            }
        });
    }

    // Iniciar inicialização segura
    safeInitialize();

})();
