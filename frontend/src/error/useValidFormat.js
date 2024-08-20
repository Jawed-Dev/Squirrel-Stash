import { getAvailableYear } from "@/composable/useGetDate";
import { listPurchases, listRecurings } from "@/svg/listTransactionSvgs";


export function isValidMail(email) {
    const emailTrim = email.trim();
    const regex = /^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/;
    return regex.test(emailTrim) && emailTrim.length <= 254;
}

export function isValidPassword(password) {
    //const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    //const regex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
    //  ^ : Démarre l'ancrage au début de la chaîne.
    // (?=.*[a-z]) : Assure au moins une lettre minuscule.
    // (?=.*[A-Z]) : Assure au moins une lettre majuscule.
    // (?=.*\d) : Assure au moins un chiffre.
    // (?=.*[@$!%*?&]) : Assure au moins un caractère spécial parmi ceux spécifiés dans le groupe (@$!%*?&).
    // [A-Za-z\d@$!%*?&] : Les caractères autorisés dans le mot de passe.
    // {8,} : Assure que la longueur du mot de passe est d'au moins 8 caractères.
    // $ : Fin de l'ancrage de la chaîne.
    //return regex.test(password);
    return true;
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
    const regex =  /^\d+(,\d{0,2})?$/;
    return regex.test(amount) && parseFloat(amount.replace(',', '.')) <= 1000000000;
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