import { getAvailableYear } from "@/composables/useGetDate";
import { listPurchases, listRecurings } from "@/svgUtils/listTransactionSvgs";


export function isValidMail(email) {
    const emailTrim = email.trim();
    const regex = /^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/;
    return regex.test(emailTrim) && emailTrim.length <= 254;
}

export function isValidPassword(password) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)[A-Za-z\d\W]{12,}$/;
    return regex.test(password);
}

export function isValidGender(gender) {
    const validGenders = ['Homme', 'Femme', 'Non défini'];
    return validGenders.includes(gender.toLowerCase());
}

export function isValidFirstName(firstName) {
    const regex = /^[A-Za-zàâçéèêëîïôûùüÿñæœ' -]{2,50}$/;
    return regex.test(firstName);
}

export function isValidLastName(lastName) {
    const regex = /^[A-Za-zàâçéèêëîïôûùüÿñæœ' -]{2,70}$/;
    return regex.test(lastName);
}

export function isValidInputAmount(amount) {
    const regex = /^\d+(,\d{1,2})?$/;
    return regex.test(amount) && parseFloat(amount.replace(',', '.')) < 1000000;
}

export function isValidInputInt(amount) {
    const regex = /^\d+$/;
    return regex.test(amount) && amount < 1000000;
}

export function formatInputAmount(amount) {
    const regex =  /^\d+(,\d{0,2})?$/;
    return regex.test(amount) && parseFloat(amount.replace(',', '.')) < 1000000;
}

export function isValidInputNote(note) {
    return typeof note === 'string' && note.trim().length <= 30;
}

export function isValidCategory(category) {
    const isPurchase = listPurchases.some(item => item.text.toLowerCase() === category.toLowerCase());
    const isRecurring = listRecurings.some(item => item.text.toLowerCase() === category.toLowerCase());
    return (isPurchase || isRecurring);
}

export function isValidTrsType(transactionType) {
    return transactionType === 'purchase' || transactionType === 'recurring';
}

export function isValidTrsDate(date) {
    const regex = /^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
    const year = Number(extractYearFromDate(date));
    if(!isValidYear(year)) return false;
    return regex.test(date);
}

export function isValidDate(date) {
    const regex = /^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/;
    const year = Number(extractYearFromDate(date));
    return regex.test(date);
}

export function isValidMessage(message) {
    return message.trim().length <= 1000;
}

export function extractYearFromDate(date) {
    const parts = date.split('-');
    return parts[0]; 
}

export function isValidYear(year) {
    return getAvailableYear().some(item => item === year);
}

export function isValidMonth(month) {
    return typeof month === 'number' && month >= 0 && month <= 12;
}