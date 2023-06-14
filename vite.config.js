import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import * as path from 'path';
// import react from '@vitejs/plugin-react';
// import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel([
            'resources/scss/app.scss',
            'resources/scss/app-dark.scss',
            'resources/js/app.js',
            'resources/js/tiny-slider.js',
            'resources/js/custom/general/landing.js',
        ]),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
        },
    },
});
