<template>
    <div class="flex justify-end flex-col w-[20%]">
        <div class="flex flex-col w-full">
            <label class="text-white text-[18px] font-light" for="input-category">Catégorie</label>
            <InputBase 
                unicode="🔍"
                v-model="searchCategory" 
                placeholder="Ex : Restaurant"
                id="input-category"
                type="text"
                :hideAnimation="true"
            />
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import InputBase from '@/component/input/InputBase.vue';
    import { listPurchases, listRecurings } from '@/svg/listTransactionSvgs';

    // props, variables...
    const checkboxCategory = defineModel('checkboxCategory');
    const searchCategory = defineModel('searchCategory');
    const searchType = defineModel('searchType');

    const simpleCategories = listPurchases.map(item => item.text);
    const simpleRecurings = listRecurings.map(item => item.text);

    const filterListTransactions = computed(() => {
        return (getNametrsType() === "Achat") ? simpleCategories : simpleRecurings;
    });

    function getNametrsType() {
        return searchType.value;
    }
</script>