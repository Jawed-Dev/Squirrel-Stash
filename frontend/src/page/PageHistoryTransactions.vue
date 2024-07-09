<template>
    <div class="font-main-font flex bg-main-bg min-h-screen w-full">
        <div class="ml-[calc(20px+70px+20px)] mr-custom-margin-main w-full flex flex-col mt-[20px]">
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