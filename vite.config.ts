import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            //ssr: 'resources/js/ssr.tsx',
            refresh: true,
        }),
        react({
            babel: {
                plugins: ['babel-plugin-react-compiler'],
            },
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
    ],

    resolve: {
        alias: {
            '@/components': path.resolve(__dirname, 'resources/js/components'),
            '@/hooks': path.resolve(__dirname, 'resources/js/hooks'),
            '@/app': path.resolve(__dirname, 'resources/js/pages'),
            '@/../public': path.resolve(__dirname, 'public'),
            'next/image': path.resolve(__dirname, 'resources/js/components/helpers/Image.tsx'),
            'next/link': path.resolve(__dirname, 'resources/js/components/helpers/Link.tsx'),
            'next/head': path.resolve(__dirname, 'resources/js/components/helpers/Head.tsx'),
            'next/router': path.resolve(__dirname, 'resources/js/components/helpers/router.ts'),
            'next/navigation': path.resolve(__dirname, 'resources/js/components/helpers/navigation.ts'),
            'next-themes': path.resolve(__dirname, 'resources/js/components/helpers/theme.ts'),
        },
    },

    esbuild: {
        jsx: 'automatic',
    },
});
