<template>
	<svg 
		:width="svg.width" :height="svg.height" viewBox="0 0 24 24" :fill="svg.fill" xmlns="http://www.w3.org/2000/svg">
		<path d="" :stroke="svg.fill"/>
	</svg>
</template>


<script setup>
    const props = defineProps({
        svg: { default: {} }
    });
</script>
