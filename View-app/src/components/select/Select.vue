<template>

    <select class="w-[250px] pl-2 py-2 bg-[#38393b] text-white
        rounded-lg bg-main-gradient 
        shadow-custom-gray-dark shadow-custom-main" 
        name="month" id="month-select"
    >
        <option v-for="(text, index) of listSelect" :key="index" :value="text">{{text}}</option>
    </select>

</template>



<script setup>

    const props = defineProps(['listSelect']);

</script>