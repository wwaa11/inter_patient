import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/preauth-forms.js', 'resources/js/admission-forms.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
