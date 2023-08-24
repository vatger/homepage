import { defineConfig, splitVendorChunkPlugin } from 'vite';
import laravel from 'laravel-vite-plugin';
import { run } from 'vite-plugin-run';
import * as path from 'path';
// import react from '@vitejs/plugin-react';
// import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        hmr: {
            host: 'vatger.test',
        },
    },

    plugins: [
        laravel([
            'resources/scss/app.scss',
            'resources/scss/app-dark.scss',
            'resources/scss/app-admin.scss',
            'resources/scss/app-admin-dark.scss',
            'resources/css/cyan.css',
            'resources/css/default.css',
            'resources/css/green.css',
            'resources/css/purple.css',
            'resources/css/red.css',
            'resources/css/skobleoff.css',
            'resources/css/skyblue.css',
            'resources/css/yellow.css',
            'resources/ts/app.ts',
            'resources/ts/special/events.ts',
            'resources/ts/special/aerodrome.ts',
            'resources/scss/special/aerodrome-mapbox.scss',
        ]),
        run([
            {
                name: 'build routes',
                run: ['php', 'artisan', 'routes:generate'],
                condition: (file) => file.includes('/routes/'),
            },
        ]),
    ],

    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return id.toString().split('node_modules/')[1].split('/')[0].toString();
                    }
                },
            },
        },
    },

    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~vendor': path.resolve(__dirname, 'vendor'),
        },
    },
});
