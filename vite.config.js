// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.jsx', 'resources/css/app.css'], // React + Tailwind
            refresh: true,
        }),
        react(),
    ],
    server: {
        host: 'project.localhost.com',
        hmr: {
            host: 'project.localhost.com',
        },
        proxy: {
            '/api': 'http://project.localhost.com',
        },
    },
});
