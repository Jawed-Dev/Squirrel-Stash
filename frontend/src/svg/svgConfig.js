// fichier svgConfig.js
export const svgConfig = {
    default: { width: '30px', height: '30px', fill: 'white'},
    veryLarge: { width: '100px', height: '100px', fill: 'white'},
    largeIcon: { width: '3vw', height: '3vw', fill: 'white'},
    mediumHigher: { width: '2.5vw', height: '2.5vw', fill: 'white'},
    medium: { width: '50px', height: '50px', fill: 'white'},
    mediumSmaller: { width: '40px', height: '40px', fill: 'white'},
    smallIcon: { width: '30px', height: '30px', fill: 'white'},
    verySmallIcon: { width: '20px', height: '20px', fill: 'white'},
    setColorDynamic: (config, color) => ({ ...config, color: color }),
};


export function setSvgConfig(params) {
    // params.width = '20px';
    // params.fill = 'white';
    // params.color = '';
    return {
        width: params.width,
        height: params.width,
        fill: params.fill,
        color: params.color
    }
}