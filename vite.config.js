import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // UI
                'node_modules/kroloburet_ui/UI.css',
                'node_modules/kroloburet_ui/UI.js',

                // Material
                'resources/js/front/material-list.js',

                // Admin
                'resources/js/admin/base.js',
                'resources/js/admin/menu.js',
                'resources/js/admin/material.js',

                // Other Resources
                'resources/js/base.js',
                'resources/css/base.css',
                'resources/css/front/material.css',
                'resources/css/two-column.css',
            ],
            refresh: true,
        }),
    ],
});
