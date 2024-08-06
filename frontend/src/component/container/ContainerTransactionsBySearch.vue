<template>
    <div v-if="componentLoaded">
        <div v-if="!isEmptyListTransaction" class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-black shadow-custom-main">
            <div class="gradient-border text-white overflow-hidden
                bg-main-gradient w-full">     
                <div class="flex flex-col justify-center relative pt-3">
                    <h2 class="pl-3  pb-2 lg:pb-0 text-xl font-extralight pr-8 ">{{ props.title}}</h2>
                    <div class="flex grow justify-center lg:grow-0 lg:justify-stretch gap-1 lg:absolute lg:left-1/2 transform lg:-translate-x-1/2">
                        <ContainerPagination
                            v-model:currentPage="currentPage"
                            v-model:totalItems="totalItems"
                            :itemsPerPage="itemsPerPage"
                        />
                    </div>
                </div>
                <div class="flex py-2 pl-0 sm:pl-4 md:pl-3 mt-5 bg-gradient-x-blue shadow-black shadow-custom-main">
                    <div class="text-[15px] sm:text-base ml-[20px] sm:ml-[40px] md:pl-[20px] w-[25%] overflow-hidden text-ellipsis">
                        <p @click="toggleOrder(ORDER_STATE.CATEGORY)" 
                        :class="`${colorForOrderSelected(ORDER_STATE.CATEGORY)} w-fit cursor-pointer`" 
                        v-html="'Catégorie' + renderStateOrder(ORDER_STATE.CATEGORY)"></p>
                    </div>
                    <div class="text-[15px] sm:text-base w-[22%] pl-5 sm:pl-0 sm:justify-stretch flex justify-center">
                        <p 
                            @click="toggleOrder(ORDER_STATE.AMOUNT)" 
                            :class="`${colorForOrderSelected(ORDER_STATE.AMOUNT)} w-fit cursor-pointer`"  
                            v-html="'Montant' + renderStateOrder(ORDER_STATE.AMOUNT) ">
                        </p>
                    </div>
                    <div class="text-[15px] sm:text-base w-[22%] sm:justify-stretch flex justify-center">
                        <p @click="toggleOrder(ORDER_STATE.DATE)" :class="` ${colorForOrderSelected(ORDER_STATE.DATE)} w-fit cursor-pointer`"  
                        v-html="'Date' + renderStateOrder(ORDER_STATE.DATE)"></p>
                    </div>
                    <div class="text-[15px] sm:text-base grow sm:justify-stretch flex justify-center">
                        <p @click="toggleOrder(ORDER_STATE.ITERATION)" :class="`${colorForOrderSelected(ORDER_STATE.ITERATION)} w-fit cursor-pointer`"  
                        v-html="'Itération' + renderStateOrder(ORDER_STATE.ITERATION)"></p>
                    </div>
                </div>
                
                <div class="">
                    <ContainerTransactionInfo v-for="(transaction, index) of filteredTransactions" 
                        :key="transaction.transaction_id" v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" 
                        :indexMenu="index" :infoTransaction="transaction" :lengthData="filteredTransactions.length"
                    />
                </div>
            </div>
        </div>
        <div v-if="isEmptyListTransaction" class="w-full flex justify-center mt-[40px]">
            <p class="text-white text-[18px]">Aucun résultat n'a été trouvé.</p>
        </div>
    </div>
</template>


<script setup>
    // import
    import { ref, computed, watch, onMounted, nextTick } from 'vue';
    import ContainerTransactionInfo from '@/component/container/ContainerTransactionInfo.vue';
    import ContainerPagination from '@/component/container/ContainerPagination.vue';
    import { storeDataTrsSearch, storeParamsSearch } from '@/storePinia/useStoreDashboard';
    import { updateDataTrsSearch } from '@/storePinia/useUpdateStoreByBackend';

    // Store Pinia
    const paramsSearch = storeParamsSearch();
    const dataTrsSearch = storeDataTrsSearch();

    const ORDER_STATE = {
        DATE: 0,
        AMOUNT: 1,
        CATEGORY: 2,
        ITERATION: 3
    };

    // variables, props...
    // const totalItems = ref(0);
    // const currentPage = ref(1);
    // const itemsPerPage = ref(1);

    const componentLoaded = ref(false);
    const totalItems = defineModel('totalItems');
    const currentPage = defineModel('currentPage');
    const itemsPerPage = defineModel('itemsPerPage');

    const currentMenuEditDeleteTrs = ref(-1);
    const props = defineProps({
        title: { default: '' },
    });

    const currentOrderSelected = defineModel('currentOrderSelected');
    const orderAsc = defineModel('orderAsc');

    // life cycle, function

    onMounted(async () => {
        const params = paramsSearch.params;
        await updateDataTrsSearch(params);
        componentLoaded.value = true;
    });

    watch( () => currentPage.value, (newPage) => {
        const params = paramsSearch.params;
        params.currentPage = newPage;
        updateDataTrsSearch(params);
    });

    watch(() => dataTrsSearch.dataTransactions?.data, (newData) => {
        if (newData) {
            const params = paramsSearch.params;
            params.currentPage = 1;
            itemsPerPage.value = newData.itemPerPage || 15;
            totalItems.value = newData.countTransactions;
        }
    }, { deep: true });

    const filteredTransactions = computed(() => {
        const transactionsData = dataTrsSearch.dataTransactions?.data?.listTransactions;
        return transactionsData ? [...transactionsData] : [];
    });

    function toggleOrder(indexOrder) {
        if(currentOrderSelected.value !== indexOrder) {
            currentOrderSelected.value = indexOrder;
            orderAsc.value = true;
        }
        else toggleStateOrder();
    }

    function toggleStateOrder() {
        orderAsc.value = !orderAsc.value;
    }

    function getCurrentIndexOrder() {
        return currentOrderSelected.value;
    }

    function getOrderState() {
        return orderAsc.value;
    }

    const isEmptyListTransaction = computed(() => {
        return filteredTransactions.value.length <= 0;
    });

    const colorForOrderSelected = (index) => {
        const color = computed(() => {
            return (getCurrentIndexOrder() === index) ? 'text-main-bg' : '';
        });
        return color.value;
    };

    const renderStateOrder = (index) => {
        const orderIndicator = computed(() => {
            if (getCurrentIndexOrder() === index) {
                return (!getOrderState()) ? "&#9660;" : "&#9650;";
            }
            return "&#9660;";
        });
        return orderIndicator.value;
    };
        
</script>