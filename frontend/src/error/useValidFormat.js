import { getAvailableYear } from "@/composable/useGetDate";
import { listCategories, listRecurings } from "@/svg/listTransactionSvgs";


export function isValidMail(email) {
    const regex = /^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/;
    return regex.test(email) && email.length <= 254;
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

export function isValidFirstName(firstName) {
    const regex = /^[A-Za-zàâçéèêëîïôûùüÿñæœ' -]{2,50}$/;
    return regex.test(firstName);
}

export function isValidLastName(lastName) {
    const regex = /^[A-Za-zàâçéèêëîïôûùüÿñæœ' -]{2,70}$/;
    return regex.test(lastName);
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