<template>
    <div class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-main relative">
        <div class="bg-main-gradient w-full gradient-border text-white"> 
    
            <div class="flex items-center justify-between">
                <h2 class="pl-3 pb-2 pt-3 text-xl font-extralight pr-8 sm:pb-0">{{ textTitle }}</h2>
                <router-link to="/historique-transactions" :class="`pt-3  font-light cursor-pointer pr-3 ${translateY}`">Voir plus ></router-link>
            </div>

            <div class="flex w-full xl:hidden justify-center ">
                <div class="w-1/6 min-w-[250px]">
                    <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
                </div>
            </div>

            <div class="absolute left-0 w-full flex py-2 pl-0 sm:pl-4 md:pl-3 mt-5 bg-gradient-x-blue shadow-main">
                <p class="text-[15px] sm:text-base ml-[20px] sm:ml-[40px] md:pl-[20px] w-[25%] overflow-hidden text-ellipsis">Catégorie</p>
                <p class="text-[15px] sm:text-base w-[22%] pl-5 sm:pl-0 sm:justify-stretch flex justify-center">Montant</p>
                <p class="text-[15px] sm:text-base w-[22%] sm:justify-stretch flex justify-center">Date</p>
                <p class="text-[15px] sm:text-base grow sm:justify-stretch flex justify-center">Itération</p>
            </div>
            <div class="mt-[calc(105px-45px)]">
                <ContainerTransactionInfo 
                    v-for="(transaction, index) of listTransactions" 
                    :key="transaction.transaction_id"
                    v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" 
                    :indexMenu="index" 
                    :infoTransaction="transaction"
                    :lengthData="filteredTransactions.length"
                />
            </div>
        </div>
    </div>
</template>


<script setup>
    // import
    import { ref, watch, computed, defineAsyncComponent } from 'vue';
    import ContainerTransactionInfo from '@/component/container/ContainerTransactionInfo.vue';
    import { classTransitionHover } from '@/composable/useClassTransitionHover';
    import { storeLastNTransactions, storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateLastNTrsByMonth } from '@/storePinia/useUpdateStoreByBackend';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    import ToggleButton from '@/component/button/ToggleButton.vue';
    

    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));

    // stores Pinia
    const lastNTransactions = storeLastNTransactions();
    const dateSelected = storeDateSelected();

    // variables, props...
    const currentMenuEditDeleteTrs = ref(-1);
    const translateY = classTransitionHover('translateY');
    const typeTransaction = defineModel();

    // life cycle
    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        updateLastNTrsByMonth(newMonth, newYear, 'purchase');
        updateLastNTrsByMonth(newMonth, newYear, 'recurring');
    }, {  immediate:true, deep:true });

    const listTransactions = computed(() => {
        const listTransaction = (!typeTransaction.value) ? lastNTransactions.listPurchases : lastNTransactions.listRecurrings;
        return listTransaction;
    });

    const filteredTransactions = computed(() => {
        const listTransaction = (!typeTransaction.value) ? lastNTransactions.listPurchases : lastNTransactions.listRecurrings;
        return listTransaction;
    });

    const textTitle = computed(() => {
        return (!typeTransaction.value) ? 'Derniers achats' : 'Derniers prélèvements';
    });
</script>