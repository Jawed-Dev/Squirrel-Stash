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
    import { ref, watch } from 'vue';
    import ToggleButton from '@/component/button/ToggleButton.vue';
    import useGraphBarLoad from '@/composable/useGraphBarLoad.vue';
    import { storeTrsMonthByDay, storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateListTrsMonthByDay } from '@/storePinia/useUpdateStoreByBackend';
    
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
    // life cycle / functions

    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        updateListTrsMonthByDay(newMonth, newYear, 'purchase');
        updateListTrsMonthByDay(newMonth, newYear, 'recurring');
    }, {  immediate:true, deep:true });

</script>