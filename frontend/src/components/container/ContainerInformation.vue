<template>
    <section :class="`hidden lg:flex flex-col items-center justify-center
         text-white bg-main-bg lg:w-1/2 lg:h-screen ${heightMin} font-main`">

        <div class="flex flex-col items-center gap-10 justify-between w-full mt-5">
            <component :is="image" :svg="styleIcon" />
            <p class="w-[350px] font-light">
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aspernatur sit beatae totam quibusdam rerum mollitia eaque minus vel explicabo consequatur incidunt !
            </p>
        </div>

        <!-- <div class="absolute h-screen ml-[100%] w-full bg-[url('/layered-waves-haikei.svg')] bg-cover bg-le bg-no-repeat opacity-100 z-50">


        </div> -->
        
    </section>
    
</template>


<script setup>    
    import { setSvgConfig } from '@/svgUtils/svgConfig';
       
    // props, variables
    const props = defineProps({
        heightMin: {default:'min-h-[600px]'},
        image: {default: ''}
    });

    const styleIcon = setSvgConfig({width:'350px'})
</script>