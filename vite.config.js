import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/custom.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: 'doxaapp.doxa',
        hmr: {
            host: 'doxaapp.doxa'
        },
        watch: {
            usePolling: true,
        },
        cors: true,
    },
});
