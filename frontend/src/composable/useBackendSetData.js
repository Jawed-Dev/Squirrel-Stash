import useConfigFetchSetData  from "@/composable/useConfigFetchSetData";
import { getLStorageAuthToken } from "@/composable/useLocalStorage";

export async function saveThreshold(month, year, amount) {
    const localToken = getLStorageAuthToken();
    const body = {
        selectedMonth: Number(month),
        selectedYear: Number(year),
        thresholdAmount: Number(amount)
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
        transactionAmount: Number(params.amount),
        transactionCategory: String(params.trsCategory),
        transactionType: String(params.trsType),
        transactionDate: String(params.date),
        transactionNote: String(params.note)
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
        transactionId: Number(params.id),
        transactionAmount: Number(params.amount),
        transactionCategory: String(params.trsCategory),
        transactionType: String(params.trsType),
        transactionDate: String(params.date),
        transactionNote: String(params.note)
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
        transactionId: Number(transactionId),
    };
    const dataRequest = await useConfigFetchSetData ({
        request: 'deleteTransaction',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}


