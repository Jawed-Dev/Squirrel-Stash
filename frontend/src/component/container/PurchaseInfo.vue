<template>
    <div v-if="props.infoTransaction.transaction_id" class="flex items-center border-t py-6 border-gray-700 text-white">
        <IconLoader :nameIcon="props.nameIcon" :svg="svg" :class="`${props.svg.color} rounded-full p-[1.5%] shadow-black shadow-custom-main`"/>
        <p class="w-[20%] pl-[15px] text-[15px]">{{infoTransaction.transaction_category}}</p>
        <p class="w-[20%] text-[15px]">{{ transactionAmount }}</p>
        <p class="w-[20%] text-[15px]">{{infoTransaction.formatted_date}}</p>
        <p class="w-[15%] text-[15px]">{{ transactionCount }}</p>
        <EditDeleteTransaction :infoTransaction="infoTransaction" :componentType="componentType" :indexMenu="props.indexMenu" v-model:currentMenuEditDeleteTrs="currentMenuEditDeleteTrs" />
    </div>
    <div v-if="(!props.infoTransaction.transaction_id)" class="flex items-center border-t py-6 border-gray-700 text-white">
        <IconLoader nameIcon="Invisible" :svg="svg" :class="`${props.svg.color} rounded-full p-[1.5%] shadow-black shadow-custom-main`"/>
        <p class="w-full pl-[15px] text-[15px] text-center font-light">Aucune donnée</p>
    </div>
</template>

<script setup>

    import { computed } from 'vue';
    import IconLoader from '@/composable/useIconLoader.vue';
    import EditDeleteTransaction from '@/component/overlay/EditDeleteTransaction.vue';
    

    

    // variables, props...
    const props = defineProps({
        infoTransaction: { default: {} },
        svg: { default: {} }, 
        indexMenu: { default: 0 },
        componentType: {default: ''},
        nameIcon: {default: ''},
        test: {default:''}
    });


    const currentMenuEditDeleteTrs = defineModel('currentMenuEditDeleteTrs');

    const transactionAmount = computed(() =>{
        return (props.infoTransaction.transaction_id) ? props.infoTransaction.transaction_amount + ' €' : '';
    });
    const transactionCount = computed(() =>{
        return (props.infoTransaction.transaction_id) ? props.infoTransaction.count_transaction + ' x' : '';
    });

</script>