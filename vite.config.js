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
        laravel(['resources/scss/app.scss', 'resources/scss/app-dark.scss', 'resources/ts/app.ts', 'resources/ts/landing/events.ts']),
        splitVendorChunkPlugin(),
        run([
            {
                name: 'build routes',
                run: ['php', 'artisan', 'routes:generate'],
                condition: (file) => file.includes('/routes/'),
            },
        ]),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~vendor': path.resolve(__dirname, 'vendor'),
        },
    },
});
