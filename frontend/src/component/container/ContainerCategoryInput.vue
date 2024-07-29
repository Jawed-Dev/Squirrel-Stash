<template>
    <div class="flex flex-col w-1/2 min-w-[250px] lg:min-w-[350px] md:w-1/4">
        <label class="text-white font-light" for="input-category">Catégorie</label>
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