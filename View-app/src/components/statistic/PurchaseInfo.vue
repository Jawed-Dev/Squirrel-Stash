<template>
    <div class="flex items-center border-t py-6 border-[#38393b] text-white">
        <IconDynamic :nameIcon="props.nameIcon"  :svg="svg" :class="`${props.svg.color} rounded-full p-3 shadow-black shadow-custom-main mr-[20px]`"/>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.name}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.price}}€</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.date}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.iteration}}x</p>
        

        <EditDelete :indexMenu="props.indexMenu" v-model:currentMenu="modelCurrentMenu" />
    </div>
</template>

<script setup>

    // import
    import { ref } from 'vue';
    import IconDynamic from '../icons/nav/IconDynamic.vue';
    import EditDelete from '../overlay/EditDelete.vue';
    

    // variables, props, emit
    const props = defineProps({
        infoPurchase: { default: {} },
        svg: { default: {} }, 
        indexMenu: { default: 0 },
        purchaseType: {default: 'standard'},
        nameIcon: {default: ''}
    });

    const modelCurrentMenu = defineModel('currentMenu');


</script>