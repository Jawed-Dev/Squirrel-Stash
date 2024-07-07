import { getBiggestTrsByMonth, getThresholdByMonth, getTotalTrsByMonth, getListTrsMonthByDay, getLastNTransactions, getListTrsBySearch } from '@/composable/useBackendGetData';
import { storeParamsSearch, storeThreshold, storeStatisticDetails, storeTrsMonthByDay, storeLastNTransactions, storeListTrsSearch } from '@/storePinia/useStoreDashboard';


// list transactions month By Day
export async function updateListTrsMonthByDay(month, year, transactionType) {
    const transactionsMonthByDay = storeTrsMonthByDay();
    const listTransactionsFetched = await getListTrsMonthByDay(month, year, transactionType);
    const localListTransactions = listTransactionsFetched?.data;
    if(transactionType === 'purchase') {
        transactionsMonthByDay.listPurchases = (localListTransactions) ? localListTransactions : [];
    }
    else if(transactionType === 'recurring') {
        transactionsMonthByDay.listRecurrings = (localListTransactions) ? localListTransactions : [];
    }
}

export async function updateLastNTrsByMonth(month, year, transactionType) {
    const lastNTransactions = storeLastNTransactions();
    const lastTransactionsFetched = await getLastNTransactions(month, year, transactionType);
    const listLastTransactions = lastTransactionsFetched?.data;
    if(!listLastTransactions) return;

    const MAX_TRANSACTIONS = 5;

    if(transactionType === 'purchase') {
        const arrayRender = [];
        for (let index = 0; index < MAX_TRANSACTIONS; index++) {
            if(listLastTransactions[index]) arrayRender[index] = listLastTransactions[index];
            else {
                arrayRender[index] = {transaction_id: null, transaction_type: 'purchase'};
            }
        }
        lastNTransactions.listPurchases = arrayRender;
    }
    else  {
        const arrayRender = [];
        for (let index = 0; index < MAX_TRANSACTIONS; index++) {
            if(listLastTransactions[index]) arrayRender[index] = listLastTransactions[index];
            else {
                arrayRender[index] = {transaction_id: null, transaction_type: 'recurring'};
            }
        }
        lastNTransactions.listRecurrings = arrayRender;
    }
}

export async function updateThresholdByMonth(month, year) {
    const threshold = storeThreshold();
    const thresholdFetched = await getThresholdByMonth(month, year);
    const thresholdAmount = thresholdFetched?.data?.threshold_amount;
    threshold.amount  = (thresholdAmount) ? thresholdAmount : 0;
}

export async function updateTotalTrsByMonth(month, year) {
    const statisticDetails = storeStatisticDetails();
    const totalTransactionsFetched = await getTotalTrsByMonth(month, year);
    const totalTransactions = totalTransactionsFetched?.data?.total_transactions;
    statisticDetails.totalTransactions = (totalTransactions) ? totalTransactions : 0;
}

export async function updateBalanceEcoByMonth(month, year) {
    const thresholdFetched = await getThresholdByMonth(month, year);
    const totalTransactionsFetched = await getTotalTrsByMonth(month, year);

    const totalTransactions = totalTransactionsFetched?.data?.total_transactions;
    const thresholdAmount = thresholdFetched?.data?.threshold_amount;

    const statisticDetails = storeStatisticDetails();
    const economyBalanceValue = thresholdAmount - totalTransactions;
    statisticDetails.economyBalance = (economyBalanceValue) ? economyBalanceValue : 0;
}

export async function updateBiggestTrsByMonth(month, year, transactionType) {
    const statisticDetails = storeStatisticDetails();
    const biggestTrsFetched = await getBiggestTrsByMonth(month, year, transactionType);
    const biggestTransactions = biggestTrsFetched?.data;
    if(transactionType === 'purchase') {
        statisticDetails.biggestPurchase = (biggestTransactions) ? biggestTransactions : 'Aucune donnée';
    }
    else if(transactionType === 'recurring') {
        statisticDetails.biggestRecurring = (biggestTransactions) ? biggestTransactions : 'Aucune donnée';
    }
}

export async function updateAllDataTransations(month, year, transactionType) {
    const paramsSearch = storeParamsSearch();
    updateListTrsMonthByDay(month, year, transactionType);
    updateBalanceEcoByMonth(month, year);
    updateTotalTrsByMonth(month, year);
    updateLastNTrsByMonth(month, year, 'purchase');
    updateLastNTrsByMonth(month, year, 'recurring');
    updateBiggestTrsByMonth(month, year, 'purchase');
    updateBiggestTrsByMonth(month, year, 'recurring');
    console.log('params', paramsSearch.params);
    updateListTrsBySearch(paramsSearch.params);
}

export async function updateListTrsBySearch(params) {
    const listTransactionsSearch = storeListTrsSearch();
    listTransactionsSearch.listTransactions = await getListTrsBySearch(params);
}
