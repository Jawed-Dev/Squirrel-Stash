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
    MANDATORY_EMPTY_INPUTS: 'Veuillez remplir tous les champs obligatoires.',
    CONFIRM_PASS_ERROR: 'Le nouveau mot de passe et la confirmation, \nne sont pas identiques',
    CATEGORY_ERROR: 'Catégorie invalide.',
    NEW_EMAIL_ERROR: "Le nouvel email est identique à l'ancien.",
    DATE_EMPTY: "Il doit y avoir une date définie.",
    FAIL_REQUEST: "La requête a échoué."
}

export const typeError = {
    email: { 
        code: 0, 
        message: "Adresse email invalide.", 
        adviceFormat: "Format d'email invalide.",
    },   
    password: { 
        code: 1, 
        message: "Mot de passe invalide.", 
        adviceFormat: "Format de mot de passe: \n8 caractères, 1 majuscule et 1 chiffre.",
    },  
    passwordConfirm: { 
        code: 2, 
        message: "Les mots de passes ne sont pas identiques.", 
        adviceFormat: "LFormat de mot de passe: \n8 caractères, 1 majuscule et 1 chiffre.",
    }, 
    lastName: { 
        code: 3, 
        message: "Nom invalide.", 
        adviceFormat: "Format du nom invalide.",
    },  
    firstName: { 
        code: 4, 
        message: "Prénom invalide.", 
        adviceFormat: "Format du prénom invalide.",
    },  
    amount: { 
        code: 5, 
        message: "Montant invalide.", 
        adviceFormat: "Exemple de montant valide : \n 10 | 10,1 | 10,21",
    },   
    trsDate: { 
        code: 6, 
        message: "Date invalide.", 
        adviceFormat: "Format de date invalide."
    },
    note: { 
        code: 7, 
        message: "La note est trop longue.", 
        adviceFormat: "Une note prend entre 1 et 30 caractères."
    },   
    trsCategory: {
        code: 8,
        message: "Catégorie invalide.", 
        adviceFormat: "Cette catégorie n'existe pas."
    },
    trsType: {
        code: 9,
        message: "Type de transaction invalide.", 
        adviceFormat: "Ce type de transaction n'existe pas."
    },
    gender: {
        code: 10,
        message: "Genre invalide.", 
        adviceFormat: "Le genre est soit :\n Homme / Femme / Autre."
    },
    message: {
        code: 11,
        message: "Votre message est trop long.", 
        adviceFormat: "Une note prend entre 1 et 1000 caractères."
    },
    date: {
        code: 12,
        message: "Date invalide.", 
        adviceFormat: "Format de date invalide."
    },
    onlyInt: {
        code: 12,
        message: "Montant invalide.", 
        adviceFormat: "Le montant doit être un nombre sans virgule."
    }
};

export function isAnyInputError(inputs) {
    return Object.values(inputs).some(value => value);
}

export function isAnyMandatoryInputEmpty(inputs) {
    return Object.values(inputs).some(value => value === '' || value === false);
}

export function isAnyMandatoryInputEmpty2(inputs) {
    return Object.values(inputs).some(value => value === '' || value === false);
}

