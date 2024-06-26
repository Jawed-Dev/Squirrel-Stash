<template>
    <div class="w-full py- px-2">
        <div class="flex justify-center pt-[40px] ">
            <ToggleButton class="text-[15px]" v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
        </div>

        <!-- liste des catégories -->
        <h2 class="text-center pt-[60px] text-[18px] font-extralight">Sélectionnez la catégorie</h2>
        <div class="w-full flex flex-wrap pt-[20px] pb-[60px]">
            <SelectInputCategories :width="'w-[70%]'" 
                v-model:currentCategory="currentCategory" 
                :typeTransaction="typeTransaction" 
                :listSelect="(!typeTransaction) ? listCategories : listRecurings" 
            />
        </div>
    
    </div>
</template>


<script setup>

import ToggleButton from "@/component/button/ToggleButton.vue";
import SelectInputCategories from "@/component/select/SelectInputCategories.vue";
import { listCategories, listRecurings } from '@/svg/listTransactionSvgs';

// variables, props, ...
const typeTransaction = defineModel('typeTransaction');
const currentCategory = defineModel('currentCategory');

</script>
