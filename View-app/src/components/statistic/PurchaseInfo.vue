<template>
    <div class="flex items-center border-t py-6 border-gray-700 text-white">
        <IconLoader :nameIcon="props.nameIcon" :svg="svg" :class="`${props.svg.color} rounded-full p-[1.5%] shadow-black shadow-custom-main`"/>
        <p class="w-[20%] pl-[15px] text-[15px]">{{infoTransaction.transaction_name}}</p>
        <p class="w-[20%] text-[15px]">{{infoTransaction.transaction_amount}}€</p>
        <p class="w-[20%] text-[15px]">{{infoTransaction.formatted_date}}</p>
        <p class="w-[15%] text-[15px]">{{infoTransaction?.count_transaction}}x</p>
        <EditDelete :infoTransaction="infoTransaction" :transactionType="transactionType" :indexMenu="props.indexMenu" v-model:currentTrsIndexSelect="currentTrsIndexSelect" />
    </div>
</template>

<script setup>

    // import
    import { ref } from 'vue';
    import IconLoader from '@/composable/useIconLoader.vue';
    import EditDelete from '@/components/overlay/EditDelete.vue';
    
    // variables, props, emit
    const props = defineProps({
        infoTransaction: { default: {} },
        svg: { default: {} }, 
        indexMenu: { default: 0 },
        transactionType: {default: ''},
        nameIcon: {default: ''}
    });

    const currentTrsIndexSelect = defineModel('currentTrsIndexSelect');


</script>