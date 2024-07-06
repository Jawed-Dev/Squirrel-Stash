<template>
    <div v-if="listTransactionStore.length > 0" class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-black shadow-custom-main">
        <div class="gradient-border text-white overflow-hidden
            bg-main-gradient w-full pl-3">     
            <div class="flex items-center relative">
                <h2 class="py-3 text-[20px] font-extralight pr-8">{{ props.title}}</h2>
                <div class="absolute left-1/2 transform -translate-x-1/2 flex gap-1">
                    <div class="text-[15px] bg-main-blue flex justify-center w-[1.3vw] px-[10px] rounded-sm font-medium shadow-black shadow-custom-main cursor-pointer">&#9664;</div>
                    <div class="bg-main-blue flex justify-center w-[1.3vw] px-[10px] rounded-sm font-medium shadow-black shadow-custom-main cursor-pointer">1</div>
                    <div class="bg-main-blue flex justify-center w-[1.3vw] px-[10px] rounded-sm font-medium shadow-black shadow-custom-main cursor-pointer">2</div>
                    <div class="bg-main-blue flex justify-center w-[1.3vw] px-[10px] rounded-sm font-medium shadow-black shadow-custom-main cursor-pointer">...</div>
                    <div class="bg-main-blue flex justify-center w-[1.3vw] px-[10px] rounded-sm font-medium shadow-black shadow-custom-main cursor-pointer">3</div>
                    <div class="text-[15px] bg-main-blue flex justify-center w-[1.3vw] px-[10px] rounded-sm font-medium shadow-black shadow-custom-main cursor-pointer">&#9654;</div>
                </div>
            </div>
            <div class="flex border-gray-700 py-2 pl-3">
                <div class="ml-[2.5vw] pl-[15px] w-[20%]">
                    <p @click="toggleOrder(ORDER_STATE.CATEGORY)" :class="`${colorForOrderSelected(ORDER_STATE.CATEGORY)} w-fit cursor-pointer`" 
                    v-html="renderStateOrder(ORDER_STATE.CATEGORY) + 'Catégorie'"></p>
                </div>
                <div class="w-[20%]">
                    <p @click="toggleOrder(ORDER_STATE.AMOUNT)" :class="`${colorForOrderSelected(ORDER_STATE.AMOUNT)} w-fit cursor-pointer`"  
                    v-html="renderStateOrder(ORDER_STATE.AMOUNT) + 'Montant'"></p>
                </div>
                <div class="w-[20%]">
                    <p @click="toggleOrder(ORDER_STATE.DATE)" :class="`${colorForOrderSelected(ORDER_STATE.DATE)} w-fit cursor-pointer`"  
                    v-html="renderStateOrder(ORDER_STATE.DATE) + 'Date'"></p>
                </div>
                <div class="w-[15%]">
                    <p @click="toggleOrder(ORDER_STATE.ITERATION)" :class="`${colorForOrderSelected(ORDER_STATE.ITERATION)} w-fit cursor-pointer`"  
                    v-html="renderStateOrder(ORDER_STATE.ITERATION) + 'Itération'"></p>
                </div>
            </div>
            <div class="pl-3">
                <ContainerTransactionInfo v-for="(transaction, index) of listTransactionStore" 
                    :key="transaction.transaction_id" v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" 
                    :indexMenu="index" :infoTransaction="transaction"
                />
            </div>
        </div>
    </div>
    <div v-if="!listTransactionStore.length" class="w-full flex justify-center mt-[40px]">
        <p class="text-white text-[18px]">Aucun résultat n'a été trouvé.</p>
    </div>
</template>


<script setup>
    // import
    import { ref, computed } from 'vue';
    import ContainerTransactionInfo from '@/component/container/ContainerTransactionInfo.vue';
    import { storeListTrsSearch } from '@/storePinia/useStoreDashboard';
    
    // Store Pinia
    const listTrsSearch = storeListTrsSearch();

    const ORDER_STATE = {
        DATE: 0,
        AMOUNT: 1,
        CATEGORY: 2,
        ITERATION: 3
    };

    // variables, props...
    const currentMenuEditDeleteTrs = ref(-1);
    const props = defineProps({
        title: { default: '' },
    });

    const currentOrderSelected = defineModel('currentOrderSelected');
    const orderAsc = defineModel('orderAsc');

    // life cycle, function
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
        return orderAsc.value
    }
 
    const listTransactionStore = computed(() => {
        return listTrsSearch.listTransactions?.data ? [...listTrsSearch.listTransactions.data] : [];
    });

    const colorForOrderSelected = (index) => {
        const color = computed(() => {
            return (getCurrentIndexOrder() === index) ? 'text-main-blue' : '';
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