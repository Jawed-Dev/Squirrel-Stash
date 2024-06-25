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
    listRecurings: { default: []},
});

// functions
function handleSelectCategory(idCategory) {
    currentCategory.value = idCategory;
}
</script>
