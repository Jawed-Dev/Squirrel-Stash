/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx,vue}",
  ],
  theme: {
    extend: {
      fontFamily: {
        'main-font': ['Poppins', 'sans-serif']
      },
      colors: {
        'custom-gray': '#B4B4B4',
        'main-blue' : '#15336C',
        'custom-gray-dark' : '#38393b'
      },
      spacing: {
        'headerWidth' : '60px',
        'top-Header' : '20px',
        'custom-margin-main' : '20px'
      },
      backgroundImage: {
        'gradient': "linear-gradient(to top left, #101010 70%, #ffffff 450%)",
        'custom-gradient': "linear-gradient(to top left, #101010 70%, #ffffff 450%)"
      },
      boxShadow: {
        'custom-main': '0 0 10px #38393b',
        'custom-lower': '0 0 3px #38393b',

      }
    },
  },
  plugins: [],
}

