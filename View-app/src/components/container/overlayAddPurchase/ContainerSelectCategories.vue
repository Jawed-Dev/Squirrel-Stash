<template>
    <div class="w-full py-5 px-2">
        <div class="flex justify-between">
            <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achat'" :text2="'Prélèvement'" />
        </div>

        <!-- liste des catégories -->

        <h2 class="text-center pt-10 text-[20px]">Sélectionnez la catégorie</h2>
        <div class="w-[600px] flex flex-wrap py-5">
            <div v-for="(icon, index) in (!typeTransaction ? listCategories : listRecurings)" :key="index" :class="`flex flex-col items-center w-[150px] ${translateY} will-change-transform`" >
                <div @click="handleSelectCategory(index)" :class="`mb-3 cursor-pointer py-3`">
                    <IconLoader :nameIcon="icon.nameIcon" 
                    :class="`${icon.color} rounded-full p-[17px] m-4 mr-[20px] shadow-black shadow-custom-main`" :svg="svgLargeBlue"/>
                    <p class=" text-[14px] flex justify-center">{{ icon.text }}</p>
                </div>
            </div>
        </div>
    
    </div>
</template>


<script setup>

// imports 
import { classTransitionHover } from "@/functions/classTransitionHover";
import ToggleButton from "../../button/ToggleButton.vue";
import { svgConfig } from "@/functions/svg/svgConfig";
import IconLoader from "@/composables/useIconLoader.vue";


const typeTransaction = defineModel('typeTransaction');
const currentCategory = defineModel('currentCategory');

// variables, props, ...
const props = defineProps({
    listCategories: { default: []},
    listRecurings: { default: []}
});

const translateY = classTransitionHover('translateY');
const svgLargeBlue = svgConfig.setColorDynamic(svgConfig.largeIcon, 'bg-gradient-blue');


// functions
function handleSelectCategory(idCategory) {
    currentCategory.value = idCategory;
}
</script>