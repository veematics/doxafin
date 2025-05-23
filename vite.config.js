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
                    'resources/js/inboxselect2.js'
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
                usePolling: false,  // Changed to false for better performance
            },
            cors: true,
            origin: `http://${host}:5173`,
            fs: {
                strict: false
            }
        },

        optimizeDeps: {
            include: ['jquery'],
            force: true
        },

        build: {
            commonjsOptions: {
                include: [/node_modules/]
            },
            cache: true,
            sourcemap: false
        }
    };
});
