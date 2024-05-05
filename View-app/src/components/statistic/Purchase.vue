<template>
    <div class="flex items-center border-b py-6 border-[#38393b] text-white">
        <component :is="iconComponent" :svg="svg"  :class="`${props.svg.color} rounded-full p-3 shadow-custom-main mr-[20px]`"/>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.name}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.price}}€</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.date}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.iteration}}x</p>
    </div>
</template>

<script setup>
    import { getIconByName } from '@/functions/icons/getIcon';

    import {ref, onMounted} from 'vue';

    const iconComponent = ref(null);

    onMounted(async () => {
        const module = await getIconByName(props.svg.name);
        iconComponent.value = module.default;
    });


    const props = defineProps({
        infoPurchase: { default: {} },
        svg: { default: {} }
    });
    
</script>