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

        'custom-blue' : '#1d8cf8',
        'custom-green' : '#42b883',
        'custom-red' : '#fd5d93',
        'custom-orange' : '#ff8d72',
        'custom-purple' : '#e14eca',
        'custom-turquoise' : '#00f2c3',
        
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
        'main-gradient': "linear-gradient(to top left, #1b1e33 40%, #536976 300%)",
        'header-gradient2': "linear-gradient(0deg,#3358f4,#1d8cf8)",
        'header-gradient': "linear-gradient(0deg,#3358f4,#1d8cf8)",

        'gradient': "linear-gradient(to top left, #101010 70%, #ffffff 450%)",
        'gradient-test': "linear-gradient(to top left, #1b1e33 70%, #ffffff 450%)",
        'custom-gradient': "linear-gradient(to top left, #101010 70%, #ffffff 450%)",

        'gradient-blue' : "linear-gradient(0deg,#3358f4,#1d8cf8)",
        'gradient-green' : "linear-gradient(0deg,#389466,#42b883)",
        'gradient-red' : "linear-gradient(0deg, #ec250d, #fd5d93)",
        'gradient-orange' : "linear-gradient(0deg,#ff6491,#ff8d72)",
        'gradient-purple' : "linear-gradient(0deg,#ba54f5,#e14eca)",
        'gradient-turquoise' : "linear-gradient(0deg,#0098f0,#00f2c3)",
        
        'gradient-grey' : "linear-gradient(0deg,#bdc3c7,#2c3e50)",
        'gradient-velvet-sun' : "linear-gradient(0deg,#e1eec3,#f05053)",
        'gradient-orca' : "linear-gradient(0deg,#44A08D,#093637)",
        'gradient-pinot' : "linear-gradient(0deg,#4b6cb7,#182848)"
        
      },
      boxShadow: {
        'custom-main': '0 0 5px #38393b',
        'custom-lower': '0 0 3px #38393b',
        'custom-test': '2px 1px 6px rgba(0,0,0,.4)',
      }
    },
  },
  plugins: [],
}

