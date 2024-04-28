<template>
    <div class="flex border-b py-7 border-[#38393b] text-white">
        <p class="w-[150px] text-[15px] pr-4">{{infoReccuringPay.name}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoReccuringPay.price}}€</p>
    </div>
</template>

<script setup>
    const props = defineProps(['infoReccuringPay']);
</script>