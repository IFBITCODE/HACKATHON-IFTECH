import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import { globSync } from 'node:fs';

const inputFiles = globSync('resources/{css,js}/**/*.{css,js}');

export default defineConfig({
    plugins: [
        laravel({
            input: inputFiles,
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true, // <-- Adicione isso para autorizar requisições do Laravel
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});