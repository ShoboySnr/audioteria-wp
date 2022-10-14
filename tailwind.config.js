/* eslint-disable no-undef */
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  mode: 'jit',
  content: [
      './inc/**/*.php',
      './js/**/*.js',
      './sass/**/*.scss'
  ],
  darkMode: 'class', // or 'media' or 'class'
  theme: {
    colors: {
      ...defaultTheme.colors,
      black100: '#0D0D0D',
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
    },
    fontFamily: {
      heading: ['Poppins', 'sans-serif'],
      body: ['Poppins', 'sans-serif'],
    },
    fontSize: {
      ...defaultTheme.fontSize,
    },
    zIndex: {
      '-10': '-10',
    },
    spacing: {
      ...defaultTheme.spacing,
    },
  },
  variants: {},
  plugins: [],
}
