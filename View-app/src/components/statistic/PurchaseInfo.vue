<template>
    <div class="flex items-center border-t py-6 border-gray-700 text-white">
        <IconLoader :nameIcon="props.nameIcon"  :svg="svg" :class="`${props.svg.color} rounded-full p-[1.5%] shadow-black shadow-custom-main mr-[60px]`"/>
        <p class="w-[20%] text-[15px]">{{infoPurchase.transaction_name}}</p>
        <p class="w-[20%] text-[15px]">{{infoPurchase.transaction_amount}}€</p>
        <p class="w-[20%] text-[15px]">{{infoPurchase.formatted_date}}</p>
        <p class="w-[20%] text-[15px]">{{infoPurchase?.count_transaction}}x</p>
        <EditDelete :indexMenu="props.indexMenu" v-model:currentMenu="currentMenu" />
    </div>
</template>

<script setup>

    // import
    import { ref } from 'vue';
    import IconLoader from '@/composable/useIconLoader.vue';
    import EditDelete from '../overlay/EditDelete.vue';
    
    // variables, props, emit
    const props = defineProps({
        infoPurchase: { default: {} },
        svg: { default: {} }, 
        indexMenu: { default: 0 },
        purchaseType: {default: 'standard'},
        nameIcon: {default: ''}
    });

    const currentMenu = defineModel('currentMenu');


</script>