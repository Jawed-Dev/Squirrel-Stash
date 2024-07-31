<template>
    <div class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-black shadow-custom-main">
        <div class="gradient-border text-white
            bg-main-gradient w-full"> 
    
            <div class="flex items-center justify-between">
                <h2 class="pl-3 pb-2 pt-3 text-xl font-extralight pr-8 sm:pb-0">{{ textTitle }}</h2>
                <router-link to="/historique-transactions" :class="`font-light cursor-pointer pr-3 ${translateY}`">Voir plus ></router-link>
            </div>

            <div class="w-full flex justify-center">
                <div class="w-1/6 min-w-[250px]">
                    <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
                </div>
            </div>

            <div class="flex py-2 pl-[2px] sm:pl-3 mt-3 bg-gradient-x-blue shadow-black shadow-custom-main">
                <p class="ml-[40px] pl-[5px] md:pl-[15px] w-[25%] overflow-hidden text-ellipsis">Catégorie</p>
                <p class="w-[20%] sm:text-left text-center">Montant</p>
                <p class="w-[25%] sm:text-left text-center">Date</p>
                <p class="grow sm:text-left text-center">Itération</p>
            </div>
            <div>
                <ContainerTransactionInfo 
                    v-for="(transaction, index) of listTransactions" 
                    :key="transaction.transaction_id"
                    v-model:isSuccessEdit="isSuccessEdit"
                    v-model:isSuccessDelete="isSuccessDelete"
                    v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" 
                    :indexMenu="index" 
                    :infoTransaction="transaction"
                    :lengthData="filteredTransactions.length"
                />
            </div>
        </div>

        <TransitionPopUp duration-in="500" duration-out="500">
            <OverlaySuccessAction text="Votre transaction a été éditée." v-if="isSuccessEdit" v-model:overlayActive="isSuccessEdit" />
            <OverlaySuccessAction text="Votre transaction a été supprimée." v-if="isSuccessDelete" v-model:overlayActive="isSuccessDelete" />
        </TransitionPopUp>
    </div>
</template>


<script setup>
    // import
    import { ref, watch, computed, watchEffect, defineAsyncComponent } from 'vue';
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

    const isSuccessDelete = ref(false);
    const isSuccessEdit = ref(false);
    const typeTransaction = ref(false);

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