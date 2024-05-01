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
        'custom-gray-dark' : '#38393b',
        'custom-gray-2' : '#484B55',
        'color-shadow' : '#27293d',
        
        'second-bg' : '#1b1e33',
        'main-bg': "#121422",

        'main-color': "#121422"
      },
      spacing: {
        'header-width' : '70px',
        'top-Header' : '20px',
        'custom-margin-main' : '20px'
      },
      backgroundImage: {
        'main-gradient': "linear-gradient(to top left, #1b1e33 50%, #536976 250%)",
        'header-gradient': "linear-gradient(0deg, #ec250d, #fd5d93)",

        'gradient': "linear-gradient(to top left, #101010 70%, #ffffff 450%)",
        'gradient-test': "linear-gradient(to top left, #1b1e33 70%, #ffffff 450%)",
        'custom-gradient': "linear-gradient(to top left, #101010 70%, #ffffff 450%)",

        
      },
      boxShadow: {
        'custom-main': '0 0 10px #38393b',
        'custom-lower': '0 0 3px #38393b',
        'custom-test': '0 1px 10px #38393b',    
      }
    },
  },
  plugins: [],
}

