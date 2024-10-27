import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { run } from 'vite-plugin-run';
import viteTsconfigPaths from 'vite-tsconfig-paths';
import * as path from 'path';

export default defineConfig({
    server: {
        host: '127.0.0.1',
        port: 3000,
    },

    plugins: [
        laravel([
            'resources/scss/app.scss',
            'resources/scss/app-dark.scss',
            'resources/scss/app-admin.scss',
            'resources/scss/app-admin-dark.scss',
            'resources/scss/mail.scss',
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
            'resources/ts/special/member.ts',
            'resources/ts/special/landing-typewriter.ts',
            'resources/scss/special/aerodrome-mapbox.scss',
        ]),
        run([
            {
                name: 'build routes',
                run: ['php', 'artisan', 'routes:generate'],
                condition: (file) => file.includes('/routes/'),
            },
        ]),
        viteTsconfigPaths(),
    ],

    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return id.toString().split('node_modules/')[1].split('/')[0].toString();
                    }
                },
                assetFileNames: function (file) {
                    return file.name.includes('mail') ? `assets/[name].[ext]` : `assets/[name]-[hash].[ext]`;
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
