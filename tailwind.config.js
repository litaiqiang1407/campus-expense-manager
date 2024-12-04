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
        hoverPrimary: 'rgba(0, 188, 42, 0.8)',
      },
    },
  },
  plugins: [
    function({ addComponents }) {
      addComponents({
        '.button-animate': {
          '@apply transition-transform duration-150 ease-in-out active:scale-95 active:opacity-80 focus:outline-none': {},
        },
      });
    },
    function({ addUtilities }) {
      addUtilities({
        '.scrollbar-hide': {
          '-ms-overflow-style': 'none', 
          'scrollbar-width': 'none',  
          '&::-webkit-scrollbar': {
            display: 'none',         
          },
        },
      })}
  ]
}

