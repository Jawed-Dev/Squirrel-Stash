<template>
    <div class="flex justify-end flex-col w-[15%]">
        <label class="text-white text-[18px] font-light" for="input-category">Catégorie</label>
        <div class="flex">
                <InputBase 
                    unicode="🔍"
                    v-model="searchCategory" 
                    width="w-fit"
                    :listSelect="filterListTransactions"
                    extraClass="text-[16px]"
                    placeholder="Ex : Restaurant"
                    id="input-category"
                    type="text"
                />
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import InputBase from '@/component/input/InputBase.vue';
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