const defaultTheme = require('tailwindcss/defaultTheme');
module.exports = {
  content: ['./public/**/*.{html,js}', './public/components/**/*.{html,js}'],
  purge:
    process.env.NODE_ENV === 'production'
      ? ['./public/**/*.html', './public/**/*.js']
      : false,
  theme: {
    colors: {
      ...defaultTheme.colors,
      black100: '#0D0D0D',
      'gray-5': ' rgba(196, 196, 196, 0.6)',
      black200: '#111111',
      black300: '#222222',
      gray200: '#FBFBFB',
      gray300: '#828282',
      gray400: '#BDBDBD',
      gray500: '#E0E0E0',
      gray600: '#F2F2F2',
      gray700: '#E5E5E5',
      gray800: '#666666',
      orange800: '#CB5715',
      light100: '#ffffff',
      light200: '#F7F7F7',
      white: '#FFFFFF',
      orange: '#CB5715',
      orange200: '#EF6D1D',
      green: '#219653',
    },
    fontFamily: {
      heading: ['Poppins', 'sans-serif'],
      body: ['Poppins', 'sans-serif'],
    },
    fontSize: {
      ...defaultTheme.fontSize,
      '2.5xl': '28px',
    },
    zIndex: {
      '-10': '-10',
      ...defaultTheme.zIndex,
    },
    spacing: {
      ...defaultTheme.spacing,
    },
  },
};
