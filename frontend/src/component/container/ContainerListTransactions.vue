<template>
    <div class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-black shadow-custom-main">
        <div class="gradient-border text-white overflow-hidden
            bg-main-gradient w-full">     
    
            <div class="flex items-center justify-between">
                <h2 class="pl-3 py-3 text-[20px] font-extralight pr-8">{{ props.title}}</h2>
                <router-link to="/historique-transactions" :class="`font-light cursor-pointer pr-3 ${translateY}`">Voir plus ></router-link>
            </div>
            <div class="flex py-2 pl-3 mt-3 bg-gradient-x-blue shadow-black shadow-custom-main">
                <p class="ml-[2.5vw] pl-[15px] w-[20%]">Catégorie</p>
                <p class="w-[20%]">Montant</p>
                <p class="w-[20%]">Date</p>
                <p class="w-[15%]">Itération</p>
            </div>
            <div>
                <ContainerTransactionInfo 
                    v-for="(transaction, index) of filteredTransactions" 
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
    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));

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

    const isSuccessDelete = ref(false);
    const isSuccessEdit = ref(false);

    // life cycle
    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        updateLastNTrsByMonth(newMonth, newYear, 'purchase');
        updateLastNTrsByMonth(newMonth, newYear, 'recurring');
    }, {  immediate:true, deep:true });

    const filteredTransactions = computed(() => {
        const listTransaction = (props.componentType === 'purchase') ? lastNTransactions.listPurchases : lastNTransactions.listRecurrings;
        return listTransaction;
    });
</script>