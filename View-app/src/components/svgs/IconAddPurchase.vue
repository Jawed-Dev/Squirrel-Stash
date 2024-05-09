<template>
	<svg 
		:width="svg.width" :height="svg.height" viewBox="0 0 24 24" :fill="svg.fill" xmlns="http://www.w3.org/2000/svg">
		<path d="M6 12H18M12 6V18" :stroke="svg.fill" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>
</template>


<script setup>
    defineProps(['svg']);
</script>


<style>

</style>