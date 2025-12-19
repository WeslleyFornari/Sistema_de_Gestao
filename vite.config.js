import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],

    // server: {
    //     host: '192.168.0.8',
    //     port: 5173, // ou outro, se estiver usando diferente
    //     strictPort: true,
    //     hmr: {
    //         host: '192.168.0.8',
    //     },
    // },
});
