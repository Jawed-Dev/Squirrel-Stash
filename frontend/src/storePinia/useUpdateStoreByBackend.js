import {   
        getBiggestTrsByMonth, getThresholdByMonth, getTotalTrsByMonth, 
        getListTrsMonthByDay, getLastNTransactions, getDataTrsBySearch,
        getDataUserProfil, getUserEmail, getYearListTrsByMonth, getTotalTrsByYear,
        getBiggestTrsByYear, getBiggestMonthByYear, getYearListTrsByCategories,
        getTopYearCategories
} from '@/composable/useBackendGetData';

import { 
        storeParamsSearch, storeThreshold, storeStatisticDetails, 
        storeTrsMonthByDay, storeLastNTransactions, storeDataTrsSearch,
        storeProfilUser, storeEmailUser, storeYearListTrsByMonth, storeTextStatsByYear, 
        storeYearListTrsByCategories, storeTopYearCategories
} from '@/storePinia/useStoreDashboard';

import Decimal from 'decimal.js';
Decimal.config({ precision: 10 });

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

    const thresholdAmount = thresholdFetched?.data?.threshold_amount || 0;
    const formattedThresholdAmount = parseFloat(thresholdAmount).toFixed(2);

    threshold.amount = formattedThresholdAmount;
    console.log(`Threshold Amount set to: ${threshold.amount}`);
}

export async function updateTotalTrsByMonth(month, year) {
    const statisticDetails = storeStatisticDetails();
    const totalTransactionsFetched = await getTotalTrsByMonth(month, year);
    const totalTransactionsRaw = totalTransactionsFetched?.data?.total_transactions || 0;
    const totalTransactions = new Decimal(totalTransactionsRaw);
    statisticDetails.totalTransactions = parseFloat(totalTransactions).toFixed(2);
}

export async function updateBalanceEcoByMonth(month, year) {
    const thresholdFetched = await getThresholdByMonth(month, year);
    const totalTransactionsFetched = await getTotalTrsByMonth(month, year);

    const totalTransactions = new Decimal(totalTransactionsFetched?.data?.total_transactions || 0);
    const thresholdAmount = new Decimal(thresholdFetched?.data?.threshold_amount || 0);

    const economyBalanceValue = thresholdAmount.minus(totalTransactions);

    const statisticDetails = storeStatisticDetails();
    statisticDetails.economyBalance = parseFloat(economyBalanceValue).toFixed(2);

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
    updateListTrsMonthByDay(month, year, transactionType);
    updateBalanceEcoByMonth(month, year);
    updateTotalTrsByMonth(month, year);
    updateLastNTrsByMonth(month, year, 'purchase');
    updateLastNTrsByMonth(month, year, 'recurring');
    updateBiggestTrsByMonth(month, year, 'purchase');
    updateBiggestTrsByMonth(month, year, 'recurring');

    const currentUrl = window.location.href; 
    const url = new URL(currentUrl);
    if(url.pathname === '/historique-transactions') {
        const paramsSearch = storeParamsSearch();
        updateDataTrsSearch(paramsSearch.params);
    }
}

export async function updateDataTrsSearch(params) {
    const dataStore = storeDataTrsSearch();
    dataStore.dataTransactions = await getDataTrsBySearch(params);
}

export async function updateStoreUserProfil() {
    const dataStore = storeProfilUser();
    const response = await getDataUserProfil();
    const firstName = response.data.user_first_name;
    const lastName = response.data.user_last_name;
    const birthday = response.data.user_birthday;
    const gender = response.data.user_gender;
    const roleLevel = response.data.user_role_level;

    dataStore.data.firstName = firstName;
    dataStore.data.lastName = lastName;
    dataStore.data.birthday = birthday;
    dataStore.data.gender = gender;
    dataStore.data.roleLevel = roleLevel;
}

export async function updateStoreUserEmail() {
    const dataStore = storeEmailUser();
    const response = await getUserEmail();
    const email = response.data;
    dataStore.currentEmail = email;   
}

export async function updateStoreYearListTrsByMonth(year, transactionType) {
    const totalYearTrsByMonth = storeYearListTrsByMonth();
    const response = await getYearListTrsByMonth(year, transactionType);
    const listTransactions = response?.data;
    totalYearTrsByMonth.data = listTransactions;
}

export async function updateStoreTotalTrsByYear(year) {
    const textStatsByYear = storeTextStatsByYear();
    const response = await getTotalTrsByYear(year);
    const totalTransactionsRaw = response?.data.total_transactions || 0;
    const formatTotalTransactions = new Decimal(totalTransactionsRaw);
    textStatsByYear.totalTransactions = parseFloat(formatTotalTransactions).toFixed(2);
}

export async function updateBiggestTrsByYear(year, transactionType) {
    const textStatsByYear = storeTextStatsByYear();
    const responsive = await getBiggestTrsByYear(year, transactionType);
    const biggestTransactions = responsive?.data?.transaction_category;

    if(transactionType === 'purchase') {
        textStatsByYear.biggestPurchase = (biggestTransactions) ? biggestTransactions : '';
    }
    else if(transactionType === 'recurring') {
        textStatsByYear.biggestRecurring = (biggestTransactions) ? biggestTransactions : '';
    }
}

export async function updateStoreBiggestMonthByYear(year) {
    const textStatsByYear = storeTextStatsByYear();
    const response = await getBiggestMonthByYear(year);
    const biggestMonth = response?.data?.month;
    textStatsByYear.biggestMonth = (biggestMonth) ? biggestMonth : 'Aucune donnée';
}

export async function updateStoreYearListTrsByCategories(year, transactionType) {
    const listTrsByCategories = storeYearListTrsByCategories();
    const response = await getYearListTrsByCategories(year, transactionType);
    const listTransactions = response?.data;
    listTrsByCategories.data = listTransactions;
}

export async function updateStoreTopYearCategories(year, transactionType) {
    const topCategories = storeTopYearCategories();
    const response = await getTopYearCategories(year, transactionType);
    const listCategories = response?.data;
    console.log('updateStoreTopYearCategories', listCategories)
    if(transactionType === 'purchase') topCategories.listPurchases = listCategories;
    else if(transactionType === 'recurring') topCategories.listRecurrings = listCategories;
}