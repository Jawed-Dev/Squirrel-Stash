<template>
    <div class="pl-5 bg-main-gradient text-white rounded-md gradient-border ">
        <h2 class="py-3 text-[25px] font-extralight">Achats du mois</h2>
        <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achat'" :text2="'Prélèvements'" />
        <useGraphBarLoad :colorsGraph="(!typeTransaction) ? colorPurchases : colorReccurings" :dataTransaction="(!typeTransaction) ? listTransactions : dataPurchases" />
    </div>
</template>

<script setup>
    import { ref, watch } from 'vue';
    import ToggleButton from '@/components/button/ToggleButton.vue';
    import useGraphBarLoad from '@/composables/useGraphBarLoad.vue';
    import useFetchForm from '@/composables/useFetchForm';
    import {getMonthNumber} from '@/composables/useGetDate';

    // functions, props ...
    const props = defineProps({
        monthSelected: {default: ''},
        yearSelected: {default: ''}
    });

    const typeTransaction = ref(false);
    const listTransactions = ref([]);

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

    // detect new fetch listTransactions
    watch( () => [props.yearSelected, props.monthSelected], async ([newYear, newMonth]) => {
        const localToken = localStorage.getItem('authToken');
        const fetchedListTransactions = await useFetchForm({
            form: 'listTransactionsMonth',
            method: 'POST',
            dataForm: {
                monthSelected: getMonthNumber(newMonth),
                yearSelected: newYear
            },
            token: localToken
        });
        listTransactions.value = fetchedListTransactions?.data;
    }, {  immediate: true, deep:true });


    // detect toggle transaction
    watch(typeTransaction, (newVal, oldVal) => {
        // alert('changement');
    });

    

    const dataPurchases = [
        { day: 1, total_amount: 10 },
        { day: 2, total_amount: 250 },
        { day: 3, total_amount: 220 },
        { day: 4, total_amount: 180 },
        { day: 5, total_amount: 0 },
        { day: 6, total_amount: 30 },
        { day: 7, total_amount: 35 },
        { day: 8, total_amount: 40 },
        { day: 9, total_amount: 208 },
        { day: 10, total_amount: 26 },
        { day: 11, total_amount: 30 },
        { day: 12, total_amount: 34 },
        { day: 13, total_amount: 0 },
        { day: 14, total_amount: 400 },
        { day: 15, total_amount: 0 },
        { day: 16, total_amount: 29 },
        { day: 17, total_amount: 45 },
        { day: 18, total_amount: 0 },
        { day: 19, total_amount: 47 },
        { day: 20, total_amount: 52 },
        { day: 21, total_amount: 55 },
        { day: 22, total_amount: 0 },
        { day: 23, total_amount: 150 },
        { day: 24, total_amount: 48 },
        { day: 25, total_amount: 45 },
        { day: 26, total_amount: 40 },
        { day: 27, total_amount: 35 },
        { day: 28, total_amount: 30 },
        { day: 29, total_amount: 25 },
        { day: 30, total_amount: 20 },
        { day: 31, total_amount: 15 }
    ];

    const dataReccurings = [
        { day: 1, count: 200 },
        { day: 2, count: 500 },
        { day: 3, count: 0 },
        { day: 4, count: 0 },
        { day: 5, count: 0 },
        { day: 6, count: 0 },
        { day: 7, count: 35 },
        { day: 8, count: 30 },
        { day: 9, count: 0 },
        { day: 10, count: 0 },
        { day: 11, count: 120 },
        { day: 12, count: 0 },
        { day: 13, count: 0 },
        { day: 14, count: 30 },
        { day: 15, count: 0 },
        { day: 16, count: 0 },
        { day: 17, count: 0 },
        { day: 18, count: 0 },
        { day: 19, count: 0 },
        { day: 20, count: 200 },
        { day: 21, count: 0 },
        { day: 22, count: 0 },
        { day: 23, count: 0 },
        { day: 24, count: 0 },
        { day: 25, count: 0 },
        { day: 26, count: 0 },
        { day: 27, count: 0 },
        { day: 28, count: 0 },
        { day: 29, count: 0 },
        { day: 30, count: 0 },
        { day: 31, count: 0 }
    ];


</script>