// Validações dinâmicas entre módulos
document.addEventListener('DOMContentLoaded', function() {
    // Validação de localidade antes de criar demanda
    const localidadeSelect = document.getElementById('localidade_id');
    if (localidadeSelect) {
        localidadeSelect.addEventListener('change', function() {
            if (!this.value) {
                showLocalidadeModal();
            }
        });
    }

    // Validação ao submeter formulário de demanda
    const demandaForm = document.querySelector('form[action*="demandas"]');
    if (demandaForm) {
        demandaForm.addEventListener('submit', function(e) {
            const localidadeId = document.getElementById('localidade_id');
            if (localidadeId && !localidadeId.value) {
                e.preventDefault();
                showLocalidadeModal();
            }
        });
    }

    // Validação de estoque ao usar material em OS
    const materialSelect = document.querySelector('select[name*="material"]');
    if (materialSelect) {
        materialSelect.addEventListener('change', function() {
            const materialId = this.value;
            if (materialId) {
                checkMaterialStock(materialId);
            }
        });
    }
});

function showLocalidadeModal() {
    const modal = new bootstrap.Modal(document.getElementById('localidadeRequiredModal'));
    if (!document.getElementById('localidadeRequiredModal')) {
        createLocalidadeModal();
    }
    modal.show();
}

function createLocalidadeModal() {
    const modalHtml = `
        <div class="modal fade" id="localidadeRequiredModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Localidade Obrigatória
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Para criar uma demanda, é necessário cadastrar uma localidade primeiro.</p>
                        <p class="mb-0">Deseja cadastrar uma localidade agora?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <a href="{{ route('localidades.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Cadastrar Localidade
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

function checkMaterialStock(materialId) {
    // Fazer requisição AJAX para verificar estoque
    fetch(`/api/materiais/${materialId}/estoque`)
        .then(response => {
            // Verificar se a resposta é JSON antes de fazer parse
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Se não for JSON, provavelmente é uma página de erro HTML
                return Promise.reject(new Error('Resposta não é JSON'));
            }
            return response.json();
        })
        .then(data => {
            if (data && data.estoque_baixo) {
                showStockAlert(data);
            }
        })
        .catch(error => {
            // Silenciar erro se a rota não existir (não é crítico)
            if (error.message !== 'Resposta não é JSON') {
                console.error('Erro ao verificar estoque:', error);
            }
        });
}

function showStockAlert(data) {
    const alert = `
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Atenção!</strong> Estoque baixo: ${data.quantidade} unidades disponíveis.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    const form = document.querySelector('form');
    if (form) {
        form.insertAdjacentHTML('afterbegin', alert);
    }
}

