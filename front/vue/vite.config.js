import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx'; // Assurez-vous que cette ligne est présente
import { fileURLToPath, URL } from 'url';

export default defineConfig({
  plugins: [
    vue(),
    vueJsx(),  // Utilisez le plugin JSX ici
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
})
