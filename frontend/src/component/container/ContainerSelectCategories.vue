<template>
    <div class="w-full py- px-2">
        <div class="flex justify-center pt-[20px] ">
            <ToggleButton class="text-[15px]" v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
        </div>

        <!-- liste des catégories -->
        <h2 class="text-center mt-[40px] text-[18px] font-light">Sélectionner la catégorie</h2>
        <div class="w-full flex flex-wrap pt-[20px] pb-[40px]">
            <SelectInputCategories :width="'w-[70%]'" 
                v-model:currentCategory="currentCategory" 
                :typeTransaction="typeTransaction" 
                :listSelect="(!typeTransaction) ? listPurchases : listRecurings" 
            />
        </div>
    
    </div>
</template>


<script setup>

import ToggleButton from "@/component/button/ToggleButton.vue";
import SelectInputCategories from "@/component/select/SelectInputCategories.vue";
import { listPurchases, listRecurings } from '@/svg/listTransactionSvgs';

// variables, props, ...
const typeTransaction = defineModel('typeTransaction');
const currentCategory = defineModel('currentCategory');

</script>
