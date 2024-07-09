/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx,vue}",
  ],
  theme: {
    extend: {
      screens: {
        'sm': {'min': '640px', 'max': '767px'},
        // => @media (min-width: 640px and max-width: 767px) { ... }
  
        'md': {'min': '768px', 'max': '1023px'},
        // => @media (min-width: 768px and max-width: 1023px) { ... }
  
        'lg': {'min': '1024px', 'max': '1279px'},
        // => @media (min-width: 1024px and max-width: 1279px) { ... }
  
        'xl': {'min': '1280px', 'max': '1535px'},
        // => @media (min-width: 1280px and max-width: 1535px) { ... }
  
        '2xl': {'min': '1536px'},
        // => @media (min-width: 1536px) { ... }
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
        
        'second-bg' : '#1b1e33',
        'main-bg': "#121422",

        'main-color': "#121422"
      },
      spacing: {
        'header-width' : '3.5vw',
        'top-Header' : '20px',
        'custom-margin-main' : '20px'
      },
      backgroundImage: {
        'main-gradient': "linear-gradient(to top left, #1b1e33 40%, #536976 250%)",
        //'main-gradient' : "linear-gradient(to top left, #83858f 40%, #24428a 100%)",
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


        'gradient-x-blue' : "linear-gradient(60deg, #2197ff, #1b8bf5, #167feb, #1173e1, #0c68d6, #085dcc, #0453c2, #0049b8)",

        
        // 'gradient-grey' : "linear-gradient(0deg,#2c3e50,#bdc3c7)",
        
        // 'gradient-orca' : "linear-gradient(0deg,#093637,#44A08D)",
        // 'gradient-pinot' : "linear-gradient(0deg,#182848,#4b6cb7)",
        // 'gradient-copper' : "linear-gradient(0deg,#94716B,#B79891)",
        // 'gradient-behongo' : "linear-gradient(0deg,#061700,#52c234)",
        // 'gradient-royal' : "linear-gradient(0deg,#243B55,#141E30)",
        
        // 'gradient-clearsky' : "linear-gradient(0deg,#363795,#005C97)"
        
      },
      boxShadow: {
        'custom-main': '1px 1px 5px #38393b',
        'custom-lower': '1px 1px 3px #38393b',
      }
    },
  },
  plugins: [],
}


// 'custom-main': '0 0 5px #38393b',
