import { ref } from 'vue';
import { isValidCategory, isValidMail, isValidInputAmount, isValidInputDate, isValidInputNote, isValidTrsType, isValidPassword, isValidLastName, isValidFirstName } from './useValidFormat';


export const CONFIG_TYPE_ERROR = {
    MAIL_ERROR : 0,
    TRS_AMOUNT_ERROR : 1,
    TRS_DATE_ERROR : 2,
    TRS_NOTE_ERROR : 3,
}

const typeError = {
    email: { 
        code: 0, 
        message: "Adresse email invalide", 
        adviceFormat: "L'adresse doit suivre le format 'exemple@domaine.com'",
    },   
    password: { 
        code: 1, 
        message: "Mot de passe invalide", 
        adviceFormat: "L'adresse doit suivre le format 'exemple@domaine.com'",
    },  
    passwordConfirm: { 
        code: 2, 
        message: "Les mots de passes ne sont pas identiques", 
        adviceFormat: "L'adresse doit suivre le format 'exemple@domaine.com'",
    }, 
    lastName: { 
        code: 3, 
        message: "Nom invalide", 
        adviceFormat: "L'adresse doit suivre le format 'exemple@domaine.com'",
    },  
    firstName: { 
        code: 4, 
        message: "Prénom invalide", 
        adviceFormat: "L'adresse doit suivre le format 'exemple@domaine.com'",
    },  
    trsAmount: { 
        code: 5, 
        message: "Montant invalide", 
        adviceFormat: "Le montant ne doit former que des lettre",
    },   
    trsDate: { 
        code: 6, 
        message: "Date invalide", 
        adviceFormat: "La note est trop longue"
    },
    trsNote: { 
        code: 7, 
        message: "La note est trop longue", 
        adviceFormat: "La note est trop longue"
    },   
    trsCategory: {
        code: 8,
        message: "Informations invalides", 
    },
    trsType: {
        code: 9,
        message: "Informations invalides", 
    }
}


export function verifyForgotPass(params) {
    const stateErrors = [];
    if(params.email.length > 0)  {
        if(!isValidMail(params.email)) stateErrors.push(typeError.email);
    }
    return (stateErrors.length > 0) ? stateErrors : null;
}

export function verifyCreateAccount(params) {
    const stateErrors = [];
    if(params.firstName.length > 0)  {
        if(!isValidFirstName(params.firstName)) stateErrors.push(typeError.firstName);
    }
    if(params.lastName.length > 0) {
        if(!isValidLastName(params.lastName)) stateErrors.push(typeError.lastName);
    }
    if(params.email.length > 0)  {
        if(!isValidMail(params.email)) stateErrors.push(typeError.email);
    }
    if(params.password.length > 0) {
        if(!isValidPassword(params.password)) stateErrors.push(typeError.password);
    }
    if(params.password.length > 0 && params.confirmPassword.length > 0) {
        if(params.password !== params.confirmPassword) stateErrors.push(typeError.passwordConfirm);
    } 
    if(params.password !== params.confirmPassword) stateErrors.push(typeError.passwordConfirm);

    return (stateErrors.length > 0) ? stateErrors : null;
}

export function verifyLogin(params) {
    const stateErrors = [];
    if(params.email.length > 0)  {
        if(!isValidMail(params.email)) stateErrors.push(typeError.email);
    }
    if(params.password.length > 0) {
        if(!isValidPassword(params.password)) stateErrors.push(typeError.password);
    }
    return (stateErrors.length > 0) ? stateErrors : null;
}


export function verifyAddTransaction(params) {
    const stateErrors = [];
    if (!isValidInputAmount(params.trsAmount)) {
        if(params.trsAmount.length > 0) stateErrors.push(typeError.trsAmount);
    }
    if (!isValidInputDate(params.date)) {
        if(params.date.length > 0) stateErrors.push(typeError.trsDate);
    }
    if (!isValidInputNote(params.note)) {
        if(params.note.length > 0) stateErrors.push(typeError.trsNote);
    }
    if (!isValidCategory(params.trsCategory, params.trsType)) {
        stateErrors.push(typeError.trsCategory);
    }
    
    if(!isValidTrsType(params.trsType)) {
        stateErrors.push(typeError.trsType);
    }

    return (stateErrors.length > 0) ? stateErrors : null;
}
export function verifyEditTransaction(params) {

    const stateErrors = [];
    if (!isValidInputAmount(params.trsAmount)) {
        if(params.trsAmount.length > 0) stateErrors.push(typeError.trsAmount);
    }
    if (!isValidInputDate(params.date)) {
        console.log(params.date);
        if(params.date.length > 0) stateErrors.push(typeError.trsDate);
    }
    if (!isValidInputNote(params.note)) {
        if(params.note.length > 0) stateErrors.push(typeError.trsNote);
    }
    if (!isValidCategory(params.trsCategory, params.trsType)) {
        stateErrors.push(typeError.trsCategory);
    }
    
    if(!isValidTrsType(params.trsType)) {
        stateErrors.push(typeError.trsType);
    }

    return (stateErrors.length > 0) ? stateErrors : null;
}
export function verifySetThreshold(params) {
    const stateErrors = [];
    if (!isValidInputAmount(params.thresholdAmount)) {
        if(params.thresholdAmount.length > 0) stateErrors.push(typeError.trsAmount);
    }
    return (stateErrors.length > 0) ? stateErrors : null;
}