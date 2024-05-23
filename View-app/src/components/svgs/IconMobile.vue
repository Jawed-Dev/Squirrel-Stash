<template>

<svg 
    :width="svg.width" :height="svg.height"
    viewBox="0 0 24 24" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" 
    :fill="svg.fill"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" 
    stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier">
    <defs></defs><rect class="cls-1" x="5.32" y="1.5" width="13.36" height="21" rx="1.91"></rect><path class="cls-1" d="M13.91,2.45H10.09a.94.94,0,0,1-.95-1h5.72A.94.94,0,0,1,13.91,2.45Z"></path><line class="cls-1" x1="11.05" y1="19.64" x2="12.95" y2="19.64">
    </line></g>
</svg>

</template>


<script setup>
    const props = defineProps({
        svg: { default: {} }
    });
</script>


<style>.cls-1{fill:none;stroke:white;stroke-miterlimit:10;stroke-width:1.91px;}</style>