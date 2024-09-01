<template>
    <div class="font-main flex flex-col bg-main-bg w-full min-h-screen pb-[calc(50px)] md:pb-0">
        <div v-show="isDataLoaded" class="mx-1 md:ml-[calc(20px+65px+20px)] xl:ml-[calc(30px+75px+30px)] md:mr-[20px] xl:mr-[30px] flex flex-col mt-5">
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
                v-model:isLoadedData="isDataLoaded"
             />
        </div>
        <div v-if="!isDataLoaded">
            <ContainerSpinner />
        </div>
    </div>
</template>


<script setup>

    // imports
    import { ref, watch } from 'vue';
    import ContainerTransactionsBySearch from "@/components/container/ContainerTransactionsBySearch.vue";
    import ContainerSearch from '@/components/container/ContainerSearch.vue';
    import { storeParamsSearch } from '@/storesPinia/useStoreDashboard';
    import { updateDataTrsSearch } from '@/storesPinia/useUpdateStoreByBackend';
    import ContainerSpinner from '@/components/container/ContainerSpinner.vue';

    const paramsSearch = storeParamsSearch();

    // variables, props...
    const isDataLoaded = ref(false);
    const ORDER_STATE_DATE = 0;
    const currentOrderSelected = ref(ORDER_STATE_DATE);
    const orderAsc = ref(false);

    const totalItems = ref(0);
    const currentPage = ref(1);
    const itemsPerPage = ref(1);
    

    // life cycle / functions
    watch([currentOrderSelected, orderAsc], async ([newOrder, newAsc]) => {
        const params = paramsSearch.params;
        currentPage.value = 1;
        paramsSearch.params.currentPage = 1;
        paramsSearch.params.currentOrderSelected = newOrder;
        paramsSearch.params.orderAsc = newAsc;
        updateDataTrsSearch(params);
    });

  
</script>