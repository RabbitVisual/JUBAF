import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    build: {
        outDir: '../../public/build-chat',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    'chat-utils': [
                        path.resolve(__dirname, 'resources/assets/js/utils.js'),
                    ],
                    'chat-core': [
                        path.resolve(__dirname, 'resources/assets/js/chat-system.js'),
                        path.resolve(__dirname, 'resources/assets/js/toast.js'),
                    ],
                    'chat-widget': [
                        path.resolve(__dirname, 'resources/assets/js/widget.js'),
                    ],
                },
            },
        },
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-chat',
            input: [
                // Estilos SCSS
                path.resolve(__dirname, 'resources/assets/sass/app.scss'),
                // JavaScript principal
                path.resolve(__dirname, 'resources/assets/js/app.js'),
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Permitir importações relativas
                includePaths: [
                    path.resolve(__dirname, 'resources/assets/sass'),
                ],
            },
        },
    },
    resolve: {
        alias: {
            '@chat': path.resolve(__dirname, 'resources/assets'),
            '@chat-js': path.resolve(__dirname, 'resources/assets/js'),
            '@chat-sass': path.resolve(__dirname, 'resources/assets/sass'),
        },
    },
});
