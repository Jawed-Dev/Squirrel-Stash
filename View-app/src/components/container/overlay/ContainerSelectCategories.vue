<template>
    <div class="w-full py- px-2">
        <div class="flex justify-center pt-[40px] ">
            <ToggleButton class="text-[15px]" v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
        </div>

        <!-- liste des catégories -->
        <h2 class="text-center pt-[60px] text-[18px] font-extralight">Sélectionnez la catégorie</h2>
        <div class="w-full flex flex-wrap pt-[20px] pb-[60px]">
            <SelectInputCategories :width="'w-[70%]'" v-model:currentCategory="currentCategory" :typeTransaction="typeTransaction" :listSelect="(!typeTransaction) ? listCategories : listRecurings" />
        </div>
    
    </div>
</template>


<script setup>

// imports 

import ToggleButton from "../../button/ToggleButton.vue";
import SelectInputCategories from "@/components/select/SelectInputCategories.vue";

const typeTransaction = defineModel('typeTransaction');
const currentCategory = defineModel('currentCategory');

// variables, props, ...
const props = defineProps({
    listCategories: { default: []},
    listRecurings: { default: []}
});

// functions
function handleSelectCategory(idCategory) {
    currentCategory.value = idCategory;
}
</script>


<!-- <div v-for="(icon, index) in (!typeTransaction ? listCategories : listRecurings)" :key="index" :class="`${translateY}`" >
    <div @click="handleSelectCategory(index)" :class="`w-[120px] mb-[20px] cursor-pointer flex flex-col items-center`">
        <IconLoader :nameIcon="icon.nameIcon" 
        :class="`${icon.color} rounded-full p-[10%] m-4 mr-[20px] shadow-black shadow-custom-main`" :svg="svgLargeBlue"/>
        <p class="text-[15px] font-light text-center text-gray-300">{{ icon.text }}</p>
    </div>
</div> -->