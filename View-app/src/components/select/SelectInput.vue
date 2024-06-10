<template>
    <select class="w-[250px] pl-2 gradient-border text-white
    rounded-[3px] bg-main-gradient
    shadow-black shadow-custom-main outline-none font-light" 
    name="month" id="month-select"
    >
    <option class="bg-main-bg font-light" v-for="(text, index) of listSelect" :key="index" :value="text">{{text}}</option>
    </select>

</template>



<script setup>

    const props = defineProps({
        listSelect: { default: [] }
    });

</script>