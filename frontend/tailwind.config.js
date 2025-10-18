/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    extend: {
      fontFamily: {
        inter: ['Inter', 'sans-serif'],
        poppins: ['Poppins', 'sans-serif'],
      },
      colors: {
        background: '#0F172A',
        card: '#1E293B',
      },
    },
  },
  plugins: [require('daisyui')],

  daisyui: {
    themes: [
      {
        dark: {
          primary: '#10B981', // verde-esmeralda
          secondary: '#06B6D4', // azul-celeste
          accent: '#8B5CF6', // roxo gamificado
          neutral: '#1E293B', // container/card
          'base-100': '#0F172A', // fundo principal
          info: '#06B6D4',
          success: '#10B981',
          warning: '#F97316',
          error: '#F43F5E',
        },
      },
    ],
  },
}
