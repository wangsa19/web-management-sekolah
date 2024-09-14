import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

export default defineConfig({
  plugins: [laravel()],
  css: {
    postcss: {
      plugins: [
        tailwindcss('./tailwind.config.js'),
        autoprefixer,
      ],
    },
  },
});
