export function getAvailableYear() {
    const yearArray = [];
    for (let year = 2020; year <= getCurrentYear(); year++) {
        yearArray.push(year);
    }
    return yearArray;
}

export const monthNames = 
["Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
"Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

export function getMonthNumber(monthName) {
    const index = monthNames.indexOf(monthName);
    if (index === -1) return null;
    return index + 1;
}

export function getCurrentDate() {
    const now = new Date();
    const year = now.getFullYear(); 
    const month = (now.getMonth() + 1).toString().padStart(2, '0'); 
    const day = now.getDate().toString().padStart(2, '0'); 
    return `${year}-${month}-${day}`;
}

export function getCurrentMonthName() {
    const currentDate = new Date();
    return monthNames[currentDate.getMonth()];
}

export function formatDateForFirstDay(month, year) {
    return `${year}-${month.toString().padStart(2, '0')}-01`;
}

export function isCurrentMonth(month, year) {
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth()+1; 
    const currentYear = currentDate.getFullYear();
    console.log(currentMonth,currentYear);
    return (month === currentMonth && year === currentYear);
}

export function formatDateForCurrentDay(month, year) {
    const today = new Date();
    const day = today.getDate();
    return `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
}

export function getCurrentYear() {
    const currentDate = new Date();
    return currentDate.getFullYear();
}