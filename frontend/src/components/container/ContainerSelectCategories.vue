<template>
    <div class="w-full mt-5">
        <div class="flex flex-col items-center">
            <div class="flex justify-center w-[60%] min-[550px]:w-1/2 min-w-[270px]">
                <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
            </div>
    
            <!-- liste des catégories -->
            <h2 class="pl-4 text-center text-[16px] font-light mt-10 w-[65%]">Sélectionner la catégorie</h2>
            <div class="w-full flex justify-center pb-10 pt-1">
                <SelectInputCategories 
                    v-model:currentCategory="currentCategory" 
                    :typeTransaction="typeTransaction" 
                    :listSelect="(!typeTransaction) ? listPurchases : listRecurings" 
                />
            </div>
        </div>
    </div>
</template>

<script setup>

import ToggleButton from "@/components/button/ToggleButton.vue";
import SelectInputCategories from "@/components/select/SelectInputCategories.vue";
import { listPurchases, listRecurings } from '@/svg/listTransactionSvgs';

// variables, props, ...
const typeTransaction = defineModel('typeTransaction');
const currentCategory = defineModel('currentCategory');

</script>
