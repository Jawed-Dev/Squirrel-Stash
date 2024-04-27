<template>
    <div class="flex border-b border-[#38393b] text-white ">
        <p class="w-[150px] py-5">{{infoPurchase.name}}</p>
        <p class="w-[150px] py-5">{{infoPurchase.price}}€</p>
        <p class="w-[200px] py-5">{{infoPurchase.date}}</p>
        <p class="w-[150px] py-5">{{infoPurchase.iteration}}x</p>
    </div>
</template>

<script setup>
    const props = defineProps(['infoPurchase']);
</script>