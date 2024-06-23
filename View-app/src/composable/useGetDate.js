export function getAvailableYearNames() {
    const yearNames = [];
    for (let year = 2020; year <= getCurrentYear(); year++) {
        yearNames.push(year);
    }
    return yearNames;
}

export const monthNames = 
["Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
"Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

export function getMonthNumber(monthName) {
    const index = monthNames.indexOf(monthName);
    if (index === -1) return null;
    return index + 1;
}

export function getCurrentMonthName() {
    const currentDate = new Date();
    return monthNames[currentDate.getMonth()];
}



export function getCurrentYear() {
    const currentDate = new Date();
    return currentDate.getFullYear();
}