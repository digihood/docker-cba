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
    container: {
      center: true,
      padding: {
        DEFAULT: '1.25rem',
        lg: '2rem',
      },
    },
    screens: {
      'sm':  '640px',
      'md':  '768px',
      'lg':  '1024px',
      'xl':  '1280px',
      '2xl': '1640px',
    },
    colors: {
      // CBA brand barvy (z Figma)
      primary:        '#FF6B6B',   // Losos – tlačítka, akcenty
      'primary-dark': '#E05555',
      'primary-light':'#FF9090',
      secondary:      '#A9936D',   // Bronz – zápatí, sekundární
      'secondary-dark':'#8A7459',
      // Accent: tmavý teal (CBA Modra)
      accent:         '#13576B',
      // Tmavé pozadí – CBA Modra teal
      dark:           '#13576B',   // CBA Modra
      'dark-muted':   '#0F4658',
      'dark-card':    '#0D3A49',
      // Šedé (teplé tóny)
      'gray-light':   '#F5F0EB',   // Teplá krémová
      'gray-mid':     '#E0D5C8',
      gray:           '#8A7A6A',
      'gray-dark':    '#5A4A3A',
      // Vanilka — světlá vanilka pro sekce pozadí
      vanilka:        '#fff3db',
      // Základní
      black:          '#000000',
      white:          '#FFFFFF',
      transparent:    'transparent',
    },
    fontSize: {
      xs:   ['12px', '18px'],
      sm:   ['14px', '20px'],
      base: ['16px', '26px'],
      lg:   ['18px', '28px'],
      xl:   ['20px', '30px'],
      '2xl':['24px', '34px'],
      '3xl':['30px', '40px'],
      h1: {
        sm: ['38px', '1.2'],
        md: '52px',
        lg: '68px',
      },
      h2: {
        sm: '28px',
        md: '38px',
        lg: '48px',
      },
      h3: {
        sm: '22px',
        md: '28px',
        lg: '34px',
      },
      h4: {
        sm: '18px',
        md: '22px',
        lg: '26px',
      },
      h5: {
        sm: '16px',
        md: '18px',
        lg: '20px',
      },
    },
    fontFamily: {
      sans:    ['"Inter Variable"', 'Inter', 'sans-serif'],
      display: ['"Montserrat"', '"Inter Variable"', 'Inter', 'sans-serif'],
    },
    extend: {
      gap: {
        'sm': '12px',
        'md': '24px',
        'lg': '48px',
      },
      borderRadius: {
        'xl':  '16px',
        '2xl': '24px',
        '3xl': '32px',
      },
      boxShadow: {
        'card':  '0 4px 24px rgba(19, 87, 107, 0.10)',
        'card-hover': '0 8px 40px rgba(19, 87, 107, 0.18)',
        'dark':  '0 4px 24px rgba(0, 0, 0, 0.30)',
      },
      spacing: {
        '18': '4.5rem',
        '22': '5.5rem',
        '30': '7.5rem',
      },
      maxWidth: {
        'content': '1220px',
      },
      transitionDuration: {
        '400': '400ms',
      },
    },
  },
  plugins: [
    require('tailwindcss-intersect'),
    require('tailwindcss-animated'),
  ],
}
