<template>
    <div class="flex flex-col w-1/3 min-w-[270px] lg:min-w-[280px] 2xl:min-w-[300px] md:w-1/4">
        <label class="pl-2 text-white font-light" for="input-category">Catégorie</label>
        <InputBase 
            iconName="Category"
            v-model="searchCategory" 
            placeholder="Ex : Restaurant"
            id="input-category"
            type="text"
            :hideAnimation="true"
        />
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import InputBase from '@/components/input/InputBase.vue';
    import { listPurchases, listRecurings } from '@/svgUtils/listTransactionSvgs';

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