<template>
    <div class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-black shadow-custom-main ">
        <div class="gradient-border pl-2 text-white overflow-hidden
            bg-main-gradient w-full">     
    
            <div class="flex items-center justify-between">
                <h2 class="py-3 text-[20px] font-extralight pr-8">{{ props.title}}</h2>
                <p :class="`cursor-pointer pr-3 ${translateY}`">Voir plus ></p>
            </div>
    
            <div class="flex border-gray-700 p-[calc(50px+60px+15px)] py-2">
                <p class="w-[20%]">Nom</p>
                <p class="w-[20%]">Montant</p>
                <p class="w-[20%]">Date</p>
                <p class="w-[20%]">Itération</p>
            </div>
    
            <div class="pl-3">
                <PurchaseInfo v-for="(purchase, index) of filteredTransactions" 
                    :key="index" :nameIcon="`restaurant`" :purchaseType="props.purchaseType" 
                    v-model:currentMenu="currentIdMenuOpen" :indexMenu="index" :svg="svg" :infoPurchase="purchase"
                />
            </div>
        </div>
    </div>
</template>


<script setup>
    // import
    import { onMounted, computed } from 'vue';
    import PurchaseInfo from '@/components/statistic/PurchaseInfo.vue';
    import { ref, watch } from 'vue';
    import { classTransitionHover } from '@/composable/useClassTransitionHover';
    import { storelastNTransactions, storeDateSelected } from '@/store/useStoreDashboard';
    import { getLastNPurchases, getLastNRecurrings } from '@/composable/useBackendStatisticFunction';

    // stores
    const lastNTransactions = storelastNTransactions();
    const dateSelected = storeDateSelected();
    const monthSelected = dateSelected.month;

    // variables, props...
    const currentIdMenuOpen = ref(-1);
    const translateY = classTransitionHover('translateY');
    const props = defineProps({
        svg: { default: {} }, 
        title: { default: '' },
        purchaseType: {default: 'standard'}
    });

    // life cycle
    watch( () => [dateSelected.year, dateSelected.month], async ([newYear, newMonth]) => {
        console.log('pinia', monthSelected);

        const lastPurchasesFetched = await getLastNPurchases(newMonth, newYear);
        const listLastPurchases = lastPurchasesFetched?.data;
        
        if(listLastPurchases) lastNTransactions.listPurchases = listLastPurchases;

        console.log('pinia', dateSelected.month);
        const lastRecurringsFetched = await getLastNRecurrings(newMonth, newYear);
        const listLastRecurrings = lastRecurringsFetched?.data;
        //console.log(listLastRecurrings);
        if(listLastRecurrings) lastNTransactions.listRecurrings = listLastRecurrings;
    }, {  immediate:true, deep:true });

    const filteredTransactions = computed(() => {
        return props.purchaseType === 'purchase' ? lastNTransactions.listPurchases : lastNTransactions.listRecurrings;
    });

    watch(currentIdMenuOpen, (newVal, oldVal) => {
        //alert(newVal);
    });

    const infoPurchase = {
        name: 'Spotify',
        price: "100.00",
        date: '26/03/2024',
        iteration: 10
    };

    const listPurchases = [
        { infoPurchase },
        { infoPurchase },
        { infoPurchase },
        { infoPurchase },
        { infoPurchase }
    ];
</script>