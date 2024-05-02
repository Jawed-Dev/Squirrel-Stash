<template>
    <div class="flex items-center border-b py-6 border-[#38393b] text-white">
        <IconRestaurant :svg="svg"  class="bg-header-gradient2 rounded-full p-3 shadow-custom-main mr-[20px]"/>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.name}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.price}}€</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.date}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.iteration}}x</p>
    </div>
</template>

<script setup>
    import IconRestaurant from '../svgs/iconRestaurant.vue';

    const props = defineProps(['infoPurchase']);

    const svg = {
        fill: 'white',
        width: '50px',
    }
    
</script>