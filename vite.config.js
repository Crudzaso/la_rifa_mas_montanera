import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    optimizeDeps: {
        include: ['canvas-confetti']
    },
    build: {
        outDir: 'public/build',
        assetsDir: 'assets',
    },
    publicDir: 'public',
    resolve: {
        alias: {
            '@': '/resources/js',
            'assets': '/resources/assets'
        },
    },
});


