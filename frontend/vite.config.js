import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import path from 'path'
import { fileURLToPath } from 'url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))

export default defineConfig({
  plugins: [vue(), tailwindcss()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },

  // Desabilita ESLint no Vite
  esbuild: {
    legalComments: 'none',
    // Ignorar warnings
    logOverride: {
      'this-is-undefined-in-esm': 'silent',
    },
  },
})
