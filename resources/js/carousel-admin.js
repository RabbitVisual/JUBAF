import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', function() {
    // Toggle Carousel
    const carouselToggle = document.getElementById('carouselToggle');
    const carouselStatus = document.getElementById('carouselStatus');

    if (carouselToggle) {
        carouselToggle.addEventListener('change', function() {
            const enabled = this.checked;
            const toggleRoute = carouselToggle.dataset.toggleRoute;

            fetch(toggleRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ enabled: enabled })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (carouselStatus) {
                        carouselStatus.textContent = enabled ? 'Ativado' : 'Desativado';
                    }
                    // Mostrar notificação de sucesso - Flowbite Alert
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-20 right-4 z-50 flex items-center p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800';
                    notification.setAttribute('role', 'alert');
                    notification.innerHTML = `
                        <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3 text-sm font-medium">${data.message}</div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/30" aria-label="Close" onclick="this.parentElement.remove()">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    `;
                    document.body.appendChild(notification);
                    setTimeout(() => notification.remove(), 3000);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                carouselToggle.checked = !enabled;
            });
        });
    }

    // Sortable para reordenar slides
    const slidesList = document.getElementById('slidesList');
    if (slidesList) {
        const reorderRoute = slidesList.dataset.reorderRoute;

        const sortable = Sortable.create(slidesList, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                const slides = Array.from(slidesList.querySelectorAll('.slide-item')).map((item, index) => ({
                    id: item.dataset.id,
                    order: index + 1
                }));

                fetch(reorderRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ slides: slides })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualizar números de ordem
                        slidesList.querySelectorAll('.slide-item').forEach((item, index) => {
                            const orderBadge = item.querySelector('[data-carousel-order-badge]');
                            if (orderBadge) {
                                orderBadge.textContent = '#' + (index + 1);
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro ao reordenar:', error);
                });
            }
        });
    }
});

