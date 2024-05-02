<template>
    <div class="flex items-center border-b py-7 border-[#38393b] text-white">
        <IconRestaurant :svg="svg"  class="bg-header-gradient2 rounded-full p-3 shadow-custom-main mr-[20px]"/>
        <p class="w-[150px] text-[15px] pr-4">{{infoReccuringPay.name}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoReccuringPay.price}}€</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoReccuringPay.date}}</p>
    </div>
</template>

<script setup>
    import IconRestaurant from '../svgs/iconRestaurant.vue';
    const props = defineProps(['infoReccuringPay']);

    const svg = {
        fill: 'white',
        width: '50px',
    }

</script>