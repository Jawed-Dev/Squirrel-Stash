<template>
     <section class="w-full mt-5 rounded-[3px] overflow-hidden shadow-main relative">
        <div class="pl-1 sm:pl-3 bg-main-gradient text-white rounded-md gradient-border">
            <h2 class="pb-2 pt-3 text-lg sm:text-xl font-extralight pr-8 sm:pb-0">{{(!typeTransaction) ? 'Achats du mois' : 'Prélèvements du mois'}}</h2>
            <div class="w-full flex justify-center">
                <div class="w-1/6 min-w-[250px] ">
                    <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
                </div>
            </div>
            <typeGraph 
            :class="opacityDataEmpty" :colorsGraph="(!typeTransaction) ? colorPurchases : colorReccurings" 
            :dataTransaction="listTransactions" />
        </div>
         <div v-show="isDataEmpty" class="w-full">
            <p class="opacity-100 absolute w-fit transform -translate-x-1/2 -translate-y-1/2 text-center text-xl text-white top-1/2 left-1/2">Aucune donnée</p>
        </div>    
     </section>
</template>

<script setup>
    import { ref, watch, computed } from 'vue';
    import ToggleButton from '@/components/button/ToggleButton.vue';
    import useGraphBar from '@/composables/useGraphBar.vue';
    import useGraphLine from '@/composables/useGraphLine.vue';
    import { storeTotalTrsMonthByDay, storeDateSelected } from '@/storesPinia/useStoreDashboard';
    import { updateListTrsMonthByDay } from '@/storesPinia/useUpdateStoreByBackend';
    import { getScreenSize } from '@/composables/useSizeScreen';
    
    // stores
    const transactionsMonthByDay = storeTotalTrsMonthByDay();
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
    };
    const { widthScreenValue } = getScreenSize();

    // life cycle / functions
    const isDataEmpty = computed(() => {
        let isAllDataEmpty = true;
        listTransactions.value.forEach(data => {
            if (data.total_amount > 0) isAllDataEmpty = false;
        });
        return isAllDataEmpty;
    });

    const typeGraph = computed(() => {
        return (widthScreenValue.value < 700) ? useGraphLine : useGraphBar; 
    });
    
    const listTransactions = computed(() => {
        return (!typeTransaction.value) ? transactionsMonthByDay.listPurchases : transactionsMonthByDay.listRecurrings;
    })

    const opacityDataEmpty = computed(() => {
        return isDataEmpty.value ? 'opacity-50' : '';
    });

    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        updateListTrsMonthByDay(newMonth, newYear, 'purchase');
        updateListTrsMonthByDay(newMonth, newYear, 'recurring');
    }, {  immediate:true, deep:true });

</script>