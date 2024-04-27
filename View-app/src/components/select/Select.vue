<template>

    <select class="w-[250px] py-2 bg-[#38393b] 
        border border-[#38393b] rounded-lg bg-[linear-gradient(to_top_left,_#101010_80%,_#ffff_250%)] 
        shadow-[#38393b] shadow-[0_0_10px_#38393b]" 
        name="month" id="month-select"
    >
        <option v-for="(text, index) of listSelect" :key="index" :value="text">{{text}}</option>
    </select>

</template>



<script setup>

    const props = defineProps(['listSelect']);

</script>