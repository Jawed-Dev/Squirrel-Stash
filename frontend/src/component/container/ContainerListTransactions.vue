<template>
    <div class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-black shadow-custom-main">
        <div class="gradient-border text-white overflow-hidden
            bg-main-gradient w-full pl-3">     
    
            <div class="flex items-center justify-between">
                <h2 class="py-3 text-[20px] font-extralight pr-8">{{ props.title}}</h2>
                <p :class="`cursor-pointer pr-3 ${translateY}`">Voir plus ></p>
            </div>
            <div class="flex border-gray-700 py-2 pl-3">
                <p class="ml-[6%] pl-[15px] w-[20%]">Catégorie</p>
                <p class="w-[20%]">Montant</p>
                <p class="w-[20%]">Date</p>
                <p class="w-[15%]">Itération</p>
            </div>
            <div class="pl-3">
                <ContainerTransactionInfo v-for="(transaction, index) of listTransactionStore" 
                    :key="transaction.transaction_id"
                    v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" :indexMenu="index" :infoTransaction="transaction"
                />
            </div>
        </div>
    </div>
</template>


<script setup>
    // import
    import { ref, watch, computed } from 'vue';
    import ContainerTransactionInfo from '@/component/container/ContainerTransactionInfo.vue';
    import { classTransitionHover } from '@/composable/useClassTransitionHover';
    import { storeLastNTransactions, storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateLastNTrsByMonth } from '@/storePinia/useUpdateStoreByBackend';

    // stores Pinia
    const lastNTransactions = storeLastNTransactions();
    const dateSelected = storeDateSelected();

    // variables, props...
    const currentMenuEditDeleteTrs = ref(-1);
    const translateY = classTransitionHover('translateY');
    const props = defineProps({
        svg: { default: {} }, 
        title: { default: '' },
        componentType: {default: 'purchase'}
    });

    // life cycle
    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        updateLastNTrsByMonth(newMonth, newYear, 'purchase');
        updateLastNTrsByMonth(newMonth, newYear, 'recurring');
    }, {  immediate:true, deep:true });

    const listTransactionStore = computed(() => {
        const listTransaction = (props.componentType === 'purchase') ? lastNTransactions.listPurchases : lastNTransactions.listRecurrings;
        return listTransaction;
    });
</script>