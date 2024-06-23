<template>
    <div class="pl-5 bg-main-gradient text-white rounded-md gradient-border ">
        <h2 class="py-3 text-[25px] font-extralight">{{(!typeTransaction) ? 'Achats du mois' : 'Transactions du mois'}}</h2>
        <div class="w-full flex justify-center">
            <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
        </div>
        <useGraphBarLoad :colorsGraph="(!typeTransaction) ? colorPurchases : colorReccurings" 
        :dataTransaction="(!typeTransaction) ? transactionsMonthByDay.listPurchases : transactionsMonthByDay.listRecurrings" />
    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import ToggleButton from '@/components/button/ToggleButton.vue';
    import useGraphBarLoad from '@/composable/useGraphBarLoad.vue';
    import useConfigFetchGetData from '@/composable/useConfigFetchGetData';
    import { getLStorageAuthToken } from "@/composable/useLocalStorage";
    import { storeTrsMonthByDay } from '@/store/useStoreDashboard';
    import { storeDateSelected } from '@/store/useStoreDashboard';

    // stores
    const transactionsMonthByDay = storeTrsMonthByDay();
    const dateSelected = storeDateSelected();

    // variables, props ...
    const typeTransaction = ref(false);

    const colorPurchases = {
        color1: '#3358f4',
        color2: '#1d8cf810',
        borderColor: '#0098f0'
    };
    const colorReccurings = {
        color1: '#89216B',
        color2: '#DA445310',
        borderColor: '#ec250d'
    }
    // functions 
    onMounted( async () => {
        
    });

    // toggle into select component (year&month)
    watch( () => [dateSelected.year, dateSelected.month], async ([newYear, newMonth]) => {
        const localToken = getLStorageAuthToken();
        //alert(newYear, newMonth);
        const body = {
            selectedMonth: newMonth,
            selectedYear: newYear
        };
        console.log(newYear, newMonth);
        const listTransactionsFetched = await useConfigFetchGetData ({
            request: 'getlistTrsByMonth',
            method: 'POST',
            dataBody: body,
            token: localToken
        });

        // was a solution for dont share references of listTransactions, spread operators wasnt solved the problem
        const listTransactions = JSON.parse(JSON.stringify(listTransactionsFetched?.data));

        const localListPurchases = JSON.parse(JSON.stringify(listTransactions));
        const localListRecurrings = JSON.parse(JSON.stringify(listTransactions));
        listTransactions.forEach((transaction,index) => {
            if(transaction.transaction_category !== 'purchase') {
                localListPurchases[index].total_amount = 0;
                localListPurchases[index].amount = 0;
                localListPurchases[index].transaction_id = null;
                localListPurchases[index].transaction_user_id= null;
            }
            if(transaction.transaction_category !== 'recurring') {
                localListRecurrings[index].total_amount = 0;
                localListRecurrings[index].amount = 0;
                localListRecurrings[index].transaction_id = null;
                localListRecurrings[index].transaction_user_id= null;
            }
        });
        transactionsMonthByDay.listPurchases = localListPurchases;
        transactionsMonthByDay.listRecurrings = localListRecurrings;

    }, {  immediate:true, deep:true });

    // detect toggle button transaction
    watch(typeTransaction, (newVal, oldVal) => {
        // alert('changement');
    });
</script>