<template>
    <div class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-black shadow-custom-main ">
        <div class="gradient-border text-white overflow-hidden
            bg-main-gradient w-full pl-3">     
    
            <div class="flex items-center justify-between">
                <h2 class="py-3 text-[20px] font-extralight pr-8">{{ props.title}}</h2>
                <p :class="`cursor-pointer pr-3 ${translateY}`">Voir plus ></p>
            </div>
            <!-- p-[calc(50px+60px+15px)] -->
            <div class="flex border-gray-700 py-2 pl-3">
                <p class="ml-[6%] pl-[15px] w-[20%]">Nom</p>
                <p class="w-[20%]">Montant</p>
                <p class="w-[20%]">Date</p>
                <p class="w-[15%]">Itération</p>
            </div>
    
            <div class="pl-3">
                <PurchaseInfo v-for="(transaction, index) of filteredTransactions" 
                    :key="index" :nameIcon="transaction.transaction_name" :transactionType="transactionType" 
                    v-model:currentTrsIndexSelect="currentTrsIndexSelect" :indexMenu="index" :svg="svg" :infoTransaction="transaction"
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
    import { storeLastNTransactions, storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateLastNTrsByMonth } from '@/storePinia/useUpdateStoreByBackend';

    // stores Pinia
    const lastNTransactions = storeLastNTransactions();
    const dateSelected = storeDateSelected();

    // variables, props...
    const currentTrsIndexSelect = ref(-1);
    const translateY = classTransitionHover('translateY');
    const props = defineProps({
        svg: { default: {} }, 
        title: { default: '' },
        transactionType: {default: ''}
    });

    // life cycle
    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {

        updateLastNTrsByMonth(newMonth, newYear, 'purchase');
        updateLastNTrsByMonth(newMonth, newYear, 'recurring');
        
    }, {  immediate:true, deep:true });

    const filteredTransactions = computed(() => {
        return props.transactionType === 'purchase' ? lastNTransactions.listPurchases : lastNTransactions.listRecurrings;
    });


    watch(currentTrsIndexSelect, (newVal, oldVal) => {
        //alert(newVal);
    });

</script>