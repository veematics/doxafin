import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const host = env.APP_ENV === 'production' 
        ? env.VITE_HOST_PRODUCTION 
        : env.VITE_HOST_LOCAL;

    return {
        plugins: [
            laravel({
                input: [
                    'resources/sass/app.scss',
                    'resources/css/custom.css',
                    'resources/js/app.js',
                    'resources/js/nonapp.js'
                ],
                refresh: true,
            }),
        ],
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
        },
    };
});
