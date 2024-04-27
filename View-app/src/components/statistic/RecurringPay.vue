<template>
    <div class="flex  text-white
        border-[#38393b] border-b ">
        <p class="w-[150px] py-5">{{infoReccuringPay.name}}</p>
        <p class="w-[150px] py-5">{{infoReccuringPay.price}}</p>
    </div>
</template>

<script setup>
    const props = defineProps(['infoReccuringPay']);
</script>