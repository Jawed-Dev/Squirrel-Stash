<template>
    <div class="w-full pt-6">
        <div class="flex flex-col items-center">
            <div class="flex justify-center w-[65%]">
                <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
            </div>
    
            <!-- liste des catégories -->
            <h2 class="pl-4 text-center text-[16px]font-light pt-7 w-[65%]">Sélectionner la catégorie</h2>
            <div class="w-full flex justify-center pb-9 pt-1">
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

import ToggleButton from "@/component/button/ToggleButton.vue";
import SelectInputCategories from "@/component/select/SelectInputCategories.vue";
import { listPurchases, listRecurings } from '@/svg/listTransactionSvgs';

// variables, props, ...
const typeTransaction = defineModel('typeTransaction');
const currentCategory = defineModel('currentCategory');

</script>
