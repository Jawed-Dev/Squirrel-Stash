<template>
    <div class="font-main-font flex bg-main-bg min-h-screen w-full">
        <ContainerHeader/>
        <div class="ml-[calc(20px+70px+20px)] mr-custom-margin-main w-full flex flex-col mt-[20px]">
            <h1 class="text-[22px] text-center text-white">Listes des transactions</h1>
            <ContainerSearch 
                :currentOrderSelected="currentOrderSelected"
                :orderAsc="orderAsc"
            />
            <ContainerTransactionsBySearch title="Historique des transactions"
            v-model:orderAsc="orderAsc"
            v-model:currentOrderSelected="currentOrderSelected" 
             />
        </div>
    </div>
</template>


<script setup>

    // imports
    import { ref, watch } from 'vue';
    import ContainerHeader from '@/component/container/ContainerHeader.vue';
    import ContainerTransactionsBySearch from "@/component/container/ContainerTransactionsBySearch.vue";
    import ContainerSearch from '@/component/container/ContainerSearch.vue';
    import { storeParamsSearch } from '@/storePinia/useStoreDashboard';
    import { updateListTrsBySearch } from '@/storePinia/useUpdateStoreByBackend';

    const paramsSearch = storeParamsSearch();

    // variables, props...
    const ORDER_STATE_DATE = 0;
    const currentOrderSelected = ref(ORDER_STATE_DATE);
    const orderAsc = ref(false);

    // life cycle / functions
    watch([currentOrderSelected, orderAsc], ([newOrder, newAsc], [oldOrder, oldAsc]) => {
        const params = paramsSearch.params;
        paramsSearch.params.currentOrderSelected = newOrder;
        paramsSearch.params.orderAsc = newAsc;
        //alert(newAsc);
        updateListTrsBySearch(params);
    });

    

    // functions

</script>