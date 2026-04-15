import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Assets principais
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/offline/init.js',
                'resources/js/quill-editor.js',
                'resources/js/secretaria-minute-editor.js',
                'resources/js/map.js',
                'resources/js/carousel-admin.js',
                'resources/js/qrcode.js',
                'resources/js/chat.js',
                'resources/js/notifications.js',
                'resources/js/blog-admin.js',
                'resources/js/blog-editor.js',
                // Módulo Chat
                'Modules/Chat/resources/assets/sass/app.scss',
                'Modules/Chat/resources/assets/js/app.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
        cors: {
            origin: '*',
            credentials: true,
        },
        // Evita que alterações em views compiladas / cache disparem HMR durante desenvolvimento
        watch: {
            ignored: ['**/storage/**', '**/bootstrap/cache/**'],
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    // Separar vendor chunks para reduzir tamanho e evitar warnings
                    if (id.includes('node_modules')) {
                        // Preline UI em chunk separado
                        if (id.includes('preline/preline') || id.includes('preline/dist')) {
                            return 'preline-core';
                        }
                        // Plugins Preline em chunk separado
                        if (id.includes('@preline')) {
                            return 'preline-plugins';
                        }
                        // Outros vendors grandes
                        if (id.includes('chart.js') || id.includes('chartjs')) {
                            return 'charts';
                        }
                        // Outros vendors
                        return 'vendor';
                    }
                },
            },
        },
        chunkSizeWarningLimit: 1000,
        target: 'esnext',
    },
});
