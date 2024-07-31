<template>
    <section :class="`hidden lg:flex flex-col items-center justify-center 
        bg-main-bg text-white lg:w-1/2 lg:h-screen ${heightMin} font-main-font`">

        <div class="flex flex-col items-center gap-10 justify-between w-full mt-5">
            <component :is="image" :svg="styleIcon" />
            <p class="w-[350px] font-light">
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aspernatur sit beatae totam quibusdam rerum mollitia eaque minus vel explicabo consequatur incidunt !
            </p>
        </div>
        
    </section>
</template>


<script setup>    
    import { setSvgConfig } from '@/svg/svgConfig';
       
    // props, variables
    const props = defineProps({
        heightMin: {default:'min-h-[600px]'},
        image: {default: ''}
    });

    const styleIcon = setSvgConfig({width:'350px'})
</script>