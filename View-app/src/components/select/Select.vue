<template>

    <select class="w-[250px] pl-2 py-2 bg-[#38393b] 
        border border-[#38393b] rounded-lg bg-custom-gradient 
        shadow-[#38393b] shadow-custom-lower" 
        name="month" id="month-select"
    >
        <option v-for="(text, index) of listSelect" :key="index" :value="text">{{text}}</option>
    </select>

</template>



<script setup>

    const props = defineProps(['listSelect']);

</script>