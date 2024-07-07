import useConfigFetchGetData  from "@/composable/useConfigFetchGetData";
import { getLStorageAuthToken, setLStorageAuthToken } from "@/composable/useLocalStorage";


async function authRequired() {
    const isSessionActive = await getStateSession();
    if(!isSessionActive) {
        redirectLogin();
    }
}

async function notAuthRequired() {
    const isSessionActive = await getStateSession();
    if(isSessionActive) {
        redirectDashboard();
    }
}

function redirectLogin() {
    window.location.href = '/connexion';
}

function redirectDashboard() {
    window.location.href = '/tableau-de-bord';
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

export async function getListTrsBySearch(params) {
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
    const listTransactionsFetched = await useConfigFetchGetData ({
        request: 'getListTrsBySearch',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    console.log('listTransactionsFetched', listTransactionsFetched, listTransactionsFetched);
    return listTransactionsFetched;
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

export async function getHandleLogin(email, password) {
    notAuthRequired();
    const dataLogin = {
        'email': String(email),
        'password': String(password)
    }
    const dataHandleLogin = await useConfigFetchGetData ({
        request: 'getHandleLogin', 
        method: 'POST', 
        dataBody: dataLogin, 
    });
    const isSuccessLogin = dataHandleLogin?.tokenJwt;
    if(isSuccessLogin) {
        setLStorageAuthToken(dataHandleLogin.tokenJwt);
    }
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