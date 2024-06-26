<template>
	<svg
		@click="toggleMenu" :width="svg.width" :height="svg.height" viewBox="0 0 24 24" 
		:fill="svg.fill" xmlns="http://www.w3.org/2000/svg">
		<path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm-6 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm12 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" :fill="svg.fill"/>
	</svg>
</template>


<script setup>
    const props = defineProps({
        svg: { default: {} }
    });
</script>


<style>

</style>