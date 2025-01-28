
import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import vueDevTools from 'vite-plugin-vue-devtools'


    export default defineConfig(
        {
            plugins: [
                laravel({
                    input: [
                        'resources/scss/style.scss',
                        'resources/css/app.css',
                        'resources/js/app.js',
                    ],
                    refresh: true,
                }),
            ],
        });