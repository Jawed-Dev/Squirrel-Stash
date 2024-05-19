<template>
    <!-- <IconDynamic :class="`rounded-full p-3 m-4 shadow-custom-main mr-[20px]`" :svg="svgSmall"/> -->
    <IconPreferences @click="toggleMenu('options')" class="cursor-pointer flex end" :svg="svgSmall" />

    <TransitionOpacity>
        <div v-show="ismenuAddActive" 
        class="fixed right-[550px] bg-main-gradient rounded-md overflow-hidden
            shadow-black shadow-custom-test trigger-class-options">
            <p class="hover:bg-custom-blue w-[100%] p-2 cursor-pointer">option 1</p>
            <p class="hover:bg-custom-blue w-[100%] p-2 cursor-pointer">option 2</p>
            <p class="hover:bg-custom-blue w-[100%] p-2 cursor-pointer">option 3</p>
            <p class="hover:bg-custom-blue w-[100%] p-2 cursor-pointer">option 4</p>
        </div>
    </TransitionOpacity>
    
</template>

<script setup>

    // import
    import {ref, onMounted, onBeforeUnmount} from 'vue';
    import { svgConfig } from '../../functions/svg/svgConfig.js'
    import IconDynamic from '../icons/nav/IconDynamic.vue';
    import IconPreferences from '../svgs/IconPreferences.vue';
    import TransitionOpacity from '../transition/TransitionOpacity.vue';

    // variables, props ...
    const svgSmall = svgConfig.setColorDynamic(svgConfig.verySmallIcon, 'bg-gradient-blue');
    const ismenuAddActive = ref(false); 

    function toggleMenu(request) {
        switch(request) {
            case 'options' : {
                ismenuAddActive.value = !ismenuAddActive.value;
                break;
            }
        }
    }
    // cycle de vie
    onMounted(() => {
        document.addEventListener('click', handleClickOutside, true);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('click', handleClickOutside, true);
    });

    // function
    function handleClickOutside(event) {
        if (!event.target.closest('.trigger-class-options')) ismenuAddActive.value = false;
    }



</script>