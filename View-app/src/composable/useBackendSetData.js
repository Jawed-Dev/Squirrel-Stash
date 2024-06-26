import useConfigFetchSetData  from "@/composable/useConfigFetchSetData";
import { getLStorageAuthToken } from "@/composable/useLocalStorage";

export async function saveThreshold(month, year, amount) {
    const localToken = getLStorageAuthToken();
    const body = {
        selectedMonth: month,
        selectedYear: year,
        thresholdAmount: amount
    };
    const dataRequest = await useConfigFetchSetData ({
        request: 'saveThreshold',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}


export async function addTransaction(params) {
    if(!params.amount) return null;
    if(!params.trsCategory) return null;
    if(!params.trsType) return null;
    if(!params.date) return null;
    if(!params.note) params.note = '';

    const localToken = getLStorageAuthToken();
    const body = {
        transactionAmount: params.amount,
        transactionCategory: params.trsCategory,
        transactionType: params.trsType,
        transactionDate: params.date,
        transactionNote: params.note
    };
    const dataRequest = await useConfigFetchSetData ({
        request: 'addTransaction',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}

export async function updateTransaction(params) {
    if(!params.amount) return null;
    if(!params.trsCategory) return null;
    if(!params.trsType) return null;
    if(!params.date) return null;
    if(!params.id) return null;
    if(!params.note) params.note = '';

    const localToken = getLStorageAuthToken();
    const body = {
        transactionId: params.id,
        transactionAmount: params.amount,
        transactionCategory: params.trsCategory,
        transactionType: params.trsType,
        transactionDate: params.date,
        transactionNote: params.note
    };
    const dataRequest = await useConfigFetchSetData ({
        request: 'updateTransaction',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}

export async function deleteTransaction(transactionId) {
    const localToken = getLStorageAuthToken();
    const body = {
        transactionId: transactionId,
    };
    const dataRequest = await useConfigFetchSetData ({
        request: 'deleteTransaction',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}


