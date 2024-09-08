<template>
    <div class="rounded-[3px] overflow-hidden my-5 shadow-main relative">
        <div class="bg-main-gradient w-full gradient-border text-white"> 
    
            <div class="flex items-center justify-between">
                <h2 class="pl-3 pb-2 pt-3 text-xl font-extralight pr-8 sm:pb-0">{{ textTitle }}</h2>
                <router-link 
                    to="/historique-transactions" 
                    :class="`pb-2 pt-3 text-lg font-extralight pr-2 sm:pb-0 hover:text-blue-500`">Voir plus >
                </router-link>
            </div>

            <div class="flex w-full 2xl:hidden justify-center ">
                <div class="w-1/6 min-w-[250px]">
                    <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
                </div>
            </div>

            <!-- category -->
            <div class="absolute pl-1 sm:pl-5 left-0 w-full flex py-2 mt-5 bg-gradient-x-blue shadow-main font-light">
                <div class="pl-1 sm:pl-0 sm:ml-10 w-[40%] sm:w-1/2 flex flex-row">
                    <!-- category -->
                    <p class="w-full sm:w-1/2 text-[15px] sm:text-base 
                    sm:pl-[20px] overflow-hidden text-ellipsis text-nowrap">{{textCategory}}</p>
                    <!-- Amount -->
                    <p class="grow text-nowrap text-left overflow-hidden text-ellipsis hidden sm:flex">Montant</p>
                </div>
                <!-- Date -->
                <p class="w-[27%] pl-5 sm:pl-0 sm:w-[21%] text-[15px] sm:text-base">Date</p>
                <!-- Iteration -->
                <p class="text-[15px] sm:text-base grow">Itération</p>
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
    import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
    import ContainerTransactionInfo from '@/components/container/ContainerTransactionInfo.vue';
    import { storeLastNTransactions, storeDateSelected } from '@/storesPinia/useStoreDashboard';
    import { updateLastNTrsByMonth } from '@/storesPinia/useUpdateStoreByBackend';
    import ToggleButton from '@/components/button/ToggleButton.vue';

    // stores Pinia
    const lastNTransactions = storeLastNTransactions();
    const dateSelected = storeDateSelected();

    // variables, props...
    const currentMenuEditDeleteTrs = ref(-1);
    const typeTransaction = defineModel();
    
    const width = ref(window.innerWidth);
    const isBreakPointSM = computed(() => width.value < 640);

    // life cycle
    onMounted(() => {
      window.addEventListener('resize', updateWidth);
    });
    onUnmounted(() => {
      window.removeEventListener('resize', updateWidth);
    });

    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        updateLastNTrsByMonth(newMonth, newYear, 'purchase');
        updateLastNTrsByMonth(newMonth, newYear, 'recurring');
    }, {  immediate:true, deep:true });

    // text category
    const textCategory = computed(() => {
        if(width.value < 430) return 'Catég. / Montant'
        return (isBreakPointSM.value) ? 'Catégorie / Montant' : 'Catégorie';
    });

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

    const updateWidth = () => width.value = window.innerWidth;
    
</script>