<template>
    <div class="flex flex-col gap-2 w-[30%]">
        <div class="flex gap-3">
            <InputCheckbox 
            v-model="checkboxCategory" 
            width="w-fit"
            extraClass="border-b-2 font-light" 
            placeholder="search"
            id="checkbox-date"
            type="checkbox"/>
            <label class="text-gray-300  font-light" for="checkbox-date">Recherche par Catégorie</label>
        </div>
        <div :class="{ 'opacity-50': !checkboxCategory }">
            <div class="flex gap-[10%] ">
                <div class="flex flex-col">
                    <label class="text-white font-light" for="input-category">Catégorie</label>
                    <SelectInput 
                        v-model="searchCategory" 
                        :listSelect="filterListTransactions"
                        :disabled="!checkboxCategory"
                        class="py-1 mt-[10px] text-[18px]"
                    />
                </div>
                <div class="flex flex-col">
                    <p class="text-white font-light" for="input-trs-type">Type de transaction</p>
                    <div>
                        <SelectInput 
                            v-model="searchType" 
                            :listSelect="['Achat', 'Prélèvement']"
                            :disabled="!checkboxCategory"
                            class="py-1 mt-[10px] text-[18px]"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import InputCheckbox from '@/component/input/InputCheckbox.vue';
    import SelectInput from '@/component/select/SelectInput.vue';
    import { listCategories, listRecurings } from '@/svg/listTransactionSvgs';


    // props, variables...
    const checkboxCategory = defineModel('checkboxCategory');
    const searchCategory = defineModel('searchCategory');
    const searchType = defineModel('searchType');

    

    const simpleCategories = listCategories.map(item => item.nameIcon);
    const simpleRecurings = listRecurings.map(item => item.nameIcon);

    const filterListTransactions = computed(() => {
        return (getNametrsType() === "Achat") ? simpleCategories : simpleRecurings;
    });

    function getNametrsType() {
        return searchType.value;
    }
</script>