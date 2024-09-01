<template>
    <svg xmlns="http://www.w3.org/2000/svg" :width="svg.width" :height="svg.height" viewBox="0 0 24 24" fill="none" :stroke="svg.fill" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M18 6l-12 12" />
        <path d="M6 6l12 12" />
    </svg>
</template>
    
    
<script setup>
    const props = defineProps({
        svg: { default: {} }
    });
</script>
    
    