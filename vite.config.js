import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const host = env.APP_ENV === 'production' 
        ? (env.VITE_HOST_PRODUCTION || 'localhost')
        : (env.VITE_HOST_LOCAL || 'localhost');

    return {
        plugins: [
            laravel({
                input: [
                    'resources/sass/app.scss',
                    'resources/css/custom.css',
                    'resources/js/app.js',
                    'resources/js/nonapp.js',
                    'resources/js/inboxselect2.js',
                    'resources/js/clientselect2.js',
                    'resources/css/select2.css'
                ],
                refresh: true,
            }),
        ],
        optimizeDeps: {
            include: ['jquery', 'select2']
        },
        server: {
            host: host,
            hmr: {
                host: host,
                protocol: 'http'
            },
            watch: {
                usePolling: true,
            },
            cors: true,
            origin: `http://${host}:5173`,
        },
    };
});
