/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx,vue}",
  ],
  theme: {
    extend: {
      clipPath: {
        'polygon-bottom': 'polygon(50% 0%, 100% 0, 100% 100%, 0 100%, 0 50%)',
        'polygon-top': 'polygon(50% 0%, 100% 0, 100% 50%, 0 50%, 0 100%, 50% 100%)',
      },
      fontFamily: {
        'main-font': ['Poppins', 'sans-serif']
      },
      colors: {
        'custom-gray': '#B4B4B4',
        'main-blue' : '#1d8cf8',
        'custom-gray-dark' : '#38393b',
        'custom-gray-2' : '#484B55',
        'color-shadow' : '#27293d',

        'custom-blue' : '#1d8cf8',
        'custom-green' : '#42b883',
        'custom-red' : '#fd5d93',
        'custom-orange' : '#ff8d72',
        'custom-purple' : '#e14eca',
        'custom-turquoise' : '#00f2c3',
        
        'second-bg' : '#1b1e3390',
        'main-bg': "#121422",
        'main-color': "#121422",
        'second-color' : '#3c78e3'
      },
      spacing: {
        'header-width' : '65px',
        'top-Header' : '20px',
        'custom-margin-main' : '20px'
      },
      backgroundImage: {
        'main-gradient': "linear-gradient(to top left, #1b1e33 40%, #536976 250%)",
        'information-gradient': "linear-gradient(90deg, #0b274d, #0e2242, #0f1e38, #0f192e, #0f1524, #0c0f1b, #060810, #000000);",
        'header-gradient': "linear-gradient(0deg,#3358f4,#1d8cf8)",

        'gradient': "linear-gradient(to top left, #101010 70%, #ffffff 450%)",
        'custom-gradient': "linear-gradient(to top left, #101010 70%, #ffffff 450%)",

        'gradient-blue' : "linear-gradient(0deg,#3358f4,#1d8cf8)",
        'gradient-green' : "linear-gradient(0deg,#389466,#42b883)",
        'gradient-red' : "linear-gradient(0deg, #ec250d, #fd5d93)",
        'gradient-orange' : "linear-gradient(0deg,#ff6491,#ff8d72)",
        'gradient-vanusa' : "linear-gradient(0deg,#89216B,#DA4453)",
        'gradient-purple' : "linear-gradient(0deg,#ba54f5,#e14eca)",
        'gradient-turquoise' : "linear-gradient(0deg,#0098f0,#00f2c3)",
        'gradient-ohhapiness' : "linear-gradient(0deg,#96c93d,#00b09b)",

        'gradient-joomla' : "linear-gradient(0deg,#2a5298,#1e3c72)",
        'gradient-namn' : "linear-gradient(0deg,#7a2828,#a73737)",
        'gradient-gray' : "linear-gradient(180deg, #2c3e50, #111)",

        'gradient-x-blue' : "linear-gradient(240deg, #174285, #3c78e3)",  
      },
      boxShadow: {
        'custom-main': '1px 1px 5px #38393b',
        'custom-lower': '1px 1px 3px #38393b',
        'custom-hover': '3px 3px 5px white',
      }
    },
  },
  plugins: []
}

