import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/css/app.css',
                'resources/assets/css/style.bundle.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
