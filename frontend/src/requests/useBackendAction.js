import useConfigFetchAction  from "@/requests/useConfigFetchAction";
import { getLStorageAuthToken } from "@/composables/useLocalStorage";
import useConfigFetchGetData from "./useConfigFetchGetData";

async function getStateSession() {
    const localToken = getLStorageAuthToken();
    const stateSession = await useConfigFetchGetData ({
        request: 'getStateSession',
        method: 'POST',
        token: localToken,
        dataBody: 'none',
    });
    const isSessionActive = stateSession?.isSessionActive;
    return (isSessionActive) ? true : false;
}

async function authRequired() {
    const isSessionActive = await getStateSession();
    if(!isSessionActive) redirectLogin();
}

async function notAuthRequired() {
    const isSessionActive = await getStateSession();
    if(isSessionActive) redirectDashboard();
}

function redirectLogin() {
    window.location.href = '/connexion';
}

function redirectDashboard() {
    window.location.href = '/tableau-de-bord';
}


export async function updatePasswordByToken(params) {
    notAuthRequired();
    const localToken = getLStorageAuthToken();
    const body = {
        resetPassToken: String(params.resetPassToken),
        password: params.password,
    };
    const dataRequest = await useConfigFetchAction ({
        request: 'updatePasswordByToken',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}

export async function updatePasswordByUserId(params) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const body = {
        oldPass: String(params.oldPass),
        newPass: String(params.newPass),
    };
    const dataRequest = await useConfigFetchAction ({
        request: 'updatePasswordByUserId',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}

export async function sendResetPass(email) {
    notAuthRequired();
    const localToken = getLStorageAuthToken();
    const body = {
        email: String(email),
    };
    const dataRequest = await useConfigFetchAction ({
        request: 'sendResetPass',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}

export async function createAccount(params) {
    notAuthRequired();
    const localToken = getLStorageAuthToken();
    const body = {
        firstName: String(params.firstName),
        lastName: String(params.lastName),
        email: String(params.email),
        password: String(params.password),
    };
    const dataRequest = await useConfigFetchAction ({
        request: 'createAccount',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}

export async function saveThreshold(month, year, amount) {
    await authRequired();
    const localToken = getLStorageAuthToken();
    const body = {
        selectedMonth: Number(month),
        selectedYear: Number(year),
        thresholdAmount: Number(amount)
    };
    const dataRequest = await useConfigFetchAction ({
        request: 'saveThreshold',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}


export async function addTransaction(params) {
    authRequired();
    if(!params.amount) return null;
    if(!params.trsCategory) return null;
    if(!params.trsType) return null;
    if(!params.date) return null;
    if(!params.note) params.note = '';

    const localToken = getLStorageAuthToken();
    const body = {
        transactionAmount: Number(params.amount),
        transactionCategory: String(params.trsCategory),
        transactionType: String(params.trsType),
        transactionDate: String(params.date),
        transactionNote: String(params.note)
    };
    const dataRequest = await useConfigFetchAction ({
        request: 'addTransaction',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}

export async function updateTransaction(params) {
    authRequired();
    if(!params.amount) return null;
    if(!params.trsCategory) return null;
    if(!params.trsType) return null;
    if(!params.date) return null;
    if(!params.id) return null;
    if(!params.note) params.note = '';

    console.log('params.amount', params.amount)
    console.log('params.amount', typeof params.amount)

    const localToken = getLStorageAuthToken();
    const body = {
        transactionId: Number(params.id),
        transactionAmount: Number(params.amount),
        transactionCategory: String(params.trsCategory),
        transactionType: String(params.trsType),
        transactionDate: String(params.date),
        transactionNote: String(params.note)
    };
    const dataRequest = await useConfigFetchAction ({
        request: 'updateTransaction',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}

export async function deleteTransaction(transactionId) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const body = {
        transactionId: Number(transactionId),
    };
    const dataRequest = await useConfigFetchAction ({
        request: 'deleteTransaction',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}


export async function updateUserProfil(params) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const body = {
        firstName: String(params.firstName),
        lastName: String(params.lastName),
        birthday: String(params.birthday),
        gender: String(params.gender)
    };
    const response = await useConfigFetchAction ({
        request: 'updateUserProfil', 
        method: 'POST', 
        dataBody: body, 
        token: localToken
    });
    return response;
}

export async function sendUpdateMail(params) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const body = {
        newEmail: String(params.newEmail),
    };
    const response = await useConfigFetchAction ({
        request: 'sendUpdateMail', 
        method: 'POST', 
        dataBody: body, 
        token: localToken
    });
    return response;
}

export async function sendEmailToSupport(params) {
    const localToken = getLStorageAuthToken();
    const body = {
        firstName: String(params.firstName),
        lastName: String(params.lastName),
        emailSender: String(params.emailSender),
        message: String(params.message),
    };
    const response = await useConfigFetchAction ({
        request: 'sendEmailToSupport', 
        method: 'POST', 
        dataBody: body, 
        token: localToken
    });
    return response;
}

export async function updateEmail(token) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const body = {
        token: String(token),
    };
    const response = await useConfigFetchAction({
        request: 'updateEmail', 
        method: 'POST', 
        dataBody: body, 
        token: localToken
    });
    return response;
}