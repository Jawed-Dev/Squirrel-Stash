<template>
    <div v-if="isLoadedData">
        <div v-if="!isEmptyListTransaction" class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-main relative">
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
                <div class="absolute pl-1 sm:pl-5 left-0 w-full flex py-2 mt-5 bg-gradient-x-blue shadow-main font-light">
                    <div class="pl-1 sm:pl-0 sm:ml-10 w-[40%] sm:w-1/2 flex flex-row">
                        <div class="flex w-full sm:w-1/2 text-[15px] sm:text-base 
                            sm:pl-[20px] overflow-hidden text-ellipsis text-nowrap">
                            <p @click="toggleOrder(ORDER_STATE.CATEGORY)" 
                            :class="`${colorForOrderSelected(ORDER_STATE.CATEGORY)} w-fit cursor-pointer`" 
                            v-html="textCategory + renderStateOrder(ORDER_STATE.CATEGORY)"></p>
                            <p @click="toggleOrder(ORDER_STATE.AMOUNT)" 
                            :class="`${colorForOrderSelected(ORDER_STATE.AMOUNT)} w-fit cursor-pointer flex sm:hidden`" 
                            v-html="textAmount + renderStateOrder(ORDER_STATE.AMOUNT)"></p>
                        </div>
                        <div class="grow text-nowrap text-left overflow-hidden text-ellipsis hidden sm:flex">
                            <p 
                                @click="toggleOrder(ORDER_STATE.AMOUNT)" 
                                :class="`${colorForOrderSelected(ORDER_STATE.AMOUNT)} w-fit cursor-pointer`"  
                                v-html="'Montant' + renderStateOrder(ORDER_STATE.AMOUNT) ">
                            </p>
                        </div>
                    </div>
                    <div class="w-[27%] sm:w-[21%] text-[15px] sm:text-base">
                        <p @click="toggleOrder(ORDER_STATE.DATE)" :class="` ${colorForOrderSelected(ORDER_STATE.DATE)} w-fit cursor-pointer`"  
                        v-html="'Date' + renderStateOrder(ORDER_STATE.DATE)"></p>
                    </div>
                    <div class="text-[15px] sm:text-base grow">
                        <p @click="toggleOrder(ORDER_STATE.ITERATION)" :class="`${colorForOrderSelected(ORDER_STATE.ITERATION)} w-fit cursor-pointer`"  
                        v-html="'Itération' + renderStateOrder(ORDER_STATE.ITERATION)"></p>
                    </div>
                </div>
                
                <div class="mt-[calc(105px-45px)]">
                    <ContainerTransactionInfo v-for="(transaction, index) of filteredTransactions" 
                        :key="transaction.transaction_id" v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" 
                        :indexMenu="index" :infoTransaction="transaction" :lengthData="filteredTransactions.length"
                    />
                </div>
            </div>
        </div>
        <div v-if="isEmptyListTransaction" class="w-full flex justify-center mt-5 xl:mt-10">
            <p class="text-white text-[18px] pb-5">Aucun résultat n'a été trouvé.</p>
        </div>
    </div>
</template>


<script setup>
    // import
    import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
    import ContainerTransactionInfo from '@/components/container/ContainerTransactionInfo.vue';
    import ContainerPagination from '@/components/container/ContainerPagination.vue';
    import { storeDataTrsSearch, storeParamsSearch } from '@/storesPinia/useStoreDashboard';
    import { updateDataTrsSearch } from '@/storesPinia/useUpdateStoreByBackend';
    import useSpinnerLoadData from '@/composables/useSpinnerLoadData';

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
    const totalItems = defineModel('totalItems');
    const currentPage = defineModel('currentPage');
    const itemsPerPage = defineModel('itemsPerPage');
    const emitIsLoadedData = defineModel('isLoadedData');
    const { isLoadedData, timerLoadPageSpinner } = useSpinnerLoadData();

    const currentMenuEditDeleteTrs = ref(-1);
    const props = defineProps({
        title: { default: '' },
    });

    const currentOrderSelected = defineModel('currentOrderSelected');
    const orderAsc = defineModel('orderAsc');

    const width = ref(window.innerWidth);

    // life cycle, function
    onMounted(async () => {
        window.addEventListener('resize', updateWidth);
        emitIsLoadedData.value = false;
        const timeMountedComponent = Date.now();
        const params = paramsSearch.params;
        await updateDataTrsSearch(params);        
        timerLoadPageSpinner(timeMountedComponent);
    });
    
    onUnmounted(() => {
      window.removeEventListener('resize', updateWidth);
    });

    watch(() => isLoadedData.value, (newValue) => {
        emitIsLoadedData.value = newValue;
    }) 

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

    // text category
    const textCategory = computed(() => {
        if(width.value < 640) return 'Catég.'
        return 'Catégorie';
    });

    const textAmount = computed(() => {
        if(width.value < 640) return '/ Mont.'
        return ' / Montant';
    });

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

    const updateWidth = () => width.value = window.innerWidth;
        
</script>