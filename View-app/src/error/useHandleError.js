import { ref } from 'vue';
import { isValidCategory, isValidFormatMail, isValidInputAmount, isValidInputDate, isValidInputNote, isValidTrsType } from './useValidFormat';


export const CONFIG_TYPE_ERROR = {
    MAIL_ERROR : 0,
    TRS_AMOUNT_ERROR : 1,
    TRS_DATE_ERROR : 2,
    TRS_NOTE_ERROR : 3,
}

const typeError = {
    mail: { 
        code: 0, 
        message: "Adresse email invalide", 
        adviceFormat: "L'adresse doit suivre le format 'exemple@domaine.com'",
    },   
    trsAmount: { 
        code: 1, 
        message: "Montant invalide", 
        adviceFormat: "Le montant ne doit former que des lettre",
    },   
    trsDate: { 
        code: 2, 
        message: "Date invalide", 
        adviceFormat: "La note est trop longue"
    },
    trsNote: { 
        code: 3, 
        message: "La note est trop longue", 
        adviceFormat: "La note est trop longue"
    },   
    trsCategory: {
        code: 4,
        message: "Informations invalides", 
    },
    trsType: {
        code: 5,
        message: "Informations invalides", 
    }
}

const stateErrors = [];
export function verifyAddTransaction(params) {

    stateErrors.value = [];
    if (!isValidInputAmount(params.trsAmount)) {
        if(params.trsAmount.length > 0) stateErrors.value.push(typeError.trsAmount);
    }
    if (!isValidInputDate(params.date)) {
        console.log(params.date);
        if(params.date.length > 0) stateErrors.value.push(typeError.trsDate);
    }
    if (!isValidInputNote(params.note)) {
        if(params.note.length > 0) stateErrors.value.push(typeError.trsNote);
    }
    if (!isValidCategory(params.trsCategory, params.trsType)) {
        stateErrors.value.push(typeError.trsCategory);
    }
    
    if(!isValidTrsType(params.trsType)) {
        stateErrors.value.push(typeError.trsType);
    }

    return (stateErrors.value.length > 0) ? stateErrors.value : null;
}
export function verifyEditTransaction(params) {

    stateErrors.value = [];
    if (!isValidInputAmount(params.trsAmount)) {
        if(params.trsAmount.length > 0) stateErrors.value.push(typeError.trsAmount);
    }
    if (!isValidInputDate(params.date)) {
        console.log(params.date);
        if(params.date.length > 0) stateErrors.value.push(typeError.trsDate);
    }
    if (!isValidInputNote(params.note)) {
        if(params.note.length > 0) stateErrors.value.push(typeError.trsNote);
    }
    if (!isValidCategory(params.trsCategory, params.trsType)) {
        stateErrors.value.push(typeError.trsCategory);
    }
    
    if(!isValidTrsType(params.trsType)) {
        stateErrors.value.push(typeError.trsType);
    }

    return (stateErrors.value.length > 0) ? stateErrors.value : null;
}
export function verifySetThreshold(params) {
    stateErrors.value = [];
    if (!isValidInputAmount(params.thresholdAmount)) {
        if(params.thresholdAmount.length > 0) stateErrors.value.push(typeError.trsAmount);
    }
    return (stateErrors.value.length > 0) ? stateErrors.value : null;
}