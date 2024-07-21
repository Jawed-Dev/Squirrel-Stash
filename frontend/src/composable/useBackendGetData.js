import useConfigFetchGetData  from "@/composable/useConfigFetchGetData";
import { getLStorageAuthToken, setLStorageAuthToken } from "@/composable/useLocalStorage";


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

export async function getNewAccessToken() {
    const localToken = getLStorageAuthToken();
    const response = await useConfigFetchGetData ({
        request: 'getNewAccessToken',
        method: 'POST',
        token: localToken,
        dataBody: 'none',
    });
    const refreshToken = response?.refreshToken;
    return (refreshToken) ? refreshToken : null;
}

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


export async function getUserFirstName() {
    const localToken = getLStorageAuthToken();
    const response = await useConfigFetchGetData ({
        request: 'getUserFirstName',
        method: 'POST',
        token: localToken,
        dataBody: 'none',
    });
    return response;
}

export async function isValidResetPassToken(resetPassToken) {
    const localToken = getLStorageAuthToken();
    const body = {
        resetPassToken: resetPassToken,
    };
    const stateSession = await useConfigFetchGetData ({
        request: 'IsValidResetPassToken',
        method: 'POST',
        token: localToken,
        dataBody: body,
    });
    const isSuccessRequest = stateSession?.isSuccessRequest;
    return (isSuccessRequest) ? true : false;
}

export async function getDataTrsBySearch(params) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const body = {  
        searchDateRangeDateMin: String(params.searchDateRangeDateMin),
        searchDateRangeDateMax: String(params.searchDateRangeDateMax),
        searchCategory: String(params.searchCategory),
        searchType: String(params.searchType),
        searchNote: String(params.searchNote),
        searchAmountMin: Number(params.searchAmountMin),
        searchAmountMax: Number(params.searchAmountMax),
        currentOrderSelected: Number(params.currentOrderSelected),
        orderAsc: Boolean(params.orderAsc),
        currentPage: Number(params.currentPage)
    };
    const dataTrsSearch = await useConfigFetchGetData ({
        request: 'getDataTrsBySearch',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataTrsSearch;
}


export async function getListTrsMonthByDay(month, year, transactionType) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const body = {
        selectedMonth: Number(month),
        selectedYear: Number(year),
        transactionType: String(transactionType)
    };
    const listTransactionsFetched = await useConfigFetchGetData ({
        request: 'getlistTrsMonthByDay',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return listTransactionsFetched;
}

export async function getThresholdByMonth(month, year) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const data = {
        selectedMonth: Number(month),
        selectedYear: Number(year)
    }
    const thresholdAmount = await useConfigFetchGetData ({
        request: 'getThresholdByMonth', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });
    return thresholdAmount;
}

export async function getHandleLogin(params) {
    //notAuthRequired();
    const dataLogin = {
        'email': String(params.email),
        'password': String(params.password),
        'stayConnected': Boolean(params.stayConnected),
    }
    const dataHandleLogin = await useConfigFetchGetData ({
        request: 'getHandleLogin', 
        method: 'POST', 
        dataBody: dataLogin, 
    });
    const isSuccessLogin = dataHandleLogin?.tokenJwt;
    if(isSuccessLogin) setLStorageAuthToken(dataHandleLogin.tokenJwt);
    return isSuccessLogin;
}

export async function getBalanceEcoByMonth(month, year) {
    authRequired();
    const isSessionActive = await getStateSession();
    if(!isSessionActive) {
        redirectLogin();
    }

    const localToken = getLStorageAuthToken();
    const data = {
        selectedMonth: Number(month),
        selectedYear: Number(year),
    }
    const lastPurchases = await useConfigFetchGetData ({
        request: 'getBalanceEcoByMonth', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });

    return lastPurchases;
}

export async function getLastNTransactions(month, year, transactionType) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const data = {
        selectedMonth: Number(month),
        selectedYear: Number(year),
        transactionType: String(transactionType),
    }
    const lastTransactions = await useConfigFetchGetData ({
        request: 'getLastNTransactions', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });
    return lastTransactions;
}

export async function getTotalTrsByMonth(month, year) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const data = {
        selectedMonth: Number(month),
        selectedYear: Number(year),
    }
    const lastPurchases = await useConfigFetchGetData ({
        request: 'getTotalTrsByMonth', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });

    return lastPurchases;
}

export async function getBiggestTrsByMonth(month, year, transactionType) {
    authRequired();
    const localToken = getLStorageAuthToken();
    const data = {
        selectedMonth: Number(month),
        selectedYear: Number(year),
        transactionType: String(transactionType)
    }
    const biggestTransaction = await useConfigFetchGetData ({
        request: 'getBiggestTrsByMonth', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });

    return biggestTransaction;
}

export async function getDataUserProfil() {
    authRequired();
    const localToken = getLStorageAuthToken();
    const response = await useConfigFetchGetData ({
        request: 'getDataUserProfil', 
        method: 'POST', 
        dataBody: ['none'], 
        token: localToken
    });
    return response;
}

export async function getUserEmail() {
    authRequired();
    const localToken = getLStorageAuthToken();
    const response = await useConfigFetchGetData ({
        request: 'getUserEmail', 
        method: 'POST', 
        dataBody: ['none'], 
        token: localToken
    });
    return response;
}

