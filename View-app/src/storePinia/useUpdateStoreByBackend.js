import { getBiggestTrsByMonth, getThresholdByMonth, getTotalTrsByMonth, getListTrsMonthByDay, getLastNTransactions } from '@/composable/useBackendGetData';
import { storeThreshold, storeStatisticDetails, storeTrsMonthByDay, storelastNTransactions } from '@/storePinia/useStoreDashboard';


// list transactions month By Day
export async function updateListTrsMonthByDay(month, year, category) {
    const transactionsMonthByDay = storeTrsMonthByDay();
    const listTransactionsFetched = await getListTrsMonthByDay(month, year, category);
    const localListTransactions = listTransactionsFetched?.data;
    if(category === 'purchase') {
        transactionsMonthByDay.listPurchases = (localListTransactions) ? localListTransactions : [];
    }
    else if(category === 'recurring') {
        transactionsMonthByDay.listRecurrings = (localListTransactions) ? localListTransactions : [];
    }
}

export async function updateLastNTrsByMonth(month, year, category) {
    const lastNTransactions = storelastNTransactions();
    const lastTransactionsFetched = await getLastNTransactions(month, year, category);
    const listLastTransactions = lastTransactionsFetched?.data;

    if(category === 'purchase') {
        lastNTransactions.listPurchases = (listLastTransactions) ? listLastTransactions : [];
    }
    else if(category === 'recurring') {
        lastNTransactions.listRecurrings = (listLastTransactions) ? listLastTransactions : [];
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

export async function updateBiggestTrsByMonth(month, year, category) {
    const statisticDetails = storeStatisticDetails();
    const biggestTrsFetched = await getBiggestTrsByMonth(month, year, category);
    const biggestTransactions = biggestTrsFetched?.data?.transaction_name;
    if(category === 'purchase') {
        statisticDetails.biggestPurchase = (biggestTransactions) ? biggestTransactions : 'Aucune donnée';
    }
    else if(category === 'recurring') {
        statisticDetails.biggestRecurring = (biggestTransactions) ? biggestTransactions : 'Aucune donnée';
    }
}