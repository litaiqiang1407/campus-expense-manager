/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#00BC2A', 
        secondaryText: '#A7A7A7',
        blueText: '#0080FF',
        redText: '#FF2121',
        primaryBackground: '#F8F9FA',
      },
    },
  },
  plugins: [],
}

