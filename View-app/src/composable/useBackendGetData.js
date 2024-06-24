import useConfigFetchGetData  from "@/composable/useConfigFetchGetData";
import { getLStorageAuthToken, setLStorageAuthToken } from "@/composable/useLocalStorage";

export async function getListTrsMonthByDay(month, year, category) {
    const localToken = getLStorageAuthToken();
    const body = {
        selectedMonth: month,
        selectedYear: year,
        category: category
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
    const localToken = getLStorageAuthToken();
    const data = {
        'selectedMonth': month,
        'selectedYear': year
    }
    const thresholdAmount = await useConfigFetchGetData ({
        request: 'getThresholdByMonth', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });
    return thresholdAmount;
}

export async function getHandleLogin(email, pass) {
    const dataLogin = {
        'email': email,
        'password': pass
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
    const localToken = getLStorageAuthToken();
    const data = {
        'selectedMonth': month,
        'selectedYear': year,
    }
    const lastPurchases = await useConfigFetchGetData ({
        request: 'getBalanceEcoByMonth', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });

    return lastPurchases;
}

export async function getLastNTransactions(month, year, category) {
    const localToken = getLStorageAuthToken();
    const data = {
        'selectedMonth': month,
        'selectedYear': year,
        'category': category,
        'limitValue': 5
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
    const localToken = getLStorageAuthToken();
    const data = {
        'selectedMonth': month,
        'selectedYear': year,
    }
    const lastPurchases = await useConfigFetchGetData ({
        request: 'getTotalTrsByMonth', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });

    return lastPurchases;
}

export async function getBiggestTrsByMonth(month, year, category) {
    const localToken = getLStorageAuthToken();
    const data = {
        'selectedMonth': month,
        'selectedYear': year,
        'category': category
    }
    const biggestTransaction = await useConfigFetchGetData ({
        request: 'getBiggestTrsByMonth', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });

    return biggestTransaction;
}