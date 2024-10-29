<template>
    <section :class="`hidden lg:flex flex-col items-center justify-center
         text-white bg-main-bg lg:w-1/2 lg:h-screen ${heightMin} font-main`">

        <div class="flex flex-col items-center justify-between w-full">
            <component :is="image" :svg="styleIcon" />
            <p class="text-center px-5 w-full font-light text-[22px]" v-html="text"></p>
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
        image: {default: ''},
        text: {default: ''}
    });

    const styleIcon = setSvgConfig({width:'400px'})
</script>