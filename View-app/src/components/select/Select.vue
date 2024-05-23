<template>

    <select class="w-[250px] pl-2 py-2 gradient-border text-white
        rounded-md bg-main-gradient 
        shadow-black shadow-custom-main" 
        name="month" id="month-select"
    >
        <option v-for="(text, index) of listSelect" :key="index" :value="text">{{text}}</option>
    </select>

</template>



<script setup>

    const props = defineProps(['listSelect']);

</script>