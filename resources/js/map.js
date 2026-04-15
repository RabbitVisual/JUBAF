/**
 * Sistema de Mapas VERTEXSEMAGRI - Google Maps Integration
 * Substitui o antigo sistema Leaflet
 */

// Função para obter a API Key (injetada globalmente via Blade template layout ou similar)
// Fallback para verificar se já existe window.initVertexGoogleMaps
const getApiKey = () => window.vertex?.mapsApiKey || '';

export function initGoogleMap(containerId, options = {}) {
    const defaults = {
        lat: -12.2336, // Coração de Maria - BA
        lng: -38.7454,
        zoom: 15,
        readonly: false,
        draggable: true,
        markerTitle: 'Local Selecionado'
    };

    const config = { ...defaults, ...options };
    const container = document.getElementById(containerId);

    if (!container) return;

    // Verificar se a API do Google Maps está carregada
    if (!window.google || !window.google.maps) {
        console.error('Google Maps API not loaded properly.');
        container.innerHTML = `<div class="p-4 text-center text-red-500 bg-red-50 rounded border border-red-200">Erro: Google Maps não carregado via API Key.</div>`;
        return;
    }

    const { Map } = google.maps;
    const { AdvancedMarkerElement } = google.maps.marker;

    // Opções do mapa
    const mapOptions = {
        center: { lat: config.lat, lng: config.lng },
        zoom: config.zoom,
        mapId: 'DEMANDA_MAP_ID', // Requerido para Advanced Markers
        mapTypeId: google.maps.MapTypeId.HYBRID,
        streetViewControl: true,
        mapTypeControl: true,
        fullscreenControl: true,
        zoomControl: true,
    };

    const map = new Map(container, mapOptions);
    let marker = null;

    // Função para criar/atualizar marcador
    const updateMarker = (position) => {
        if (marker) {
            marker.position = position;
        } else {
            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: config.markerTitle,
                draggable: !config.readonly && config.draggable,
                animation: google.maps.Animation.DROP
            });

            if (!config.readonly) {
                marker.addListener('dragend', (event) => {
                    const newPos = event.latLng;
                    if (config.onMarkerDragEnd) {
                        config.onMarkerDragEnd({
                            lat: newPos.lat(),
                            lng: newPos.lng()
                        });
                    }
                });
            }
        }
    };

    // Inicializar marcador
    updateMarker({ lat: config.lat, lng: config.lng });

    // Evento de clique no mapa (apenas se não for readonly)
    if (!config.readonly) {
        map.addListener('click', (event) => {
            const clickedPos = event.latLng;
            updateMarker(clickedPos);

            if (config.onMapClick) {
                config.onMapClick({
                    lat: clickedPos.lat(),
                    lng: clickedPos.lng()
                });
            }
        });
    }

    return map;
}

// Exportar para uso global
window.initVertexGoogleMapsApp = initGoogleMap;
