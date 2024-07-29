<template>
    <div class="font-main-font flex flex-col bg-main-bg w-full min-h-screen pb-[calc(50px)] md:pb-0">
        <div class="mx-1 md:ml-[calc(20px+65px+20px)] md:mr-custom-margin-main flex flex-col mt-[20px]">
            <h1 class="font-light flex justify-start text-[25px] text-white">Historique des transactions</h1>
            
            <ContainerSearch 
                :currentOrderSelected="currentOrderSelected"
                :orderAsc="orderAsc"
            />
   
            <ContainerTransactionsBySearch title="Historique des transactions"
                v-model:orderAsc="orderAsc"
                v-model:currentOrderSelected="currentOrderSelected" 
                v-model:totalItems="totalItems"
                v-model:currentPage="currentPage"
                v-model:itemsPerPage="itemsPerPage"
             />
        </div>
    </div>
</template>


<script setup>

    // imports
    import { ref, watch } from 'vue';
    import ContainerTransactionsBySearch from "@/component/container/ContainerTransactionsBySearch.vue";
    import ContainerSearch from '@/component/container/ContainerSearch.vue';
    import { storeParamsSearch } from '@/storePinia/useStoreDashboard';
    import { updateDataTrsSearch } from '@/storePinia/useUpdateStoreByBackend';

    const paramsSearch = storeParamsSearch();

    // variables, props...
    const ORDER_STATE_DATE = 0;
    const currentOrderSelected = ref(ORDER_STATE_DATE);
    const orderAsc = ref(false);

    const totalItems = ref(0);
    const currentPage = ref(1);
    const itemsPerPage = ref(1);

    // life cycle / functions
    watch([currentOrderSelected, orderAsc], ([newOrder, newAsc]) => {
        const params = paramsSearch.params;
        currentPage.value = 1;
        paramsSearch.params.currentPage = 1;
        paramsSearch.params.currentOrderSelected = newOrder;
        paramsSearch.params.orderAsc = newAsc;
        updateDataTrsSearch(params);
    });

</script>