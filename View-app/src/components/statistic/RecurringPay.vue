<template>
    <div class="flex items-center border-b py-6 border-[#38393b] text-white">
        <component :is="iconComponent" :svg="svg" :class="`${props.svg.color} rounded-full p-3 shadow-custom-main mr-[20px]`"/>
        <p class="w-[150px] text-[15px] pr-4">{{infoReccuringPay.name}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoReccuringPay.price}}€</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoReccuringPay.date}}</p>
    </div>
</template>

<script setup>
    import { getIconByName } from '@/functions/icons/getIcon';
    import {ref, onMounted} from 'vue';

    const iconComponent = ref(null)

    onMounted(async () => {
        const module = await getIconByName(props.svg.name);
        iconComponent.value = module.default;
    });


    const props = defineProps({
        infoReccuringPay: { default: {}},
        svg: {default: {}}
    });

    const svg = {
        fill: 'white',
        width: '50px',
    }

</script>