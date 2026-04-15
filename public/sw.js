/**
 * Service Worker — JUBAF Painel de Líderes (PWA)
 * Cache do shell e rotas autenticadas do prefixo /lideres.
 *
 * @version 6.0.0
 */

// Sistema de Logging Condicional - Apenas em desenvolvimento
const isDevelopment = self.location.hostname === 'localhost' ||
    self.location.hostname === '127.0.0.1' ||
    self.location.hostname.includes('.local');

const logger = {
    log: (...args) => isDevelopment && console.log(...args),
    warn: (...args) => isDevelopment && console.warn(...args),
    error: (...args) => console.error(...args), // Erros sempre são logados
    info: (...args) => isDevelopment && console.info(...args)
};

const CACHE_VERSION = 'jubaf-lideres-v6.0';
const SHELL_CACHE = 'jubaf-shell-v6.0';
const DATA_CACHE = 'jubaf-data-v6.0';
const IMAGES_CACHE = 'jubaf-images-v6.0';

// App Shell - recursos críticos que devem estar sempre disponíveis
const APP_SHELL = [
    '/',
    '/lideres',
    '/lideres/dashboard',
    '/lideres/profile',
    '/lideres/chat/page',
    '/offline.html',
    '/manifest.json',
    '/icons/icon.svg',
];

// Rotas do painel de líderes (pré-cache; requer sessão válida no servidor)
const LIDERES_ROUTES = [
    '/lideres',
    '/lideres/dashboard',
    '/lideres/profile',
    '/lideres/chat/page',
];

// APIs opcionais para pré-cache (vazio: sem painel legado)
const API_ROUTES = [];

// Assets estáticos críticos
const STATIC_ASSETS = [
    '/build/assets/app.css',
    '/build/assets/app.js',
    '/favicon.svg'
];

// Instalação - Cache Agressivo
self.addEventListener('install', (event) => {
    logger.log('[SW v5.1] Instalando Service Worker com cache agressivo...');

    event.waitUntil(
        Promise.all([
            // 1. Cache do App Shell
            cacheAppShell(),
            // 2. Pré-cachear rotas do painel de líderes
            preCacheLideresRoutes(),
            // 3. Pré-cachear APIs
            preCacheAPIs(),
            // 4. Pré-cachear assets estáticos
            preCacheStaticAssets()
        ]).then(() => {
            logger.log('[SW v5.1] ✅ Instalação completa - PWA 100% offline habilitada');
            logger.log('[SW v5.1] 📦 Sidebar e todas as rotas cacheadas');
            return self.skipWaiting();
        }).catch((error) => {
            logger.error('[SW v5.1] ❌ Erro na instalação:', error);
        })
    );
});

// Cache do App Shell
async function cacheAppShell() {
    const cache = await caches.open(SHELL_CACHE);
    logger.log('[SW v5.1] Cacheando App Shell...');

    const promises = APP_SHELL.map(async (url) => {
        try {
            const response = await fetch(url, {
                cache: 'no-cache',
                credentials: 'same-origin'
            });
            if (response.ok) {
                await cache.put(url, response);
                logger.log('[SW v5.1] ✅ Shell cached:', url);
                return true;
            }
        } catch (error) {
            logger.warn('[SW v5.1] ⚠️ Falha ao cachear shell:', url, error);
        }
        return false;
    });

    await Promise.all(promises);
    logger.log('[SW v5.1] App Shell cacheado');
}

// Pré-cachear rotas do painel de líderes
async function preCacheLideresRoutes() {
    const cache = await caches.open(CACHE_VERSION);
    logger.log('[SW v5.1] Pré-cacheando rotas /lideres...');

    const promises = LIDERES_ROUTES.map(async (route) => {
        try {
            const response = await fetch(route, {
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (response.ok) {
                await cache.put(route, response.clone());
                logger.log('[SW v5.1] ✅ Rota cacheada:', route);
                // Assets serão cacheados automaticamente quando a página for carregada
                return true;
            }
        } catch (error) {
            logger.warn('[SW v5.1] ⚠️ Falha ao cachear rota:', route, error);
        }
        return false;
    });

    await Promise.all(promises);
    logger.log('[SW v5.1] Rotas /lideres pré-cacheadas');
}

// Pré-cachear APIs
async function preCacheAPIs() {
    const cache = await caches.open(DATA_CACHE);
    logger.log('[SW v5.1] Pré-cacheando APIs...');

    const promises = API_ROUTES.map(async (api) => {
        try {
            const response = await fetch(api, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (response.ok) {
                await cache.put(api, response.clone());
                logger.log('[SW v5.1] ✅ API cacheada:', api);
                return true;
            }
        } catch (error) {
            logger.warn('[SW v5.1] ⚠️ Falha ao cachear API:', api, error);
        }
        return false;
    });

    await Promise.all(promises);
    logger.log('[SW v5.1] APIs pré-cacheadas');
}

// Pré-cachear assets estáticos
async function preCacheStaticAssets() {
    const cache = await caches.open(SHELL_CACHE);
    logger.log('[SW v5.1] Pré-cacheando assets estáticos...');

    const promises = STATIC_ASSETS.map(async (asset) => {
        try {
            const response = await fetch(asset);
            if (response.ok) {
                await cache.put(asset, response);
                logger.log('[SW v5.1] ✅ Asset cacheado:', asset);
                return true;
            }
        } catch (error) {
            logger.warn('[SW v5.1] ⚠️ Falha ao cachear asset:', asset, error);
        }
        return false;
    });

    await Promise.all(promises);
    logger.log('[SW v5.1] Assets estáticos pré-cacheados');
}

// Nota: Assets (CSS, JS, imagens) são cacheados automaticamente
// quando as páginas são carregadas pelo navegador.
// O Service Worker intercepta essas requisições e as cacheia
// usando a estratégia Cache First definida no event listener 'fetch'.

// Ativação - limpar caches antigos e forçar atualização
self.addEventListener('activate', (event) => {
    logger.log('[SW v5.1] Ativando Service Worker...');

    event.waitUntil(
        caches.keys().then((cacheNames) => {
            const currentCaches = [CACHE_VERSION, SHELL_CACHE, DATA_CACHE, IMAGES_CACHE];
            const oldCaches = cacheNames.filter(name => !currentCaches.includes(name));

            return Promise.all(
                oldCaches.map((name) => {
                    logger.log('[SW v5.1] 🗑️ Removendo cache antigo:', name);
                    return caches.delete(name);
                })
            );
        }).then(() => {
            logger.log('[SW v5.1] ✅ Ativação completa - Cache atualizado');
            // Forçar atualização de todas as páginas principais para garantir sidebar atualizado
            return Promise.all([
                self.clients.claim(),
                cachePages([
                    '/lideres',
                    '/lideres/dashboard',
                    '/lideres/profile',
                    '/lideres/chat/page',
                ])
            ]);
        })
    );
});

// Interceptar requisições
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Ignorar requisições não-HTTP
    if (!url.protocol.startsWith('http')) {
        return;
    }

    // Ignorar requisições de outros domínios
    if (url.origin !== self.location.origin) {
        return;
    }

    // Ignorar requisições de desenvolvimento (hot reload, etc)
    if (url.pathname.includes('__webpack') || url.pathname.includes('hot-update')) {
        return;
    }

    // IGNORAR rotas de autenticação e personificação!
    // Isso evita que o SW interfira nos redirecionamentos (302) que trocam a sessão do usuário.
    // Sem isso, o SW pode causar erros de "redirect mode" ou loops de logout.
    const bypassRoutes = [
        'login-as',
        'stop-impersonation',
        'logout',
        'login',
        'senha/comprovante'
    ];

    if (bypassRoutes.some(route => url.pathname.includes(route))) {
        logger.log('[SW v5.1] ⏭️ Ignorando rota de sessão:', url.pathname);
        return;
    }

    // POST/PUT/DELETE - tentar enviar, se falhar salvar para sync
    if (request.method !== 'GET' && request.method !== 'HEAD') {
        event.respondWith(handleMutatingRequest(request));
        return;
    }

    // APIs - Network First com cache robusto
    if (url.pathname.startsWith('/api/')) {
        event.respondWith(networkFirstWithCache(request, DATA_CACHE));
        return;
    }

    // Painel de líderes - Cache First (já pré-cacheadas quando autenticado)
    if (url.pathname.startsWith('/lideres')) {
        event.respondWith(cacheFirstWithNetwork(request, CACHE_VERSION));
        return;
    }

    // Assets estáticos - Cache First
    if (isStaticAsset(url.pathname)) {
        event.respondWith(cacheFirstWithNetwork(request, SHELL_CACHE));
        return;
    }

    // Imagens - Cache First
    if (isImage(url.pathname)) {
        event.respondWith(cacheFirstWithNetwork(request, IMAGES_CACHE));
        return;
    }

    // Outros - Network First com cache
    event.respondWith(networkFirstWithCache(request, CACHE_VERSION));
});

// Verificar se é asset estático
function isStaticAsset(pathname) {
    return /\.(js|css|woff|woff2|ttf|eot|json)$/i.test(pathname) ||
        pathname.startsWith('/build/') ||
        pathname.startsWith('/icons/') ||
        pathname === '/manifest.json';
}

// Verificar se é imagem
function isImage(pathname) {
    return /\.(png|jpg|jpeg|gif|svg|ico|webp|avif)$/i.test(pathname) ||
        pathname.startsWith('/storage/');
}

// Estratégia Cache First com Network fallback
async function cacheFirstWithNetwork(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cachedResponse = await cache.match(request);

    // Se tem no cache, retornar imediatamente
    if (cachedResponse) {
        // Em background, tentar atualizar do network
        fetch(request).then((networkResponse) => {
            if (networkResponse.ok) {
                cache.put(request, networkResponse.clone());
            }
        }).catch(() => {
            // Ignorar erros de atualização em background
        });

        return cachedResponse;
    }

    // Se não tem no cache, tentar network
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        // Se offline e não tem cache, retornar página offline
        if (request.headers.get('accept')?.includes('text/html')) {
            return serveOfflinePage(request);
        }

        // Para outros tipos, retornar erro
        return new Response('Recurso não disponível offline', {
            status: 503,
            statusText: 'Service Unavailable'
        });
    }
}

// Estratégia Network First com cache
async function networkFirstWithCache(request, cacheName) {
    const cache = await caches.open(cacheName);

    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        // Tentar cache
        const cachedResponse = await cache.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        // Para APIs, retornar JSON vazio
        if (request.url.includes('/api/')) {
            return new Response(JSON.stringify({
                offline: true,
                data: [],
                message: 'Dados offline não disponíveis. Conecte-se à internet para sincronizar.'
            }), {
                headers: { 'Content-Type': 'application/json' }
            });
        }

        return serveOfflinePage(request);
    }
}

// Servir página offline
async function serveOfflinePage(request) {
    const url = new URL(request.url);

    // Página offline estática
    const cache = await caches.open(SHELL_CACHE);
    const offlineResponse = await cache.match('/offline.html');

    if (offlineResponse) {
        return offlineResponse;
    }

    // Criar página offline inline
    return createOfflinePage(url.pathname);
}

// Criar página offline inline
function createOfflinePage(pathname) {
    const html = `<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUBAF — Offline</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
            padding: 20px;
        }
        .header {
            background: rgba(0,0,0,0.2);
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo { font-size: 20px; font-weight: bold; }
        .badge {
            background: #ef4444;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            max-width: 500px;
            margin: 0 auto;
        }
        .icon {
            width: 100px;
            height: 100px;
            margin-bottom: 24px;
            opacity: 0.9;
        }
        h1 { font-size: 28px; margin-bottom: 12px; font-weight: 700; }
        p { opacity: 0.95; margin-bottom: 32px; line-height: 1.6; font-size: 16px; }
        .btn {
            background: white;
            color: #f97316;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            margin: 8px;
            display: inline-block;
            transition: transform 0.2s;
        }
        .btn:hover { transform: scale(1.05); }
        .info {
            background: rgba(255,255,255,0.15);
            padding: 20px;
            border-radius: 12px;
            margin-top: 32px;
            font-size: 14px;
            backdrop-filter: blur(10px);
        }
        #ordens-list {
            margin-top: 32px;
            width: 100%;
        }
        .ordem-item {
            background: rgba(255,255,255,0.2);
            padding: 16px;
            margin: 12px 0;
            border-radius: 12px;
            text-align: left;
            backdrop-filter: blur(10px);
        }
        .ordem-numero { font-weight: bold; font-size: 16px; margin-bottom: 4px; }
        .ordem-status { font-size: 14px; opacity: 0.9; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">📱 JUBAF</div>
        <div class="badge">OFFLINE</div>
    </div>

    <div class="container">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18.364 5.636a9 9 0 010 12.728m-3.536-3.536a4 4 0 000-5.656m-7.071 7.071a9 9 0 010-12.728m3.536 3.536a4 4 0 000 5.656"/>
            <line x1="2" y1="2" x2="22" y2="22" stroke-width="3"/>
        </svg>

        <h1>Você está Offline</h1>
        <p>Não se preocupe! O aplicativo funciona completamente offline. Seus dados estão salvos localmente e serão sincronizados automaticamente quando a conexão voltar.</p>

        <div>
            <a href="javascript:location.reload()" class="btn">🔄 Tentar Novamente</a>
            <a href="/lideres/dashboard" class="btn" style="background: rgba(255,255,255,0.2); color: white;">📋 Painel</a>
        </div>

        <div class="info">
            <strong>💡 Dica:</strong> O aplicativo foi pré-carregado e funciona 100% offline. Você pode trabalhar normalmente mesmo sem internet!
        </div>

        <div id="ordens-list">
            <h3 style="margin-bottom: 16px; font-size: 18px;">📋 Ordens Disponíveis Offline:</h3>
            <div id="ordens-container">Carregando...</div>
        </div>
    </div>

    <script>
        async function loadCachedOrdens() {
            try {
                const request = indexedDB.open('JubafOfflineShellDB', 2);
                request.onsuccess = function() {
                    const db = request.result;
                    if (!db.objectStoreNames.contains('ordensCache')) {
                        document.getElementById('ordens-container').innerHTML =
                            '<p style="opacity:0.8; font-size: 14px;">Nenhuma ordem em cache ainda.</p>';
                        return;
                    }
                    const tx = db.transaction('ordensCache', 'readonly');
                    const store = tx.objectStore('ordensCache');
                    const getAllRequest = store.getAll();

                    getAllRequest.onsuccess = function() {
                        const ordens = getAllRequest.result || [];
                        const container = document.getElementById('ordens-container');

                        if (ordens.length === 0) {
                            container.innerHTML = '<p style="opacity:0.8; font-size: 14px;">Nenhuma ordem disponível offline.</p>';
                            return;
                        }

                        container.innerHTML = ordens.slice(0, 5).map(o =>
                            '<div class="ordem-item">' +
                                '<div class="ordem-numero">' + (o.numero || 'OS #' + o.id) + '</div>' +
                                '<div class="ordem-status">Status: ' + (o.status_texto || o.status || 'Pendente') + '</div>' +
                            '</div>'
                        ).join('');

                        if (ordens.length > 5) {
                            container.innerHTML += '<p style="opacity:0.8; margin-top: 12px; font-size: 14px;">E mais ' + (ordens.length - 5) + ' ordem(ns)...</p>';
                        }
                    };
                };
            } catch (e) {
                console.log('Erro ao carregar ordens:', e);
            }
        }

        loadCachedOrdens();

        // Verificar conexão e recarregar quando voltar
        window.addEventListener('online', () => {
            setTimeout(() => location.reload(), 1000);
        });
    </script>
</body>
</html>`;

    return new Response(html, {
        status: 200,
        headers: {
            'Content-Type': 'text/html; charset=utf-8',
            'Cache-Control': 'no-cache'
        }
    });
}

// Handler para requisições de mutação (POST, PUT, DELETE)
async function handleMutatingRequest(request) {
    try {
        const response = await fetch(request.clone());
        return response;
    } catch (error) {
        logger.log('[SW v5.1] Offline - salvando para sync:', request.url);

        // Salvar para sincronização posterior
        await saveForLaterSync(request.clone());

        return new Response(JSON.stringify({
            success: true,
            offline: true,
            message: 'Ação salva para sincronização quando online',
            synced: false
        }), {
            status: 200,
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Salvar para sincronização posterior
async function saveForLaterSync(request) {
    try {
        const db = await openDB();
        const body = await request.text();

        const data = {
            uuid: 'sw_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9),
            url: request.url,
            method: request.method,
            body: body,
            headers: Object.fromEntries(request.headers.entries()),
            timestamp: Date.now(),
            synced: false,
            retries: 0
        };

        return new Promise((resolve, reject) => {
            const tx = db.transaction('pendingActions', 'readwrite');
            const store = tx.objectStore('pendingActions');
            store.add(data);
            tx.oncomplete = () => resolve();
            tx.onerror = () => reject(tx.error);
        });
    } catch (e) {
        console.error('[SW v5.1] Erro ao salvar para sync:', e);
    }
}

// Abrir IndexedDB
function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('JubafOfflineShellDB', 2);

        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);

        request.onupgradeneeded = (event) => {
            const db = event.target.result;

            if (!db.objectStoreNames.contains('pendingActions')) {
                const store = db.createObjectStore('pendingActions', { keyPath: 'uuid' });
                store.createIndex('synced', 'synced', { unique: false });
                store.createIndex('timestamp', 'timestamp', { unique: false });
            }

            if (!db.objectStoreNames.contains('ordensCache')) {
                const ordensStore = db.createObjectStore('ordensCache', { keyPath: 'id' });
                ordensStore.createIndex('status', 'status', { unique: false });
            }

            if (!db.objectStoreNames.contains('materiaisCache')) {
                db.createObjectStore('materiaisCache', { keyPath: 'id' });
            }

            if (!db.objectStoreNames.contains('fotosPendentes')) {
                const fotosStore = db.createObjectStore('fotosPendentes', { keyPath: 'uuid' });
                fotosStore.createIndex('ordemId', 'ordemId', { unique: false });
                fotosStore.createIndex('synced', 'synced', { unique: false });
            }

            if (!db.objectStoreNames.contains('syncHistory')) {
                const historyStore = db.createObjectStore('syncHistory', { keyPath: 'uuid' });
                historyStore.createIndex('syncedAt', 'syncedAt', { unique: false });
            }
        };
    });
}

// Mensagens do cliente
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'CACHE_PAGES') {
        const pages = event.data.pages || LIDERES_ROUTES;
        cachePages(pages);
    }

    if (event.data && event.data.type === 'FORCE_SYNC') {
        // Forçar sincronização em background
        syncPendingData();
    }
});

// Cachear páginas sob demanda
async function cachePages(pages) {
    const cache = await caches.open(CACHE_VERSION);

    for (const page of pages) {
        try {
            const response = await fetch(page, { credentials: 'same-origin' });
            if (response.ok) {
                await cache.put(page, response);
                logger.log('[SW v5.1] ✅ Página cacheada:', page);
            }
        } catch (e) {
            logger.warn('[SW v5.1] ⚠️ Falha ao cachear página:', page);
        }
    }

    // Notificar clientes
    self.clients.matchAll().then((clients) => {
        clients.forEach((client) => {
            client.postMessage({ type: 'CACHE_COMPLETE' });
        });
    });
}

// Sincronizar dados pendentes em background
async function syncPendingData() {
    if (self.syncInProgress) return;
    self.syncInProgress = true;

    try {
        const db = await openDB();
        const tx = db.transaction('pendingActions', 'readonly');
        const store = tx.objectStore('pendingActions');
        const index = store.index('synced');
        const request = index.getAll(false);

        request.onsuccess = async () => {
            const pending = request.result || [];
            logger.log('[SW v5.1] 🔄 Sincronizando', pending.length, 'ações pendentes...');

            for (const action of pending) {
                try {
                    const response = await fetch(action.url, {
                        method: action.method,
                        body: action.body,
                        headers: action.headers
                    });

                    if (response.ok) {
                        // Marcar como sincronizado
                        const updateTx = db.transaction('pendingActions', 'readwrite');
                        const updateStore = updateTx.objectStore('pendingActions');
                        action.synced = true;
                        await updateStore.put(action);
                        logger.log('[SW v5.1] ✅ Ação sincronizada:', action.uuid);
                    }
                } catch (error) {
                    logger.warn('[SW v5.1] ⚠️ Erro ao sincronizar ação:', action.uuid, error);
                }
            }

            self.syncInProgress = false;
        };
    } catch (error) {
        logger.error('[SW v5.1] ❌ Erro na sincronização:', error);
        self.syncInProgress = false;
    }
}

// Sincronização periódica quando online
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-pending-data') {
        event.waitUntil(syncPendingData());
    }
});

// Background sync quando voltar online
self.addEventListener('online', () => {
    syncPendingData();
});

// Mensagens do Service Worker para o cliente
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'CACHE_PAGES') {
        const pages = event.data.pages || LIDERES_ROUTES;
        cachePages(pages);
    }

    if (event.data && event.data.type === 'FORCE_SYNC') {
        syncPendingData();
    }

    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: CACHE_VERSION });
    }
});

// Notificar clientes sobre atualizações
self.addEventListener('activate', (event) => {
    event.waitUntil(
        self.clients.matchAll({ includeUncontrolled: true }).then((clients) => {
            clients.forEach((client) => {
                client.postMessage({
                    type: 'SW_ACTIVATED',
                    version: CACHE_VERSION
                });
            });
        })
    );
});

// Limpeza automática de cache antigo (manter apenas últimos 5 caches)
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            const currentCaches = [CACHE_VERSION, SHELL_CACHE, DATA_CACHE, IMAGES_CACHE];
            const oldCaches = cacheNames.filter(name => !currentCaches.includes(name));

            // Ordenar por data e manter apenas os 5 mais recentes
            return Promise.all(
                oldCaches.map((name) => {
                    console.log('[SW v4] 🗑️ Removendo cache antigo:', name);
                    return caches.delete(name);
                })
            );
        }).then(() => {
            console.log('[SW v4] ✅ Ativação completa');
            return self.clients.claim();
        })
    );
});

// Verificar atualizações periodicamente
setInterval(() => {
    self.registration.update();
}, 60 * 60 * 1000); // A cada 1 hora

console.log('[SW] Service Worker JUBAF carregado');
console.log('[SW] Versão:', CACHE_VERSION);
console.log('[SW] Verificação de atualização a cada 1 hora');
