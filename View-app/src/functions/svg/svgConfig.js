// fichier svgConfig.js
export const svgConfig = {
    default: { width: '30px', height: '30px', fill: 'white'},
    veryLarge: { width: '100px', height: '100px', fill: 'white'},
    largeIcon: { width: '70px', height: '70px', fill: 'white'},
    medium: { width: '50px', height: '50px', fill: 'white'},
    smallIcon: { width: '30px', height: '30px', fill: 'white'},
    verySmallIcon: { width: '17px', height: '17px', fill: 'white'},
    setColorDynamic: (config, color) => ({ ...config, color: color }),
};