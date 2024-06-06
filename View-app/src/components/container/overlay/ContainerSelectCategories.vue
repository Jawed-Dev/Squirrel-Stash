<template>
    <div class="w-full py-4 px-2">
        <div class="flex justify-between">
            <ToggleButton class="text-[14px]" v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
        </div>

        <!-- liste des catégories -->

        <h2 class="text-center pt-[25px] text-[20px] font-extralight">Sélectionnez la catégorie</h2>
        <div class="w-[600px] flex flex-wrap pt-[20px] ">
            <div v-for="(icon, index) in (!typeTransaction ? listCategories : listRecurings)" :key="index" :class="`${translateY}`" >
                <div @click="handleSelectCategory(index)" :class="`w-[120px] mb-[20px] cursor-pointer flex flex-col items-center`">
                    <IconLoader :nameIcon="icon.nameIcon" 
                    :class="`${icon.color} rounded-full p-[10%] m-4 mr-[20px] shadow-black shadow-custom-main`" :svg="svgLargeBlue"/>
                    <p class="text-[15px] font-light text-center text-gray-300">{{ icon.text }}</p>
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
const svgLargeBlue = svgConfig.setColorDynamic(svgConfig.mediumHigher, 'bg-gradient-blue');


// functions
function handleSelectCategory(idCategory) {
    currentCategory.value = idCategory;
}
</script>