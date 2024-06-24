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
        request: 'addTransaction',
        method: 'POST',
        dataBody: body,
        token: localToken
    });
    return dataRequest;
}


export async function addTransaction(params) {
    if(!params.amount) return null;
    if(!params.trsName) return null;
    if(!params.category) return null;
    if(!params.date) return null;
    if(!params.note) return null;

    console.log(params);

    const localToken = getLStorageAuthToken();
    const body = {
        transactionAmount: params.amount,
        transactionName: params.trsName,
        category: params.category,
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

