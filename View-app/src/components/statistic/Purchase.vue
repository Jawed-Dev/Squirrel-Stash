<template>
    <div class="flex border-b py-7 border-[#38393b] text-white">
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.name}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.price}}€</p>
        <p class="w-[200px] text-[15px] pr-4">{{infoPurchase.date}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.iteration}}x</p>
    </div>
</template>

<script setup>
    const props = defineProps(['infoPurchase']);
</script>