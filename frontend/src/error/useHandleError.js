export const TYPE_SUBMIT_ERROR = {
    MANDATORY_EMPTY_INPUTS : 0,
    INPUTS_FORMAT_ERRORS : 1,
    NOT_SUCCESS_REQUEST : 2,
    CONFIRM_PASS_ERROR : 3,
    CATEGORY_ERROR : 4,
    NEW_EMAIL_ERROR : 5,
    DATE_EMPTY : 5,
}

export const TEXT_SUBMIT_ERROR = {
    ALL_INPUTS_MANDATORY: 'Tous les champs sont obligatoires.',
    MANDATORY_EMPTY_INPUTS: 'Veuillez remplir tous les champs * obligatoires.',
    CONFIRM_PASS_ERROR: 'Les mots de passe ne sont pas identiques',
    CATEGORY_ERROR: 'Catégorie invalide.',
    NEW_EMAIL_ERROR: "Le nouvel email est identique à l'ancien.",
    DATE_EMPTY: "Il doit y avoir une date définie."
}

export const typeError = {
    email: { 
        code: 0, 
        message: "Adresse email invalide", 
        adviceFormat: "Email invalide",
    },   
    password: { 
        code: 1, 
        message: "Mot de passe invalide", 
        adviceFormat: "8 caractères, une majuscule et un chiffre.'",
    },  
    passwordConfirm: { 
        code: 2, 
        message: "Les mots de passes ne sont pas identiques", 
        adviceFormat: "8 caractères, une majuscule et un chiffre.'",
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
        adviceFormat: "Genre invalide.", 
    },
    message: {
        code: 11,
        adviceFormat: "Votre message est trop long.", 
    },
    date: {
        code: 12,
        adviceFormat: "Date invalide.", 
    }
};

export function isAnyInputError(inputs) {
    return Object.values(inputs).some(value => value);
}

export function isAnyMandatoryInputEmpty(inputs) {
    return Object.values(inputs).some(value => value === '' || value === false);
}

