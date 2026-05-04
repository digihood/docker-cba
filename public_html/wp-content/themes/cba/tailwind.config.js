/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./assets/**/*.{js,css,php}",
    "./functions/**/*.{js,css,php}",
    "./page-templates/**/*.{js,css,php}",
    "./parts/**/*.{js,css,php}",
    "./*.{js,php}",
  ],
  theme: {
    container: { // Nastavení .containeru
      center: true,
      padding: { // padding containeru
        DEFAULT: '1rem',
        // sm: '2rem',
        // lg: '4rem',
        // xl: '5rem',
        // '2xl': '6rem',
      },
    },
    screens: { // Šířka containeru
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1280px',
        '2xl': '1640px',
    },
    colors: { // Nastavení barev šablony
      primary: '#1fb6ff',
      secondary: '#7e5bef',
      orange: '#ff7849',
      green: '#13ce66',
      yellow: '#ffc82c',
      black: '#000',
      white: '#fff',
      gray: '#d3dce6',
    },
    fontSize: { // Nastavení velikostí textů šablony - ['font-size','line-height']
      sm: ['14px', '20px'],
      base: ['16px', '24px'],
      lg: ['20px', '28px'],
      xl: ['24px', '32px'],
      h1: {
        sm: ['40px', '1.2'],
        md: '50px',
        lg: '60px',
      },
      h2: {
        sm: '30px',
        md: '40px',
        lg: '50px',
      },
      h3: {
        sm: '25px',
        md: '35px',
        lg: '45px',
      },
      h4: {
        sm: '20px',
        md: '25px',
        lg: '30px',
      },
      h5: {
        sm: '18px',
        md: '20px',
        lg: '22px',
      }
    },
    fontFamily: { // Nastavení fontů šablony
      sans: ['Inter Variable', 'sans-serif'],
    },
    extend: {
      gap: { // Defaultní mezera mezi řádky/sloupci v Gridu
        'sm': '10px',
        'md': '20px',
        'lg': '50px',
      },
    },
  },
  plugins: [
    require('tailwindcss-intersect'), // https://github.com/heidkaemper/tailwindcss-intersect
    require('tailwindcss-animated'), // https://github.com/new-data-services/tailwindcss-animated
  ],
}

