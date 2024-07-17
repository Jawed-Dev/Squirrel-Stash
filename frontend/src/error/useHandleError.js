import { ref, computed } from 'vue';
import { isValidCategory, isValidMail, isValidInputAmount, isValidInputDate, isValidInputNote, isValidTrsType, isValidPassword, isValidLastName, isValidFirstName } from './useValidFormat';


export const TYPE_SUBMIT_ERROR = {
    MANDATORY_EMPTY_INPUTS : 0,
    INPUTS_FORMAT_ERRORS : 1,
    NOT_SUCCESS_REQUEST : 2,
    CONFIRM_PASS_ERROR : 3,
    CATEGORY_ERROR : 4,
}

export const typeError = {
    email: { 
        code: 0, 
        message: "Adresse email invalide", 
        adviceFormat: "Un email doit suivre le format suivant: 'exemple@domaine.com'",
    },   
    password: { 
        code: 1, 
        message: "Mot de passe invalide", 
        adviceFormat: "Le mot de passe doit comporter, 8 caractères, une majuscule et un chiffre.'",
    },  
    passwordConfirm: { 
        code: 2, 
        message: "Les mots de passes ne sont pas identiques", 
        adviceFormat: "L'adresse doit suivre le format 'exemple@domaine.com'",
    }, 
    lastName: { 
        code: 3, 
        message: "Nom invalide", 
        adviceFormat: "Nom invalide.",
    },  
    firstName: { 
        code: 4, 
        message: "Prénom invalide", 
        adviceFormat: "Prénom invalide.",
    },  
    amount: { 
        code: 5, 
        message: "Montant invalide", 
        adviceFormat: "Montant invalide.",
    },   
    trsDate: { 
        code: 6, 
        message: "Date invalide", 
        adviceFormat: "Date invalide."
    },
    note: { 
        code: 7, 
        message: "La note est trop longue", 
        adviceFormat: "Note invalide."
    },   
    trsCategory: {
        code: 8,
        message: "Catégorie invalide.", 
    },
    trsType: {
        code: 9,
        message: "Type de transaction invalide.", 
    },
    gender: {
        code: 10,
        message: "Genre invalide.", 
    }
};

export function isAnyInputError(inputs) {
    return Object.values(inputs).some(value => value);
}

export function isAnyMandatInputEmpty(inputs) {
    return Object.values(inputs).some(value => value === '' || value === false);
}

export function useErrorFormat (func, inputs) {
    const stateFormatErrors = ref([]);
    const computedFormatErrors = computed(() => {
        const errors = func(inputs);
        if(errors) {
            stateFormatErrors.value = errors;
            return errors[0].message;
        }
        else {
            stateFormatErrors.value = [];
        }
    });

    return {
        stateFormatErrors,
        computedFormatErrors
    }
}

export function verifyLogin(params) {
    console.log(params);
    const stateErrors = [];
    if (params.email.ref.value.length > 0) {
        if (!isValidMail(params.email.ref.value)) stateErrors.push(typeError.email);
    }
    if (params.password.ref.value.length > 0) {
        if (!isValidPassword(params.password.ref.value)) stateErrors.push(typeError.password);
    }
    return (stateErrors.length > 0) ? stateErrors : null;
}

export function verifyForgotPass(params) {
    const stateErrors = [];
    if(params.email.ref.value.length > 0)  {
        if(!isValidMail(params.email.ref.value)) stateErrors.push(typeError.email);
    }
    return (stateErrors.length > 0) ? stateErrors : null;
}


export function verifyResetPass(params) {
    const stateErrors = [];
    if(params.password.ref.valuelength > 0)  {
        if(!isValidPassword(params.password.ref.value)) stateErrors.push(typeError.password);
    }
    if(params.password.ref.value.length > 0 && params.confirmPassword.ref.value.length > 0) {
        if(params.password.ref.value !== params.confirmPassword.ref.value) stateErrors.push(typeError.passwordConfirm);
    }
    return (stateErrors.length > 0) ? stateErrors : null;
}

export function verifyCreateAccount(params) {
    const stateErrors = [];
    if(params.firstName.ref.value.length > 0)  {
        if(!isValidFirstName(params.firstName.ref.value)) stateErrors.push(typeError.firstName);
    }
    if(params.lastName.ref.value.length > 0) {
        if(!isValidLastName(params.lastName.ref.value)) stateErrors.push(typeError.lastName);
    }
    if(params.email.ref.value.length > 0)  {
        if(!isValidMail(params.email.ref.value)) stateErrors.push(typeError.email);
    }
    if(params.password.ref.value.length > 0) {
        if(!isValidPassword(params.password.ref.value)) stateErrors.push(typeError.password);
    }
    if(params.password.ref.value.length > 0 && params.confirmPassword.ref.value.length > 0) {
        if(params.password.ref.value !== params.confirmPassword.ref.value) stateErrors.push(typeError.passwordConfirm);
    } 
    return (stateErrors.length > 0) ? stateErrors : null;
}

export function verifyAddTransaction(params) {
    const stateErrors = [];
    if(params.trsAmount.ref.value.length > 0)  {
        if (!isValidInputAmount(params.trsAmount.ref.value)) stateErrors.push(typeError.trsAmount);
    }
    if(params.date.ref.value.length > 0) {
        if (!isValidInputDate(params.date.ref.value)) stateErrors.push(typeError.trsDate);
    }
    if(params.note.ref.value.length > 0) {
        if (!isValidInputNote(params.note.ref.value)) stateErrors.push(typeError.trsNote);
    }
    if(params.trsCategory.ref.value.length > 0) { 
        if (!isValidCategory(params.trsCategory.ref.value, params.trsType.ref.value)) stateErrors.push(typeError.trsCategory);
    }
    if(params.trsType.ref.value.length > 0) { 
        if(!isValidTrsType(params.trsType)) stateErrors.push(typeError.trsType);
    }
    
    return (stateErrors.length > 0) ? stateErrors : null;
}
export function verifyEditTransaction(params) {
    const stateErrors = [];
    if(params.trsAmount.ref.value.length > 0)  {
        if (!isValidInputAmount(params.trsAmount.ref.value)) stateErrors.push(typeError.trsAmount);
    }
    if(params.date.ref.value.length > 0) {
        if (!isValidInputDate(params.date.ref.value)) stateErrors.push(typeError.trsDate);
    }
    if(params.note.ref.value.length > 0) {
        if (!isValidInputNote(params.note.ref.value)) stateErrors.push(typeError.trsNote);
    }
    if(params.trsCategory.ref.value.length > 0) { 
        if (!isValidCategory(params.trsCategory.ref.value, params.trsType.ref.value)) stateErrors.push(typeError.trsCategory);
    }
    if(params.trsType.ref.value.length > 0) { 
        if(!isValidTrsType(params.trsType)) stateErrors.push(typeError.trsType);
    }
    
    return (stateErrors.length > 0) ? stateErrors : null;
}
export function verifySetThreshold(params) {
    const stateErrors = [];
    if(params.thresholdAmount.ref.value.length > 0) {
        if (!isValidInputAmount(params.thresholdAmount.ref.value)) stateErrors.push(typeError.trsAmount);
    }
    return (stateErrors.length > 0) ? stateErrors : null;
}