<template>
    <div class="w-full py-8">

        <div class="flex flex-col items-center">
            <div class="w-full flex justify-center">
                <ToggleButton v-model:typeTransaction="typeTransaction" :text1="'Achats'" :text2="'Prélèvements'" />
            </div>
    
            <!-- liste des catégories -->
            <h2 class="text-center text-lg font-light pt-8">Sélectionner la catégorie</h2>
 
            <div class="w-full flex justify-center">
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
