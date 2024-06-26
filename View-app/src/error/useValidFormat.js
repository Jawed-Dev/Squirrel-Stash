import { getAvailableYear } from "@/composable/useGetDate";
import { listCategories, listRecurings } from "@/svg/listTransactionSvgs";


export function isValidFormatMail(email) {
    const regex = /^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/;
    return regex.test(email) && email.length <= 254;
}

export function isValidInputAmount(amount) {
    const regex = /^\d+(,\d+)?$/;
    return regex.test(amount) && amount.length <= 10;
}

export function isValidInputNote(note) {
    return note.length <= 30;
}   

export function isValidCategory(category, transactionType) {
    if(transactionType ==='purchase') {
        return listCategories.some(item => item.nameIcon.toLowerCase() === category.toLowerCase());
    }
    else {
        return listRecurings.some(item => item.nameIcon.toLowerCase() === category.toLowerCase());
    }
}

export function isValidTrsType(transactionType) {
    return transactionType ==='purchase' || transactionType ==='recurring';
}

export function isValidInputDate(date) {
    const regex = /^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
    return regex.test(date);
}

export function isValidYear(year) {
    return getAvailableYear().some(item => item === year);
}
export function isValidMonth(month) {
    return typeof month === 'number' && month >= 0 && month <= 12;
}