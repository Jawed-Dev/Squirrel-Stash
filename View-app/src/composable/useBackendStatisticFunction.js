import useConfigFetchGetData  from "@/composable/useConfigFetchGetData";
import { getLStorageAuthToken } from "@/composable/useLocalStorage";

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

export async function getLastNPurchases(month, year) {
    const localToken = getLStorageAuthToken();
    const data = {
        'selectedMonth': month,
        'selectedYear': year,
        'category': 'purchase',
        'limitValue': 5
    }
    const lastPurchases = await useConfigFetchGetData ({
        request: 'getLastNTransactions', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });

    return lastPurchases;
}

export async function getLastNRecurrings(month, year) {
    const localToken = getLStorageAuthToken();
    const data = {
        'selectedMonth': month,
        'selectedYear': year,
        'category': 'recurring',
        'limitValue': 5
    }
    const lastRecurrings = await useConfigFetchGetData ({
        request: 'getLastNTransactions', 
        method: 'POST', 
        dataBody: data, 
        token: localToken
    });

    return lastRecurrings;
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