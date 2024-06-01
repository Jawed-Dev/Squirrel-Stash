<template>
    <div :class="`rounded-t-[3px] shadow-black shadow-custom-main bg-gradient-joomla ${paddingY} px-[150px]`">
        <h2 :class="`${width} text-center text-[22px] pt-3 py-2 font-extralight`">{{titleContainer}}</h2>
        <div v-if="props.isIconActive">
            <div class="flex gap-[15px] items-center justify-center"> 
                <p class="text-[18px] font-medium  py-5">{{ currentList.text }}</p>
                <IconLoader :class="`shadow-black shadow-custom-main p-2 rounded-full 
                ${currentList.color}`"
                :svg="svgMediumSBlue" 
                :nameIcon="currentList.nameIcon"/>
            </div>
        </div>
    </div>

    <!-- contenu du slot  -->
    <slot> </slot>
    <!-- -->

    <div>
        <div class="flex shadow-black shadow-custom-main">
            <div class="w-[50%]">
                <button @click="$emit('toggleMenu','cancel')" class="w-full bg-gradient-namn h-full py-3 rounded-bl-[3px]">{{textBtn1}}</button>
            </div>
            <div class="w-[50%]">
                <button @click="$emit('toggleMenu','valid')" class="w-full bg-gradient-blue h-full py-3 rounded-br-[3px]">{{textBtn2}}</button>
            </div>
        </div>
    </div>
</template>

<script setup>
    // imports
    import { svgConfig } from '@/functions/svg/svgConfig';
    import IconLoader from '../../composables/useIconLoader.vue';
    
    // variables, props, ...
    const emits = defineEmits(['toggleMenu']);
    const props = defineProps({
        toggleMenu: Function,
        titleContainer: { default: ''},
        textBtn1: { default: ''},
        textBtn2: { default: ''},
        typeTransaction: {default: false},
        currentList: { default: [] },
        isIconActive: {default: false},
        width: {default: '250px'},
        paddingY: {default: 'py-[35px]'}
    });
    const svgMediumSBlue = svgConfig.setColorDynamic(svgConfig.mediumSmaller, 'bg-gradient-blue');
</script>